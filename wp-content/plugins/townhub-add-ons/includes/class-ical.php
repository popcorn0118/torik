<?php
/* add_ons_php */

class Esb_Class_iCal {
    private static $_instance;

    var $file_text;
    var $cal;
    var $event_count;
    var $todo_count;
    var $last_key;

    private function __construct() {
        add_action( 'wp_loaded', array( $this, 'ical_import' ), 20 ); 
        add_action( 'wp_loaded', array( $this, 'ical_export' ), 20 ); 

        
    }

    public static function getInstance() {
        if ( ! ( self::$_instance instanceof self ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    public function ical_import(){
        if ( !isset( $_POST['action'] ) || $_POST['action'] != 'ical_import') {
            return;
        }
        Esb_Class_Form_Handler::verify_nonce('ical_import');

        $listing = (isset($_POST['ical-listing']) && $_POST['ical-listing'] != '') ? sanitize_text_field($_POST['ical-listing']) : '';
        $room = (isset($_POST['ical-room']) && $_POST['ical-room'] != '') ? sanitize_text_field($_POST['ical-room']) : '';

        $for_room = isset($_POST['for_room']) && $_POST['for_room'] == 'yes';
        $cal_key = $for_room ? ESB_META_PREFIX.'calendar' : ESB_META_PREFIX.'listing_dates';

        if( (empty($room) && $for_room) || (empty($listing) && !$for_room) ){
            $this->ical_redirect('post');
        }

        if( !isset($_FILES['ical_file']) || empty( $_FILES['ical_file'] ) ){
            $this->ical_redirect('file');
        }

        $post_id = !empty($room) && $for_room ? $room : $listing;

        if( get_current_user_id() != get_post_field( 'post_author', $post_id ) ){
            $this->ical_redirect('not_allow');
        }

        $movefile = townhub_addons_handle_image_upload( $_FILES['ical_file'] );

        if( is_array($movefile) ){
            $attachment = array(
                'post_mime_type' => $movefile['type'],
                'post_title'     => sanitize_file_name(basename($movefile['file'])),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );
            // // Insert the attachment.
            $attach_id = wp_insert_attachment( $attachment, $movefile['file'], $post_id );
            if( $attach_id && !is_wp_error( $attach_id ) ){
                // $content = @file_get_contents( get_attached_file( $attach_id ) );

                $this->parse( get_attached_file( $attach_id ) );

                $ical_data = $this->get_all_data();
                $calDates = array();
                if (!empty($ical_data['VEVENT'])) {
                    foreach ($ical_data['VEVENT'] as $key => $data) {
                        //get StartDate And StartTime
                        $start_dttimearr = explode('T', $data['DTSTART']);
                        // $StartDate = $start_dttimearr[0];
                        // $startTime = $start_dttimearr[1];
                        if( !empty($start_dttimearr[0]) ){
                            $calDates[] = Esb_Class_Date::format( $start_dttimearr[0] );
                        }

                        //get EndDate And EndTime
                        // $end_dttimearr = explode('T', $data['DTEND']);
                        // $EndDate = $end_dttimearr[0];
                        // $EndTime = $end_dttimearr[1];

                    }
                }
                if( !empty($calDates) ){
                    $available = get_post_meta( $post_id, $cal_key, true );
                    if( isset($_POST['ical_unavailable']) && $_POST['ical_unavailable'] == 'yes' ){
                        if( !empty($available) ){
                            $available = explode(";", $available);
                            $new_avai = array_diff($available, $calDates);
                            update_post_meta( $post_id, $cal_key, implode(";", $new_avai) );
                        }
                    }else{
                        if( !empty($available) ){
                            $available = explode(";", $available);
                            $calDates = array_merge($available,$calDates);
                            $calDates = array_unique($calDates);
                            asort($calDates, SORT_NUMERIC);
                        }
                        update_post_meta( $post_id, $cal_key, implode(";", $calDates) );
                    }
                    $this->ical_redirect('success');
                }
            }
        }else{
            // upload file error
            $this->ical_redirect('upload');
        }
    }

    public function ical_export(){
        if ( !isset( $_POST['action'] ) || $_POST['action'] != 'ical_export') {
            return;
        }
        Esb_Class_Form_Handler::verify_nonce('ical_export');

        $listing = (isset($_POST['ical-listing']) && $_POST['ical-listing'] != '') ? sanitize_text_field($_POST['ical-listing']) : '';
        $room = (isset($_POST['ical-room']) && $_POST['ical-room'] != '') ? sanitize_text_field($_POST['ical-room']) : '';

        $for_room = isset($_POST['for_room']) && $_POST['for_room'] == 'yes';
        $cal_key = $for_room ? ESB_META_PREFIX.'calendar' : ESB_META_PREFIX.'listing_dates';

        if( (empty($room) && $for_room) || (empty($listing) && !$for_room) ){
            $this->ical_redirect('post', true);
        }

        $post_id = !empty($room) && $for_room ? $room : $listing;

        if( get_current_user_id() != get_post_field( 'post_author', $post_id ) ){
            $this->ical_redirect('not_allow', true);
        }

        $available = get_post_meta( $post_id, $cal_key, true );
        if( !empty($available) ){
            $available = explode(";", $available);
            $ics_data = "BEGIN:VCALENDAR\n";
            $ics_data .= "VERSION:2.0\n";
            $ics_data .= "PRODID:PHP\n";
            $ics_data .= "METHOD:PUBLISH\n";
            $ics_data .= "X-WR-CALNAME:Schedule\n";

            # Change the timezone if needed
            // $ics_data .= "X-WR-TIMEZONE:Asia/Kolkata\n";
            // $ics_data .= "BEGIN:VTIMEZONE\n";
            // $ics_data .= "TZID:Asia/Kolkata\n";
            // $ics_data .= "BEGIN:DAYLIGHT\n";
            // $ics_data .= "TZOFFSETFROM:-0500\n";
            // $ics_data .= "TZOFFSETTO:-0400\n";
            // $ics_data .= "DTSTART:1403086496\n";
            // $ics_data .= "RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=2SU\n";
            // $ics_data .= "TZNAME:EDT\n";
            // $ics_data .= "END:DAYLIGHT\n";
            // $ics_data .= "BEGIN:STANDARD\n";
            // $ics_data .= "TZOFFSETFROM:-0400\n";
            // $ics_data .= "TZOFFSETTO:-0500\n";
            // $ics_data .= "DTSTART:1403086496\n";
            // $ics_data .= "RRULE:FREQ=YEARLY;BYMONTH=11;BYDAY=1SU\n";
            // $ics_data .= "TZNAME:EST\n";
            // $ics_data .= "END:STANDARD\n";
            // $ics_data .= "END:VTIMEZONE\n";

            foreach ($available as $date) {
                # Change TimeZone if needed
                $ics_data .= "BEGIN:VEVENT\n";
                $ics_data .= "DTSTART:".$date ."\n";
                $ics_data .= "DTEND:" . $date . "\n";
                $ics_data .= "DTSTAMP:" . date('Ymd') . "T" . date('His') . "Z\n";
                $ics_data .= "LOCATION:" . get_post_meta( $post_id, '_cth_address', true ) . "\n";
                // $ics_data .= "DESCRIPTION:" . $description . "\n";
                $ics_data .= "SUMMARY:" . sprintf(_x( '%s (Available)', 'iCal Export', 'townhub-add-ons' ), get_the_title($post_id) ) . "\n";
                // $ics_data .= "UID:" . $id . "\n";
                $ics_data .= "SEQUENCE:0\n";
                $ics_data .= "END:VEVENT\n";
            }   

            // while ($available) {
            // $id = $event_details['ID'];
            // $start_date = $event_details['StartDate'];
            // $start_time = $event_details['StartTime'];
            // $end_date = $event_details['EndDate'];
            // $end_time = $event_details['EndTime'];
            // $name = $event_details['Title'];
            // $location = $event_details['Location'];
            // $description = $event_details['Description'];

            // # Replace HTML tags
            // $search = array("/<br>/","/&amp;/","/&rarr;/","/&larr;/","/,/","/;/");
            // $replace = array("\\n","&","-->","<--","\\,","\\;"); 

            // $name = preg_replace($search, $replace, $name);
            // $location = preg_replace($search, $replace, $location);
            // $description = preg_replace($search, $replace, $description);

            // # Change TimeZone if needed
            // $ics_data .= "BEGIN:VEVENT\n";
            // $ics_data .= "DTSTART;TZID=Asia/Kolkata:".$start_date."T".$start_time."\n";
            // $ics_data .= "DTEND:" . $end_date . "T" . $end_time . "\n";
            // $ics_data .= "DTSTAMP:" . date('Ymd') . "T" . date('His') . "Z\n";
            // $ics_data .= "LOCATION:" . $location . "\n";
            // $ics_data .= "DESCRIPTION:" . $description . "\n";
            // $ics_data .= "SUMMARY:" . $name . "\n";
            // $ics_data .= "UID:" . $id . "\n";
            // $ics_data .= "SEQUENCE:0\n";
            // $ics_data .= "END:VEVENT\n";
            // }
            $ics_data .= "END:VCALENDAR\n";

            // $this->ical_redirect('', true);

            // output headers so that the file is downloaded rather than displayed
            header('Content-Type: text/calendar; charset=utf-8');
            header('Content-Disposition: attachment; filename='.sanitize_title_with_dashes( get_the_title($post_id) ).'.ics');

            echo $ics_data;
            
            exit(); 
        }else{
            $this->ical_redirect('empty', true);
        }
    }

    private function ical_redirect( $result = '', $export = false ){
        $args = array('_result' => $result);
        if( $export ){
            $args = array_merge( array( 'export'=> '' ), $args );
        }
        $url = add_query_arg( $args , Esb_Class_Dashboard::screen_url('ical') );
        wp_safe_redirect( $url );
        exit;
    }

    public static function get_result_message( $code ) {
        switch ( $code ) {
            case 'success':
                return _x( 'iCal synchronization was successful.', 'Dashboard iCal', 'townhub-add-ons' );
            case 'post':
                return _x( 'You need to select a listing or room to sync dates to.', 'Dashboard iCal', 'townhub-add-ons' );
            case 'file':
                return _x( 'You need to upload an iCal (.ics) file.', 'Dashboard iCal', 'townhub-add-ons' );
            case 'not_allow':
                return _x( 'You are not allow to sync dates with the selected listing/room.', 'Dashboard iCal', 'townhub-add-ons' );
            case 'upload':
                return _x( 'Upload file error.', 'Dashboard iCal', 'townhub-add-ons' );
            case 'empty':
                return _x( 'There is no available dates to export.', 'Dashboard iCal', 'townhub-add-ons' );
            default:
                break;
        }
         
        return '';
    }
    
    function read_file($file) {
        $this->file = $file;
        $file_text = join("", file($file)); //load file
        $file_text = preg_replace("/[\r\n]{1,} ([:;])/", "\\1", $file_text);
        return $file_text; // return all text
    }

    function get_event_count() {
        return $this->event_count;
    }

    function get_todo_count() {
        return $this->todo_count;
    }
    
    function parse($uri) {
        $this->cal = array(); // new empty array

        $this->event_count = -1;
        $this->file_text = $this->read_file($uri);

        $this->file_text = preg_split("[\n]", $this->file_text);
        if (!stristr($this->file_text[0], 'BEGIN:VCALENDAR'))
            return 'error not VCALENDAR';

        foreach ($this->file_text as $text) {

            $text = trim($text);
            if (!empty($text)) {
                list($key, $value) = $this->retun_key_value($text);

                switch ($text) {
                    case "BEGIN:VTODO":
                        $this->todo_count = $this->todo_count + 1; 
                        $type = "VTODO";
                        break;

                    case "BEGIN:VEVENT":
                        $this->event_count = $this->event_count + 1; 
                        $type = "VEVENT";
                        break;

                    case "BEGIN:VCALENDAR":
                    case "BEGIN:DAYLIGHT":
                    case "BEGIN:VTIMEZONE":
                    case "BEGIN:STANDARD":
                        $type = $value; 
                        break;

                    case "END:VTODO": 
                    case "END:VEVENT":

                    case "END:VCALENDAR":
                    case "END:DAYLIGHT":
                    case "END:VTIMEZONE":
                    case "END:STANDARD":
                        $type = "VCALENDAR";
                        break;

                    default: 
                        $this->add_to_array($type, $key, $value); 
                        break;
                }
            }
        }
        return $this->cal;
    }


    function add_to_array($type, $key, $value) {
        if ($key == false) {
            $key = $this->last_key;
            switch ($type) {
                case 'VEVENT': $value = $this->cal[$type][$this->event_count][$key] . $value;
                    break;
                case 'VTODO': $value = $this->cal[$type][$this->todo_count][$key] . $value;
                    break;
            }
        }

        if (($key == "DTSTAMP") or ($key == "LAST-MODIFIED") or ($key == "CREATED"))
            $value = $this->ical_date_to_unix($value);
        if ($key == "RRULE")
            $value = $this->ical_rrule($value);
        if (stristr($key, "DTSTART") or stristr($key, "DTEND")){
            $my_arr = explode("T",$value);
            $cdate = $my_arr[0];
            if( count($my_arr) >= 2 ){
                $cdate .= " ".$my_arr[1]; 
            }
            list($key, $cdate) = $this->ical_dt_date($key, $cdate);
        }

        switch ($type) {
            case "VTODO":
                $this->cal[$type][$this->todo_count][$key] = $value;
                break;

            case "VEVENT":
                $this->cal[$type][$this->event_count][$key] = $value;
                break;

            default:
                $this->cal[$type][$key] = $value;
                break;
        }
        $this->last_key = $key;
    }

    function retun_key_value($text) {
        preg_match("/([^:]+)[:]([\w\W]+)/", $text, $matches);

        if (empty($matches)) {
            return array(false, $text);
        } else {
            $matches = array_splice($matches, 1, 2);
            return $matches;
        }
    }

    function ical_rrule($value) {
        $rrule = explode(';', $value);
        foreach ($rrule as $line) {
            $rcontent = explode('=', $line);
            $result[$rcontent[0]] = $rcontent[1];
        }
        return $result;
    }

    function ical_date_to_unix($ical_date) {
        $ical_date = str_replace('T', '', $ical_date);
        $ical_date = str_replace('Z', '', $ical_date);

        preg_match('/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{0,2})([0-9]{0,2})([0-9]{0,2})/', $ical_date, $date);

        if ($date[1] <= 1970) {
            $date[1] = 1971;
        }
        if( empty($date[1]) ) $date[1] = date("Y");
        if( empty($date[2]) ) $date[2] = date("n");
        if( empty($date[3]) ) $date[3] = date("j");
        if( empty($date[4]) ) $date[4] = date("H");
        if( empty($date[5]) ) $date[5] = date("i");
        if( empty($date[6]) ) $date[6] = date("s");
        return mktime($date[4], $date[5], $date[6], $date[2], $date[3], $date[1]);
    }

    function ical_dt_date($key, $value) {
        $value = $this->ical_date_to_unix($value);

        $temp = explode(";", $key);

        if (empty($temp[1])) { // DTEND:20190130T133000Z not DTSTART;VALUE=DATE:20210701
            // $data = str_replace('T', '', $data);
            return array($key, $value);
        }

        $key = $temp[0];
        $temp = explode("=", $temp[1]);
        $return_value[$temp[0]] = $temp[1];
        $return_value['unixtime'] = $value;

        return array($key, $return_value);
    }

    function get_sort_event_list() {
        $temp = $this->get_event_list();
        if (!empty($temp)) {
            usort($temp, array(&$this, "ical_dtstart_compare"));
            return $temp;
        } else {
            return false;
        }
    }

    function ical_dtstart_compare($a, $b) {
        return strnatcasecmp($a['DTSTART']['unixtime'], $b['DTSTART']['unixtime']);
    }

    function get_event_list() {
        return $this->cal['VEVENT'];
    }

    function get_todo_list() {
        return $this->cal['VTODO'];
    }

    function get_calender_data() {
        return $this->cal['VCALENDAR'];
    }

    function get_all_data() {
        return $this->cal;
    }

}

Esb_Class_iCal::getInstance();
