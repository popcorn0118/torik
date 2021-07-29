<?php
/* add_ons_php */

class Esb_Class_Date
{

    public static function timezone()
    {
        $tz = get_option('timezone_string');
        if ($tz) {
            return $tz;
        } else if ( $offset = get_option('gmt_offset') ) {
            return self::tz_offset__name( $offset );
        }
        return 'UTC';
    }

    public static function tz_offset__name($offset)
    {
        $offset *= 3600; // convert hour offset to seconds
        $abbrarray = timezone_abbreviations_list();
        if($abbrarray){
            foreach ($abbrarray as $abbr)
            {
                foreach ($abbr as $city)
                {
                    if ($city['offset'] == $offset)
                    {
                        return $city['timezone_id'];
                    }
                }
            }
        }
            

        return 'UTC';
        // return false;
    }

    public static function tz_details($tz = ''){
        if( empty($tz) ) $tz = self::timezone();
        $listingTimezone = new DateTimeZone( $tz );
        
        $utcTimezone        = new DateTimeZone('UTC');
        $utcDateTime        = new DateTime('now', $utcTimezone);
        $utcPrevDateTime    = new DateTime('now-1day', $utcTimezone);
        // $currentDateTime = new DateTime('now', $listingTimezone);
        // $prevDateTime    = new DateTime('now-1day', $listingTimezone);
        // change utc time to listing time zone
        $utcDateTime->setTimezone($listingTimezone);
        $utcPrevDateTime->setTimezone($listingTimezone);
        return array(
            // 'tz_offset'     => $listingTimezone->getOffset($utcDateTime),
            'tz_offset'     => $utcDateTime->format('Z'),
            // 'day'           => $utcDateTime->format('l'), //Sunday - Saturday 
            'day'           => $utcDateTime->format('D'), // Mon - Sun
            // 'hour'          => $utcDateTime->format('G.i'),
            'hour'          => $utcDateTime->format('His'),
            'date'          => $utcDateTime->format('Y-m-d H:i:s'),

            // 'prev_day'      => $utcPrevDateTime->format('l'), //Sunday - Saturday 
            'prev_day'      => $utcPrevDateTime->format('D'), // Mon - Sun
        );
    }
    // get utc offset from listing timezone -> for mysql CONVERT_TZ
    public static function utc_tz_offset($tz=''){
        if( empty($tz) ) $tz = self::timezone();
        $listingTimezone = new DateTimeZone( $tz );

        $date = new DateTime('now', $listingTimezone);

        // create a new date offset by the timezone offset
        // gets the interval as hours & minutes
        $offset = $listingTimezone->getOffset($date) . ' seconds';
        $dateOffset = clone $date;
        $dateOffset->sub(DateInterval::createFromDateString($offset));

        $interval = $dateOffset->diff($date);
        return $interval->format('%R%H:%I');
    }

    public static function week_days(){
        $days = array(
            'Mon' => __( 'Monday',  'townhub-add-ons' ),
            'Tue' => __( 'Tuesday',  'townhub-add-ons' ),
            'Wed' => __( 'Wednesday',  'townhub-add-ons' ),
            'Thu' => __( 'Thursday',  'townhub-add-ons' ),
            'Fri' => __( 'Friday',  'townhub-add-ons' ),
            'Sat' => __( 'Saturday',  'townhub-add-ons' ),
            'Sun' => __( 'Sunday',  'townhub-add-ons' ),
        );
        return $days;
    }

    public static function wkhours_select()
    {
        $hours = array(
            '00:00:00' => _x('12:00 AM','Working hours', 'townhub-add-ons'),
            '00:05:00' => _x('12:05 AM','Working hours', 'townhub-add-ons'),
            '00:10:00' => _x('12:10 AM','Working hours', 'townhub-add-ons'),
            '00:15:00' => _x('12:15 AM','Working hours', 'townhub-add-ons'),
            '00:20:00' => _x('12:20 AM','Working hours', 'townhub-add-ons'),
            '00:25:00' => _x('12:25 AM','Working hours', 'townhub-add-ons'),

            '00:30:00' => _x('12:30 AM','Working hours', 'townhub-add-ons'),
            '00:35:00' => _x('12:35 AM','Working hours', 'townhub-add-ons'),
            '00:40:00' => _x('12:40 AM','Working hours', 'townhub-add-ons'),
            '00:45:00' => _x('12:45 AM','Working hours', 'townhub-add-ons'),
            '00:50:00' => _x('12:50 AM','Working hours', 'townhub-add-ons'),
            '00:55:00' => _x('12:55 AM','Working hours', 'townhub-add-ons'),

            '01:00:00' => _x('1:00 AM','Working hours', 'townhub-add-ons'),
            '01:05:00' => _x('1:05 AM','Working hours', 'townhub-add-ons'),
            '01:10:00' => _x('1:10 AM','Working hours', 'townhub-add-ons'),
            '01:15:00' => _x('1:15 AM','Working hours', 'townhub-add-ons'),
            '01:20:00' => _x('1:20 AM','Working hours', 'townhub-add-ons'),
            '01:25:00' => _x('1:25 AM','Working hours', 'townhub-add-ons'),

            '01:30:00' => _x('1:30 AM','Working hours', 'townhub-add-ons'),
            '01:35:00' => _x('1:35 AM','Working hours', 'townhub-add-ons'),
            '01:40:00' => _x('1:40 AM','Working hours', 'townhub-add-ons'),
            '01:45:00' => _x('1:45 AM','Working hours', 'townhub-add-ons'),
            '01:50:00' => _x('1:50 AM','Working hours', 'townhub-add-ons'),
            '01:55:00' => _x('1:55 AM','Working hours', 'townhub-add-ons'),

            '02:00:00' => _x('2:00 AM','Working hours', 'townhub-add-ons'),
            '02:05:00' => _x('2:05 AM','Working hours', 'townhub-add-ons'),
            '02:10:00' => _x('2:10 AM','Working hours', 'townhub-add-ons'),
            '02:15:00' => _x('2:15 AM','Working hours', 'townhub-add-ons'),
            '02:20:00' => _x('2:20 AM','Working hours', 'townhub-add-ons'),
            '02:25:00' => _x('2:25 AM','Working hours', 'townhub-add-ons'),

            '02:30:00' => _x('2:30 AM','Working hours', 'townhub-add-ons'),
            '02:35:00' => _x('2:35 AM','Working hours', 'townhub-add-ons'),
            '02:40:00' => _x('2:40 AM','Working hours', 'townhub-add-ons'),
            '02:45:00' => _x('2:45 AM','Working hours', 'townhub-add-ons'),
            '02:50:00' => _x('2:50 AM','Working hours', 'townhub-add-ons'),
            '02:55:00' => _x('2:55 AM','Working hours', 'townhub-add-ons'),

            '03:00:00' => _x('3:00 AM','Working hours', 'townhub-add-ons'),
            '03:05:00' => _x('3:05 AM','Working hours', 'townhub-add-ons'),
            '03:10:00' => _x('3:10 AM','Working hours', 'townhub-add-ons'),
            '03:15:00' => _x('3:15 AM','Working hours', 'townhub-add-ons'),
            '03:20:00' => _x('3:20 AM','Working hours', 'townhub-add-ons'),
            '03:25:00' => _x('3:25 AM','Working hours', 'townhub-add-ons'),

            '03:30:00' => _x('3:30 AM','Working hours', 'townhub-add-ons'),
            '03:35:00' => _x('3:35 AM','Working hours', 'townhub-add-ons'),
            '03:40:00' => _x('3:40 AM','Working hours', 'townhub-add-ons'),
            '03:45:00' => _x('3:45 AM','Working hours', 'townhub-add-ons'),
            '03:50:00' => _x('3:50 AM','Working hours', 'townhub-add-ons'),
            '03:55:00' => _x('3:55 AM','Working hours', 'townhub-add-ons'),

            '04:00:00' => _x('4:00 AM','Working hours', 'townhub-add-ons'),
            '04:05:00' => _x('4:05 AM','Working hours', 'townhub-add-ons'),
            '04:10:00' => _x('4:10 AM','Working hours', 'townhub-add-ons'),
            '04:15:00' => _x('4:15 AM','Working hours', 'townhub-add-ons'),
            '04:20:00' => _x('4:20 AM','Working hours', 'townhub-add-ons'),
            '04:25:00' => _x('4:25 AM','Working hours', 'townhub-add-ons'),

            '04:30:00' => _x('4:30 AM','Working hours', 'townhub-add-ons'),
            '04:35:00' => _x('4:35 AM','Working hours', 'townhub-add-ons'),
            '04:40:00' => _x('4:40 AM','Working hours', 'townhub-add-ons'),
            '04:45:00' => _x('4:45 AM','Working hours', 'townhub-add-ons'),
            '04:50:00' => _x('4:50 AM','Working hours', 'townhub-add-ons'),
            '04:55:00' => _x('4:55 AM','Working hours', 'townhub-add-ons'),

            '05:00:00' => _x('5:00 AM','Working hours', 'townhub-add-ons'),
            '05:05:00' => _x('5:05 AM','Working hours', 'townhub-add-ons'),
            '05:10:00' => _x('5:10 AM','Working hours', 'townhub-add-ons'),
            '05:15:00' => _x('5:15 AM','Working hours', 'townhub-add-ons'),
            '05:20:00' => _x('5:20 AM','Working hours', 'townhub-add-ons'),
            '05:25:00' => _x('5:25 AM','Working hours', 'townhub-add-ons'),

            '05:30:00' => _x('5:30 AM','Working hours', 'townhub-add-ons'),
            '05:35:00' => _x('5:35 AM','Working hours', 'townhub-add-ons'),
            '05:40:00' => _x('5:40 AM','Working hours', 'townhub-add-ons'),
            '05:45:00' => _x('5:45 AM','Working hours', 'townhub-add-ons'),
            '05:50:00' => _x('5:50 AM','Working hours', 'townhub-add-ons'),
            '05:55:00' => _x('5:55 AM','Working hours', 'townhub-add-ons'),

            '06:00:00' => _x('6:00 AM','Working hours', 'townhub-add-ons'),
            '06:05:00' => _x('6:05 AM','Working hours', 'townhub-add-ons'),
            '06:10:00' => _x('6:10 AM','Working hours', 'townhub-add-ons'),
            '06:15:00' => _x('6:15 AM','Working hours', 'townhub-add-ons'),
            '06:20:00' => _x('6:20 AM','Working hours', 'townhub-add-ons'),
            '06:25:00' => _x('6:25 AM','Working hours', 'townhub-add-ons'),
            
            '06:30:00' => _x('6:30 AM','Working hours', 'townhub-add-ons'),
            '06:35:00' => _x('6:35 AM','Working hours', 'townhub-add-ons'),
            '06:40:00' => _x('6:40 AM','Working hours', 'townhub-add-ons'),
            '06:45:00' => _x('6:45 AM','Working hours', 'townhub-add-ons'),
            '06:50:00' => _x('6:50 AM','Working hours', 'townhub-add-ons'),
            '06:55:00' => _x('6:55 AM','Working hours', 'townhub-add-ons'),

            '07:00:00' => _x('7:00 AM','Working hours', 'townhub-add-ons'),
            '07:05:00' => _x('7:05 AM','Working hours', 'townhub-add-ons'),
            '07:10:00' => _x('7:10 AM','Working hours', 'townhub-add-ons'),
            '07:15:00' => _x('7:15 AM','Working hours', 'townhub-add-ons'),
            '07:20:00' => _x('7:20 AM','Working hours', 'townhub-add-ons'),
            '07:25:00' => _x('7:25 AM','Working hours', 'townhub-add-ons'),

            '07:30:00' => _x('7:30 AM','Working hours', 'townhub-add-ons'),
            '07:35:00' => _x('7:35 AM','Working hours', 'townhub-add-ons'),
            '07:40:00' => _x('7:40 AM','Working hours', 'townhub-add-ons'),
            '07:45:00' => _x('7:45 AM','Working hours', 'townhub-add-ons'),
            '07:50:00' => _x('7:50 AM','Working hours', 'townhub-add-ons'),
            '07:55:00' => _x('7:55 AM','Working hours', 'townhub-add-ons'),

            '08:00:00' => _x('8:00 AM','Working hours', 'townhub-add-ons'),
            '08:05:00' => _x('8:05 AM','Working hours', 'townhub-add-ons'),
            '08:10:00' => _x('8:10 AM','Working hours', 'townhub-add-ons'),
            '08:15:00' => _x('8:15 AM','Working hours', 'townhub-add-ons'),
            '08:20:00' => _x('8:20 AM','Working hours', 'townhub-add-ons'),
            '08:25:00' => _x('8:25 AM','Working hours', 'townhub-add-ons'),

            '08:30:00' => _x('8:30 AM','Working hours', 'townhub-add-ons'),
            '08:35:00' => _x('8:35 AM','Working hours', 'townhub-add-ons'),
            '08:40:00' => _x('8:40 AM','Working hours', 'townhub-add-ons'),
            '08:45:00' => _x('8:45 AM','Working hours', 'townhub-add-ons'),
            '08:50:00' => _x('8:50 AM','Working hours', 'townhub-add-ons'),
            '08:55:00' => _x('8:55 AM','Working hours', 'townhub-add-ons'),

            '09:00:00' => _x('9:00 AM','Working hours', 'townhub-add-ons'),
            '09:05:00' => _x('9:05 AM','Working hours', 'townhub-add-ons'),
            '09:10:00' => _x('9:10 AM','Working hours', 'townhub-add-ons'),
            '09:15:00' => _x('9:15 AM','Working hours', 'townhub-add-ons'),
            '09:20:00' => _x('9:20 AM','Working hours', 'townhub-add-ons'),
            '09:25:00' => _x('9:25 AM','Working hours', 'townhub-add-ons'),

            '09:30:00' => _x('9:30 AM','Working hours', 'townhub-add-ons'),
            '09:35:00' => _x('9:35 AM','Working hours', 'townhub-add-ons'),
            '09:40:00' => _x('9:40 AM','Working hours', 'townhub-add-ons'),
            '09:45:00' => _x('9:45 AM','Working hours', 'townhub-add-ons'),
            '09:50:00' => _x('9:50 AM','Working hours', 'townhub-add-ons'),
            '09:55:00' => _x('9:55 AM','Working hours', 'townhub-add-ons'),

            '10:00:00' => _x('10:00 AM','Working hours', 'townhub-add-ons'),
            '10:05:00' => _x('10:05 AM','Working hours', 'townhub-add-ons'),
            '10:10:00' => _x('10:10 AM','Working hours', 'townhub-add-ons'),
            '10:15:00' => _x('10:15 AM','Working hours', 'townhub-add-ons'),
            '10:20:00' => _x('10:20 AM','Working hours', 'townhub-add-ons'),
            '10:25:00' => _x('10:25 AM','Working hours', 'townhub-add-ons'),

            '10:30:00' => _x('10:30 AM','Working hours', 'townhub-add-ons'),
            '10:35:00' => _x('10:35 AM','Working hours', 'townhub-add-ons'),
            '10:40:00' => _x('10:40 AM','Working hours', 'townhub-add-ons'),
            '10:45:00' => _x('10:45 AM','Working hours', 'townhub-add-ons'),
            '10:50:00' => _x('10:50 AM','Working hours', 'townhub-add-ons'),
            '10:55:00' => _x('10:55 AM','Working hours', 'townhub-add-ons'),

            '11:00:00' => _x('11:00 AM','Working hours', 'townhub-add-ons'),
            '11:05:00' => _x('11:05 AM','Working hours', 'townhub-add-ons'),
            '11:10:00' => _x('11:10 AM','Working hours', 'townhub-add-ons'),
            '11:15:00' => _x('11:15 AM','Working hours', 'townhub-add-ons'),
            '11:20:00' => _x('11:20 AM','Working hours', 'townhub-add-ons'),
            '11:25:00' => _x('11:25 AM','Working hours', 'townhub-add-ons'),

            '11:30:00' => _x('11:30 AM','Working hours', 'townhub-add-ons'),
            '11:35:00' => _x('11:35 AM','Working hours', 'townhub-add-ons'),
            '11:40:00' => _x('11:40 AM','Working hours', 'townhub-add-ons'),
            '11:45:00' => _x('11:45 AM','Working hours', 'townhub-add-ons'),
            '11:50:00' => _x('11:50 AM','Working hours', 'townhub-add-ons'),
            '11:55:00' => _x('11:55 AM','Working hours', 'townhub-add-ons'),

            '12:00:00' => _x('12:00 PM','Working hours', 'townhub-add-ons'),
            '12:05:00' => _x('12:05 PM','Working hours', 'townhub-add-ons'),
            '12:10:00' => _x('12:10 PM','Working hours', 'townhub-add-ons'),
            '12:15:00' => _x('12:15 PM','Working hours', 'townhub-add-ons'),
            '12:20:00' => _x('12:20 PM','Working hours', 'townhub-add-ons'),
            '12:25:00' => _x('12:25 PM','Working hours', 'townhub-add-ons'),

            '12:30:00' => _x('12:30 PM','Working hours', 'townhub-add-ons'),
            '12:35:00' => _x('12:35 PM','Working hours', 'townhub-add-ons'),
            '12:40:00' => _x('12:40 PM','Working hours', 'townhub-add-ons'),
            '12:45:00' => _x('12:45 PM','Working hours', 'townhub-add-ons'),
            '12:50:00' => _x('12:50 PM','Working hours', 'townhub-add-ons'),
            '12:55:00' => _x('12:55 PM','Working hours', 'townhub-add-ons'),

            '13:00:00' => _x('1:00 PM','Working hours', 'townhub-add-ons'),
            '13:05:00' => _x('1:05 PM','Working hours', 'townhub-add-ons'),
            '13:10:00' => _x('1:10 PM','Working hours', 'townhub-add-ons'),
            '13:15:00' => _x('1:15 PM','Working hours', 'townhub-add-ons'),
            '13:20:00' => _x('1:20 PM','Working hours', 'townhub-add-ons'),
            '13:25:00' => _x('1:25 PM','Working hours', 'townhub-add-ons'),

            '13:30:00' => _x('1:30 PM','Working hours', 'townhub-add-ons'),
            '13:35:00' => _x('1:35 PM','Working hours', 'townhub-add-ons'),
            '13:40:00' => _x('1:40 PM','Working hours', 'townhub-add-ons'),
            '13:45:00' => _x('1:45 PM','Working hours', 'townhub-add-ons'),
            '13:50:00' => _x('1:50 PM','Working hours', 'townhub-add-ons'),
            '13:55:00' => _x('1:55 PM','Working hours', 'townhub-add-ons'),

            '14:00:00' => _x('2:00 PM','Working hours', 'townhub-add-ons'),
            '14:05:00' => _x('2:05 PM','Working hours', 'townhub-add-ons'),
            '14:10:00' => _x('2:10 PM','Working hours', 'townhub-add-ons'),
            '14:15:00' => _x('2:15 PM','Working hours', 'townhub-add-ons'),
            '14:20:00' => _x('2:20 PM','Working hours', 'townhub-add-ons'),
            '14:25:00' => _x('2:25 PM','Working hours', 'townhub-add-ons'),

            '14:30:00' => _x('2:30 PM','Working hours', 'townhub-add-ons'),
            '14:35:00' => _x('2:35 PM','Working hours', 'townhub-add-ons'),
            '14:40:00' => _x('2:40 PM','Working hours', 'townhub-add-ons'),
            '14:45:00' => _x('2:45 PM','Working hours', 'townhub-add-ons'),
            '14:50:00' => _x('2:50 PM','Working hours', 'townhub-add-ons'),
            '14:55:00' => _x('2:55 PM','Working hours', 'townhub-add-ons'),

            '15:00:00' => _x('3:00 PM','Working hours', 'townhub-add-ons'),
            '15:05:00' => _x('3:05 PM','Working hours', 'townhub-add-ons'),
            '15:10:00' => _x('3:10 PM','Working hours', 'townhub-add-ons'),
            '15:15:00' => _x('3:15 PM','Working hours', 'townhub-add-ons'),
            '15:20:00' => _x('3:20 PM','Working hours', 'townhub-add-ons'),
            '15:25:00' => _x('3:25 PM','Working hours', 'townhub-add-ons'),

            '15:30:00' => _x('3:30 PM','Working hours', 'townhub-add-ons'),
            '15:35:00' => _x('3:35 PM','Working hours', 'townhub-add-ons'),
            '15:40:00' => _x('3:40 PM','Working hours', 'townhub-add-ons'),
            '15:45:00' => _x('3:45 PM','Working hours', 'townhub-add-ons'),
            '15:50:00' => _x('3:50 PM','Working hours', 'townhub-add-ons'),
            '15:55:00' => _x('3:55 PM','Working hours', 'townhub-add-ons'),

            '16:00:00' => _x('4:00 PM','Working hours', 'townhub-add-ons'),
            '16:05:00' => _x('4:05 PM','Working hours', 'townhub-add-ons'),
            '16:10:00' => _x('4:10 PM','Working hours', 'townhub-add-ons'),
            '16:15:00' => _x('4:15 PM','Working hours', 'townhub-add-ons'),
            '16:20:00' => _x('4:20 PM','Working hours', 'townhub-add-ons'),
            '16:25:00' => _x('4:25 PM','Working hours', 'townhub-add-ons'),

            '16:30:00' => _x('4:30 PM','Working hours', 'townhub-add-ons'),
            '16:35:00' => _x('4:35 PM','Working hours', 'townhub-add-ons'),
            '16:40:00' => _x('4:40 PM','Working hours', 'townhub-add-ons'),
            '16:45:00' => _x('4:45 PM','Working hours', 'townhub-add-ons'),
            '16:50:00' => _x('4:50 PM','Working hours', 'townhub-add-ons'),
            '16:55:00' => _x('4:55 PM','Working hours', 'townhub-add-ons'),

            '17:00:00' => _x('5:00 PM','Working hours', 'townhub-add-ons'),
            '17:05:00' => _x('5:05 PM','Working hours', 'townhub-add-ons'),
            '17:10:00' => _x('5:10 PM','Working hours', 'townhub-add-ons'),
            '17:15:00' => _x('5:15 PM','Working hours', 'townhub-add-ons'),
            '17:20:00' => _x('5:20 PM','Working hours', 'townhub-add-ons'),
            '17:25:00' => _x('5:25 PM','Working hours', 'townhub-add-ons'),

            '17:30:00' => _x('5:30 PM','Working hours', 'townhub-add-ons'),
            '17:35:00' => _x('5:35 PM','Working hours', 'townhub-add-ons'),
            '17:40:00' => _x('5:40 PM','Working hours', 'townhub-add-ons'),
            '17:45:00' => _x('5:45 PM','Working hours', 'townhub-add-ons'),
            '17:50:00' => _x('5:50 PM','Working hours', 'townhub-add-ons'),
            '17:55:00' => _x('5:55 PM','Working hours', 'townhub-add-ons'),

            '18:00:00' => _x('6:00 PM','Working hours', 'townhub-add-ons'),
            '18:05:00' => _x('6:05 PM','Working hours', 'townhub-add-ons'),
            '18:10:00' => _x('6:10 PM','Working hours', 'townhub-add-ons'),
            '18:15:00' => _x('6:15 PM','Working hours', 'townhub-add-ons'),
            '18:20:00' => _x('6:20 PM','Working hours', 'townhub-add-ons'),
            '18:25:00' => _x('6:25 PM','Working hours', 'townhub-add-ons'),

            '18:30:00' => _x('6:30 PM','Working hours', 'townhub-add-ons'),
            '18:35:00' => _x('6:35 PM','Working hours', 'townhub-add-ons'),
            '18:40:00' => _x('6:40 PM','Working hours', 'townhub-add-ons'),
            '18:45:00' => _x('6:45 PM','Working hours', 'townhub-add-ons'),
            '18:50:00' => _x('6:50 PM','Working hours', 'townhub-add-ons'),
            '18:55:00' => _x('6:55 PM','Working hours', 'townhub-add-ons'),

            '19:00:00' => _x('7:00 PM','Working hours', 'townhub-add-ons'),
            '19:05:00' => _x('7:05 PM','Working hours', 'townhub-add-ons'),
            '19:10:00' => _x('7:10 PM','Working hours', 'townhub-add-ons'),
            '19:15:00' => _x('7:15 PM','Working hours', 'townhub-add-ons'),
            '19:20:00' => _x('7:20 PM','Working hours', 'townhub-add-ons'),
            '19:25:00' => _x('7:25 PM','Working hours', 'townhub-add-ons'),

            '19:30:00' => _x('7:30 PM','Working hours', 'townhub-add-ons'),
            '19:35:00' => _x('7:35 PM','Working hours', 'townhub-add-ons'),
            '19:40:00' => _x('7:40 PM','Working hours', 'townhub-add-ons'),
            '19:45:00' => _x('7:45 PM','Working hours', 'townhub-add-ons'),
            '19:50:00' => _x('7:50 PM','Working hours', 'townhub-add-ons'),
            '19:55:00' => _x('7:55 PM','Working hours', 'townhub-add-ons'),

            '20:00:00' => _x('8:00 PM','Working hours', 'townhub-add-ons'),
            '20:05:00' => _x('8:05 PM','Working hours', 'townhub-add-ons'),
            '20:10:00' => _x('8:10 PM','Working hours', 'townhub-add-ons'),
            '20:15:00' => _x('8:15 PM','Working hours', 'townhub-add-ons'),
            '20:20:00' => _x('8:20 PM','Working hours', 'townhub-add-ons'),
            '20:25:00' => _x('8:25 PM','Working hours', 'townhub-add-ons'),

            '20:30:00' => _x('8:30 PM','Working hours', 'townhub-add-ons'),
            '20:35:00' => _x('8:35 PM','Working hours', 'townhub-add-ons'),
            '20:40:00' => _x('8:40 PM','Working hours', 'townhub-add-ons'),
            '20:45:00' => _x('8:45 PM','Working hours', 'townhub-add-ons'),
            '20:50:00' => _x('8:50 PM','Working hours', 'townhub-add-ons'),
            '20:55:00' => _x('8:55 PM','Working hours', 'townhub-add-ons'),

            '21:00:00' => _x('9:00 PM','Working hours', 'townhub-add-ons'),
            '21:05:00' => _x('9:05 PM','Working hours', 'townhub-add-ons'),
            '21:10:00' => _x('9:10 PM','Working hours', 'townhub-add-ons'),
            '21:15:00' => _x('9:15 PM','Working hours', 'townhub-add-ons'),
            '21:20:00' => _x('9:20 PM','Working hours', 'townhub-add-ons'),
            '21:25:00' => _x('9:25 PM','Working hours', 'townhub-add-ons'),

            '21:30:00' => _x('9:30 PM','Working hours', 'townhub-add-ons'),
            '21:35:00' => _x('9:35 PM','Working hours', 'townhub-add-ons'),
            '21:40:00' => _x('9:40 PM','Working hours', 'townhub-add-ons'),
            '21:45:00' => _x('9:45 PM','Working hours', 'townhub-add-ons'),
            '21:50:00' => _x('9:50 PM','Working hours', 'townhub-add-ons'),
            '21:55:00' => _x('9:55 PM','Working hours', 'townhub-add-ons'),

            '22:00:00' => _x('10:00 PM','Working hours', 'townhub-add-ons'),
            '22:05:00' => _x('10:05 PM','Working hours', 'townhub-add-ons'),
            '22:10:00' => _x('10:10 PM','Working hours', 'townhub-add-ons'),
            '22:15:00' => _x('10:15 PM','Working hours', 'townhub-add-ons'),
            '22:20:00' => _x('10:20 PM','Working hours', 'townhub-add-ons'),
            '22:25:00' => _x('10:25 PM','Working hours', 'townhub-add-ons'),

            '22:30:00' => _x('10:30 PM','Working hours', 'townhub-add-ons'),
            '22:35:00' => _x('10:35 PM','Working hours', 'townhub-add-ons'),
            '22:40:00' => _x('10:40 PM','Working hours', 'townhub-add-ons'),
            '22:45:00' => _x('10:45 PM','Working hours', 'townhub-add-ons'),
            '22:50:00' => _x('10:50 PM','Working hours', 'townhub-add-ons'),
            '22:55:00' => _x('10:55 PM','Working hours', 'townhub-add-ons'),

            '23:00:00' => _x('11:00 PM','Working hours', 'townhub-add-ons'),
            '23:05:00' => _x('11:05 PM','Working hours', 'townhub-add-ons'),
            '23:10:00' => _x('11:10 PM','Working hours', 'townhub-add-ons'),
            '23:15:00' => _x('11:15 PM','Working hours', 'townhub-add-ons'),
            '23:20:00' => _x('11:20 PM','Working hours', 'townhub-add-ons'),
            '23:25:00' => _x('11:25 PM','Working hours', 'townhub-add-ons'),

            '23:30:00' => _x('11:30 PM','Working hours', 'townhub-add-ons'),
            '23:35:00' => _x('11:35 PM','Working hours', 'townhub-add-ons'),
            '23:40:00' => _x('11:40 PM','Working hours', 'townhub-add-ons'),
            '23:45:00' => _x('11:45 PM','Working hours', 'townhub-add-ons'),
            '23:50:00' => _x('11:50 PM','Working hours', 'townhub-add-ons'),
            '23:55:00' => _x('11:55 PM','Working hours', 'townhub-add-ons'),

            // '24:00:00' => __( '12:00 PM',  'townhub-add-ons' ),

        );
        return (array) apply_filters('cth_wkhours_select', $hours);
    }
    public static function reformat($date = '', $time = false, $format = '' ){
        if($date == 'NEVER') return esc_html__( 'NEVER', 'townhub-add-ons' ); // return $date;
        $dateObj = new DateTime($date);
        if($format == ''){
            $format = get_option('date_format');
            if($time) $format .= ' '.get_option( 'time_format' );
        }
        return $dateObj->format($format);
    }
    public static function i18n($date = '', $time = false, $format = '', $gmt = false ){
        if($date == 'NEVER') return esc_html__( 'NEVER', 'townhub-add-ons' ); // return $date;
        $timestamp = strtotime($date);
        if($format == ''){
            $format = get_option('date_format');
            if($time) $format .= ' '.get_option( 'time_format' );
        }
        if( $timestamp ){
            return date_i18n($format, $timestamp, $gmt);
        }else{
            return $date;
        }
    }
    public static function format($date = 'now', $format = 'Ymd'){
        $dateObj = new DateTime($date);
        return $dateObj->format($format);
    }
    public static function format_new($date = '', $format = '', $time = false ){
        if($date == 'NEVER') return $date;
        $dateObj = new DateTime($date);
        if($format == ''){
            $format = get_option('date_format');
            if($time) $format .= ' '.get_option( 'time_format' );
        }
        return $dateObj->format($format);
    }

    public static function modify($date = 'now', $modify = 0, $format = 'Ymd'){
        $dateObj = new DateTime($date);
        if($dateObj){
            $dateObj->modify($modify .' days');
            return $dateObj->format($format);
        }
        return false; 
    }
    public static function compare($date_one = '', $date_two = '', $compare = '<'){
        $tz = wp_timezone();
        $date_one = new DateTime($date_one, $tz);
        $date_two = new DateTime($date_two, $tz);
        $date_one = $date_one->format('YmdHis');
        $date_two = $date_two->format('YmdHis');
        $result = false;
        switch ($compare) {
            case '<=':
                $result =  $date_one <= $date_two;
                break;
            case '=':
                $result =  $date_one == $date_two;
                break;
            case '>=':
                $result =  $date_one >= $date_two;
                break;
            case '<':
                $result =  $date_one < $date_two;
                break;
            case '>':
                $result =  $date_one > $date_two;
                break;
            default:
                $result =  $date_one < $date_two;
                break;
        }
        return $result;
    }
    public static function js_offset(){
        $offset  = get_option( 'gmt_offset' );
        $hours   = (int) $offset;
        $minutes = abs( ( $offset - (int) $offset ) * 60 );
        $offset  = sprintf( '%+03d:%02d', $hours, $minutes );

        return $offset;
    }
}
