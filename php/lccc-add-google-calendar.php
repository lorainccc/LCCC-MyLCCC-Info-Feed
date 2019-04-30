<?php

/*
 *  Function for adding LCCC Event Calendar events to a user's Google Calendar
 *  
 * 
 *     
*/

function lc_addToGoogleCalendar($title='', $startdate='', $enddate='', $location='', $details='')
{
    $startdate = ($startdate ? $startdate : time());
    $startdate = (is_numeric($startdate) ? $startdate : strtotime($startdate));
    $enddate = ($enddate ? $enddate : $startdate + 3600);
    $enddate = (is_numeric($enddate) ? $enddate : strtotime($enddate));   
    $google_url = "http://www.google.com/calendar/event";
    $action = "?action=TEMPLATE";
    $title = ( $title ? ("&text=" . urlencode($title)) : "") ;
    $dates = "&dates=" . getIcalDate($startdate) . "Z/" . getIcalDate($enddate) . "Z";
    $location = ( $location ? ("&location=" . urlencode($location)) : "") ;
    $details = ( $details ? ("&details=" . urlencode($details)) : "") ;
    $out = $google_url . $action . $title . $dates . $location . $details;
    return $out;
}
function getIcalDate($time, $incl_time = true)
{
    return $incl_time ? date('Ymd\THis', $time) : date('Ymd', $time);
}

?>