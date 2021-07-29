<?php
/* add_ons_php */

class Esb_Class_iCal_Sync {
    // private static $_instance;

    protected $post_id;

    var $file_text;
    var $cal;
    var $event_count;
    var $todo_count;
    var $last_key;

    public function __construct($post_id) {
        // add_action( 'wp_loaded', array( $this, 'ical_import' ), 20 ); 
        // add_action( 'wp_loaded', array( $this, 'ical_export' ), 20 ); 
        $this->post_id = $post_id;


        
    }

    // public static function getInstance() {
    //     if ( ! ( self::$_instance instanceof self ) ) {
    //         self::$_instance = new self();
    //     }

    //     return self::$_instance;
    // }

    private function __clone() {
    }

    private function __wakeup() {
    }

    public function sync_import(){
        $url = get_post_meta( $this->post_id, ESB_META_PREFIX.'ical_url', true );
        // $url = 'https://www.airbnb.gr/calendar/ical/35422908.ics?s=dc6f0b741863ae6e0d01eb1e7fbcf0eb';
        if( empty($url) ) return false;

        $cal_key = get_post_type( $this->post_id ) == 'lrooms' ? ESB_META_PREFIX.'calendar' : ESB_META_PREFIX.'listing_dates';


        $this->parse( $url );

        $ical_data = $this->get_all_data();
        // echo '<pre>';
        // var_dump($ical_data); die;
        $calDates = array();
        $ical_unavailable = true; // tru to remove from calendar
        if (!empty($ical_data['VEVENT'])) {
            foreach ($ical_data['VEVENT'] as $key => $data) {
                // array(4) {
                //   ["DTEND"]=>
                //   string(8) "20211127"
                //   ["DTSTART"]=>
                //   string(8) "20210701"
                //   ["UID"]=>
                //   string(56) "6fec1092d3fa-ef0c41705a630814ba614b3cfc5d4e63@airbnb.com"
                //   ["SUMMARY"]=>
                //   string(22) "Airbnb (Not available)"
                // }
                //get StartDate And StartTime
                $start_dttimearr = explode('T', $data['DTSTART']);
                // $StartDate = $start_dttimearr[0];
                // $startTime = $start_dttimearr[1];
                if( !empty($start_dttimearr[0]) ){
                    $stdate = townhub_addons_format_cal_date($start_dttimearr[0]);
                    $calDates[] = Esb_Class_Date::format( $stdate );

                    $end_dttimearr = explode('T', $data['DTEND']);
                    if( !empty($end_dttimearr[0]) && $end_dttimearr[0] != $start_dttimearr[0] ){
                        $eddate = townhub_addons_format_cal_date($end_dttimearr[0]);
                        $dayEndObj = new DateTime($eddate);
                        for ($i=1; $i < 1000 ; $i++) { 
                            $temp = Esb_Class_Date::modify( $stdate, $i, 'Y-m-d' );
                            $tempObj = new DateTime($temp);
                            if( $tempObj >= $dayEndObj ) break;
                            $calDates[] = Esb_Class_Date::format( $temp );
                        }

                    }
                }

                //get EndDate And EndTime
                // $end_dttimearr = explode('T', $data['DTEND']);
                // $EndDate = $end_dttimearr[0];
                // $EndTime = $end_dttimearr[1];

            }
        }
        // echo '<pre>';
        // var_dump($calDates); die;
        if( !empty($calDates) ){
            $available = get_post_meta( $this->post_id, $cal_key, true );
            if( $ical_unavailable ){
                if( !empty($available) ){
                    $available = explode(";", $available);
                    $new_avai = array_diff($available, $calDates);
                    update_post_meta( $this->post_id, $cal_key, implode(";", $new_avai) );
                }
            }else{
                if( !empty($available) ){
                    $available = explode(";", $available);
                    $calDates = array_merge($available,$calDates);
                    $calDates = array_unique($calDates);
                    asort($calDates, SORT_NUMERIC);
                }
                update_post_meta( $this->post_id, $cal_key, implode(";", $calDates) );
            }

        }

    }

    function parse($url) {
        $this->cal = array(); // new empty array

        $this->event_count = -1;

        $response = wp_remote_get( esc_url_raw( $url ) );

        if ( is_wp_error( $response ) || ! isset( $response['body'] ) ) {
            return '';
        }

        // $response = wp_remote_retrieve_body( $request );

        // var_dump($response);
        // die;
        
        // if ( 'OK' !== wp_remote_retrieve_response_message( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ){
        //     return false;
        // }

        $this->file_text = $response['body'];

        $this->file_text = preg_split("[\n]", $this->file_text);
        // echo '<pre>';
        // var_dump( $this->file_text );die;
        
        if ( !stristr($this->file_text[0], 'BEGIN:VCALENDAR') )
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

        // echo '<pre>';
        // var_dump( $this->cal );die;
        
        return $this->cal;
    }

    

    
    

    function get_event_count() {
        return $this->event_count;
    }

    function get_todo_count() {
        return $this->todo_count;
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

// Esb_Class_iCal_Sync::getInstance();
