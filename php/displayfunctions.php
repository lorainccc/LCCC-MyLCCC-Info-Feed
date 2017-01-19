<?php

//function lccc_calendar($day, $month, $year){
//			do_action('lccc_calendar',$day, $month, $year);
//}
function todayPosts($month, $currentDay, $year){
				$lcccevents = '';
				$stockerevents = '';
				$athleticevents = '';
	
		//Grab posts (endpoints)
  $domain = 'http://' . $_SERVER['SERVER_NAME'];
	
	   //?filter[posts_per_page]='.$displaynumber.'
			$lcccevents = new Endpoint( $domain . '/mylccc/wp-json/wp/v2/lccc_events?filter[posts_per_page]=-1' );
			$athleticevents = new Endpoint( $domain . '/athletics/wp-json/wp/v2/lccc_events?per_page=100' );
			$stockerevents = new Endpoint( 'http://sites.lorainccc.edu/stocker/wp-json/wp/v2/lccc_events?filter[posts_per_page]=-1' );
	
		//Create instance
	$multi = new MultiBlog( 1 );
	
		//Add endpoints to instance
	if ( $lcccacademicevents != ''){
		$multi->add_endpoint ( $lcccacademicevents );
	};
	if ( $lcccevents != ''){
		$multi->add_endpoint ( $lcccevents );
	};
	if ( $athleticevents != ''){
		$multi->add_endpoint ( $athleticevents );
	};

	if ( $stockerevents != ''){
		$multi->add_endpoint ( $stockerevents );
	};
	//Fetch Endpoints
	$posts = $multi->get_posts();
	if(empty($posts)){
		echo 'No Posts Found!';
	}
	
usort( $posts, function ( $a, $b) {
return strtotime( $a->event_start_date ) - strtotime( $b->event_start_date );
});
	
				global $myvar;
				global $date;
				global $event_month;
				global $event_day;
				global $event_year;
				$todaysevents = '';
				$temp = strLen($currentDay);
				$twoDay = '';
	   $nextTwoDay = '';
				if ($temp < 2){
					$twoDay = '0' . $currentDay;
				}else{
					$twoDay = $currentDay;
				}
			 $nextDay = $currentDay + 1;
				if ($temp < 2){
					$nextTwoDay = '0' . $currentDay;
				}else{
					$nextTwoDay = $currentDay;
				}
				$currentDate = $year . '-' . $month . '-' . $twoDay;
				if($posts !=''){	
							$todaysevents .= '<ul class="calendardayseventslist">';
					foreach ( $posts as $post ){
									if(strtotime($post->event_start_date) == strtotime($currentDate)){

											$todaysevents .= '<li><a href="'.$post->links.'">'.$post->title->rendered.'</a></li>';
									}
						}
						$todaysevents .= '</ul>';
				}
		return $todaysevents;
}
function build_calendar($day,$month,$year){
					$month=$month;
					$year=$year;
 // Create array containing abbreviations of days of week.
     $daysOfWeek = array('Sun','Mon','Tues','Wed','Thurs','Fri','Sat');

									// What is the first day of the month in question?
									$firstDayOfMonth = mktime(0,0,0,$month,1,$year);
									// How many days does this month contain?
									$numberDays = date('t',$firstDayOfMonth);
									// Retrieve some information about the first day of the
									// month in question.
									$dateComponents = getdate($firstDayOfMonth);
									// What is the name of the month in question?
									$monthName = $dateComponents['month'];
									// What is the index value (0-6) of the first day of the
									// month in question.
									$dayOfWeek = $dateComponents['wday'];
									// Create the table tag opener and day headers
									$calendar = "<table class='calendar'>";
									$calendar .= "<caption>$monthName $year</caption>";
									$calendar .= "<tr>";
									// Create the calendar headers
									foreach($daysOfWeek as $day) {
														$calendar .= "<th class='header'>$day</th>";
									} 
									// Create the rest of the calendar
									// Initiate the day counter, starting with the 1st.
									$currentDay = 1;
									$calendar .= "</tr><tr>";
									// The variable $dayOfWeek is used to
									// ensure that the calendar
									// display consists of exactly 7 columns.
									if ($dayOfWeek > 0) { 
														$calendar .= "<td colspan='$dayOfWeek' class='not-month'>&nbsp;</td>"; 
									}

									$month = str_pad($month, 2, "0", STR_PAD_LEFT);

									while ($currentDay <= $numberDays) {
														// Seventh column (Saturday) reached. Start a new row.
														if ($dayOfWeek == 7) {
																			$dayOfWeek = 0;
																			$calendar .= "</tr><tr>";
														}

														$currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);

														$date = "$year-$month-$currentDayRel";
										
														if ($date == date("Y-m-d")){
															$calendar .= "<td class='day today' rel='$date'><span class='calendar-today'><a class='datelink-currentday' href='/day/?d=$date'>$currentDay</a></span><span class='event_entries'>".todayPosts($month,$currentDay,$year)."</span></td>";
														}
														else{
															$calendar .= "<td class='day' rel='$date'><span class='day-date'><a class='datelink' href='/day/?d=$date'>$currentDay</a></span><span class='event_entries'>".todayPosts($month,$currentDay,$year)."</span></td>";
														}

														// Increment counters

														$currentDay++;
														$dayOfWeek++;
									}


									// Complete the row of the last week in month, if necessary
									if ($dayOfWeek != 7) { 

														$remainingDays = 7 - $dayOfWeek;
														$calendar .= "<td colspan='$remainingDays' class='not-month'>&nbsp;</td>"; 
									}

									$calendar .= "</tr>";
									$calendar .= "</table>";
									echo $calendar;	

				}
				add_action('lccc_calendar', 'build_calendar', 10, 3); 
function build_previousMonth($month,$year,$monthString){
	global $myvar;
	global $event_month;
		$prevMonth = $month - 1;  
  if ($prevMonth == 0) {
   $prevMonth = 12;
  }
  
 if ($prevMonth == 12){  
  $prevYear = $year - 1;
 } else {
  $prevYear = $year;
 }
 $startday = 01;
 $dateObj = DateTime::createFromFormat('!m', $prevMonth);
 $datetodisplay = "$prevYear-$prevMonth-$startday";
	$date=strtotime($datetodisplay);
	$monthName = date('F',$date); 
	$prevMonthToDisplay = "$prevYear-$prevMonth-$startday";
 echo "<div style='display:inline-block;'><a href='calendar/?d=".$prevMonthToDisplay."'><- " . $monthName . "</a></div>";
}
	add_action('lccc_previous_month', 'build_previousMonth', 10, 3); 
function build_nextMonth($month,$year,$monthString){
 global $myvar;
	global $event_month;
	$nextMonth = $month + 1;
	
  if ($nextMonth == 13) {
   $nextMonth = 1;
  }
 
 if ($nextMonth == 1){  
  $nextYear = $year + 1;
 } else {
  $nextYear = $year;
 }
 $startday = 01;
 $dateObj = DateTime::createFromFormat('!m', $nextMonth);
 $datetodisplay = "$nextYear-$nextMonth-$startday";
	$date=strtotime($datetodisplay);
	$monthName = date('F',$date); 
	$nextmonthtodisplay = "$nextYear-$nextMonth-$startday";
 echo "<div style='text-align:right;'><a href='calendar/?d=".$nextmonthtodisplay."'>" . $monthName . " -></a></div>";
}
add_action('lccc_next_month', 'build_nextMonth', 10, 3); 

 

//Below is the functions to genrate the week view
				$displaycount =1;
				$today = getdate();		
				$currentDay = $today['mday'];
				$lastdaydisplayed = '';
				$startdaylastweek = $currentDay - 8;
				$currentmonthdisplayed = '';
				$currentyeardisplayed = '';
				$prevstartday = '';
function build_week($month,$year,$day) {
					global $lastdaydisplayed;
					global $displaycount;
					global $currentmonthdisplayed;
					global $startday;
					global $currentyeardisplayed;
					global $prevstartday;
					global $lastdate;
					$month=$month;
					$year=$year;
     // Create array containing abbreviations of days of week.
     $daysOfWeek = array('Sun','Mon','Tues','Wed','Thurs','Fri','Sat');
     // What is the first day of the month in question?
     $firstDayOfMonth = mktime(0,0,0,$month,1,$year);
     // How many days does this month contain?
     $numberDays = date('t',$firstDayOfMonth);
     // Retrieve some information about the first day of the
     // month in question.
     $dateComponents = getdate($firstDayOfMonth);
     // What is the name of the month in question?
     $monthName = $dateComponents['month'];
     // What is the index value (0-6) of the first day of the
     // month in question.
     $dayOfWeek = $dateComponents['wday'];
       // Get today's date and extract the day of the month to start on.
	 			$currentDay = $day;
						// Create the table tag opener and day headers
    $week = "<h3 class='calendar-list-header'>Week Of $monthName $currentDay, $year</h3><br />";
					$week .= "<ul class='calendarlist'>";
     //$week .= "<caption>$monthName $year</caption>";
     //$week .= "<li>";
     // Create the week headers
     //foreach($daysOfWeek as $day) {
     //     $week .= "<th class='header'>$day</th>";
     //} 
     // Create the rest of the week
 // Get today's date and extract the day of the month to start on.
     // Initiate the day counter, starting with the 1st.
					//$currentDay = 1;
	
					//$week .= "<li>";
     
					// The variable $dayOfWeek is used to
     // ensure that the week
     // display consists of exactly 7 columns.
     //if ($dayOfWeek > 0) { 
     //     $week .= "<div colspan='$dayOfWeek' class='not-month'>&nbsp;</div>"; 
     //}
     
     $month = str_pad($month, 2, "0", STR_PAD_LEFT);
  
     while (($currentDay <= $numberDays) && ($displaycount !=8)) {
          // Seventh column (Saturday) reached. Start a new row.
          if ($dayOfWeek == 7) {
               $dayOfWeek = 0;

															//$week .= "</li><li>";
          }
          
          $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
          
          $date = "$year-$month-$currentDayRel";
          
          if ($date == date("Y-m-d")){
           $week .= "<li class='day today' rel='$date'><div class='daycontainer'><span class='today-date'><a class='datelink-currentday' href='day/?d=$date'>$monthName $currentDay, $year</a></span><span class='event_entries'>".todayPosts($month,$currentDay,$year)."</span></div></li>";
          }
          else{
           $week .= "<li class='day' rel='$date'><div class='daycontainer'><span class='day-date'><a class='datelink' href='day/?d=$date'>$monthName $currentDay, $year</a></span><span class='event_entries'>".todayPosts($month,$currentDay,$year)."</span></div></li>";
          }
										$currentmonthdisplayed = $month;
										$lastdaydisplayed = $currentDay;
											$currentyeardisplayed = $year; 
          // Increment counters
          $currentDay++;
										$prevstartday = $currentDay;
          $dayOfWeek++;
										$displaycount++;
     }
     
     
     // Complete the row of the last week in month, if necessary
     //if ($dayOfWeek != 7) { 
     //     $remainingDays = 7 - $dayOfWeek;
     //     $week .= "<div colspan='$remainingDays' class='not-month'>&nbsp;</div>"; 
     //}
     
     //$week .= "</li>";
					$week .= "</ul>";
					$lastdaydisplayed = $date;
     echo $week;
}
add_action('lccc_week', 'build_week', 10, 3);

function add_to_list($month,$year,$day) {
					global $startday;
					global $lastdaydisplayed;
					global $displaycount;
					global $currentmonthdisplayed;
					global $currentyeardisplayed;
					$month=$month;
					$year=$year;
					$daysinnemonth = 8 - $displaycount;
     // Create array containing abbreviations of days of week.
     $daysOfWeek = array('Sun','Mon','Tues','Wed','Thurs','Fri','Sat');
     // What is the first day of the month in question?
     $firstDayOfMonth = mktime(0,0,0,$month,1,$year);
     // How many days does this month contain?
     $numberDays = date('t',$firstDayOfMonth);
     // Retrieve some information about the first day of the
     // month in question.
     $dateComponents = getdate($firstDayOfMonth);
     // What is the name of the month in question?
     $monthName = $dateComponents['month'];
     // What is the index value (0-6) of the first day of the
     // month in question.
     $dayOfWeek = $dateComponents['wday'];
     // Create the table tag opener and day headers
     $calendar = "<h4  class='calendar-month-subheader'>$monthName $year</h3>";
					$calendar .= "<ul class='calendarlist'>";
     //$calendar .= "<caption>$monthName $year</caption>";
     //$calendar .= "<li>";
     // Create the calendar headers
     //foreach($daysOfWeek as $day) {
     //     $calendar .= "<th class='header'>$day</th>";
     //} 
     // Create the rest of the calendar
     // Get today's date and extract the day of the month to start on.
	    //$today = getdate();		
					//$currentDay = $today['mday'];
	
     // Initiate the day counter, starting with the 1st.
					$currentDay = 1;
				
					//$calendar .= "<li>";
     
					// The variable $dayOfWeek is used to
     // ensure that the calendar
     // display consists of exactly 7 columns.
     //if ($dayOfWeek > 0) { 
     //     $calendar .= "<div colspan='$dayOfWeek' class='not-month'>&nbsp;</div>"; 
     //}
     
     $month = str_pad($month, 2, "0", STR_PAD_LEFT);
  
     while ($currentDay <= $daysinnemonth) {
          // Seventh column (Saturday) reached. Start a new row.
          if ($dayOfWeek == 7) {
               $dayOfWeek = 0;

															//$calendar .= "</li><li>";
          }
          
          $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
          
          $date = "$year-$month-$currentDayRel";
          
           if ($date == date("Y-m-d")){
           $calendar .= "<li class='day today' rel='$date'><div class='daycontainer'><span class='today-date'><a class='datelink-currentday' href='/day/?d=$date'>$monthName $currentDay, $year</a></span><span class='event_entries'>".todayPosts($month,$currentDay,$year)."</span></div></li>";
          }
          else{
           $calendar .= "<li class='day' rel='$date'><div class='daycontainer'><span class='day-date'><a class='datelink' href='/day/?d=$date'>$monthName $currentDay, $year</a></span><span class='event_entries'>".todayPosts($month,$currentDay,$year)."</span></div></li>";
          }
						$lastdaydisplayed = $date;
						$currentmonthdisplayed = $month;
						$currentyeardisplayed = $year; 
          // Increment counters
          $currentDay++;	
									if ($currentDay == $numberDays && $month == 12){
												$year ++;
										}
											$startday = $currentDay; 
          $dayOfWeek++;
										$displaycount++;
     }
     
     
     // Complete the row of the last week in month, if necessary
     //if ($dayOfWeek != 7) { 
     //     $remainingDays = 7 - $dayOfWeek;
     //     $calendar .= "<div colspan='$remainingDays' class='not-month'>&nbsp;</div>"; 
     //}
     
     //$calendar .= "</li>";

					$calendar .= "</ul>";
     echo $calendar;
}
add_action('lccc_add_to_list', 'add_to_list', 10, 3);
function build_prevWeek($year,$month,$day){
// What is the first day of the month in question?
	$firstDayOfMonth = mktime(0,0,0,$month,1,$year);
	// How many days does this month contain?
	$numberDays = date('t',$firstDayOfMonth);
	$lastdaydisplayed = $day;
	$currentmonthdisplayed = $month;
	if($lastdaydisplayed-8 < 1){
	$currentmonthdisplayed --;	
	// What is the first day of the month in question?
	$firstDayOfMonth = mktime(0,0,0,$currentmonthdisplayed,1,$year);
	// How many days does this month contain?
	$numberDays = date('t',$firstDayOfMonth);
	$daysleft = $lastdaydisplayed-7;
	$startday =$numberDays+$daysleft;
					if($currentmonthdisplayed == 0){
						$currentmonthdisplayed = 12;
						$year --;
					}
	}else{
 $startday = $lastdaydisplayed -7;
	}
	$nextdaydisplayed = "$year-$currentmonthdisplayed-$startday";
 echo "<div style='display:inline-block;'>&nbsp;</div><div style='text-align:center;'><a href='/week/?d=$nextdaydisplayed'><- Previous 7 Days</a></div>";
}
add_action('lccc_prev_week', 'build_prevWeek', 10, 3);
function build_nextWeek($year,$month,$day){
 // What is the first day of the month in question?
	$firstDayOfMonth = mktime(0,0,0,$month,1,$year);
	// How many days does this month contain?
	$numberDays = date('t',$firstDayOfMonth);
	$lastdaydisplayed = $day;
	$currentmonthdisplayed = $month;
	if($lastdaydisplayed +7 > $numberDays){
	$startday = 1;
	if($currentmonthdisplayed +1 == 13){
	$currentmonthdisplayed = 1;
	$year ++;
	}else{
	$currentmonthdisplayed = $currentmonthdisplayed +1;
	}		
	}else{
 $startday = $lastdaydisplayed +7;
	}
	$nextdaydisplayed = "$year-$currentmonthdisplayed-$startday";
	echo "<div style='display:inline-block;'>&nbsp;</div><div style='text-align:center;'><a href='/week/?d=$nextdaydisplayed'>Next 7 Days -></a></div>";
}
add_action('lccc_next_week', 'build_nextWeek', 10, 3);


?>