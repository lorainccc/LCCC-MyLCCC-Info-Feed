<?php
/** Widget Code */
class LCCC_Whats_Going_On_Event_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
				'classname' => 'LCCC_Whats_Going_On_Event_Widget',
			'description' => 'LCCC Whats Going On Event Widget for displaying LCCC Events on LCCC sites',
		);
		parent::__construct( 'LCCC_Whats_Going_On_Event_Widgett', 'LCCC Whats Going On Event Widget', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
  extract( $args );
   // these are the widget options
			$numberofposts = $instance['numberofposts']; 
			$whattodisplay = 'lccc_events';
			$widgetcategory = $instance['category'];
			$widgetheader = $instance['eventheader'];
   echo $before_widget;
   // Display the widget
		 echo '<div class="small-12 medium-12 large-12 columns '.$whattodisplay.'">';
		 if ($whattodisplay == 'lccc_events'){
if( $widgetheader == 'stocker-header'){
echo '<div class="small-12 medium-12 large-12 columns '.$whattodisplay.'_header">';
		echo '<div class="small-12 medium-12 large-12 columns event-header-text-container">';
			echo '<h2 class="headertext">'.' Stocker Events'.'</h2>';
		echo '</div>';
	echo '</div>';
}else{ 	
	echo '<div class="small-12 medium-12 large-12 columns '.$whattodisplay.'_header">';
		echo '<div class="small-5 medium-5 large-5 columns '.$whattodisplay.' headerlogo">';
					echo '<img src="' . plugins_url( '../images/lccc-logo.svg', __FILE__ ) . '"  height="60" width="73" alt="Lorain County Community College Logo" > ';
		echo '</div>';
		echo '<div class="small-7 medium-7 large-7 columns event-header-text-container">';
			echo '<h2 class="headertext">'.'Events'.'</h2>';
		echo '</div>';
	echo '</div>';
}
			}
	  
 if ($whattodisplay == 'lccc_events'){
				$today = getdate();
				$currentDay = $today['mday'];
				$month = $today['mon'];
				$year = $today['year'];
				$firsteventdate ='';
    $nexteventdate ='';
				$todaysevents = '';
				$temp = strLen($currentDay);            
				$twoDay = '';
	   $nextTwoDay = '';
    if ($temp < 2){
							$twoDay = '0' . $currentDay;
				}else{
							$twoDay = $currentDay;
				}
				$twomonth = '';
    $tempmonth = strLen($month);
    if ($tempmonth < 2){
							$twomonth = '0' . $month;
				}else{
							$twomonth = $month;
				}
			 $nextDay = $currentDay + 1;
				if ($temp < 2){
							$nextTwoDay = '0' . $currentDay;
				}else{
							$nextTwoDay = $currentDay;
				}
     $today = "$year-$twomonth-$twoDay";
					
					$eventargs=array(
					'post_type' => 'lccc_events',
  					'posts_per_page' => $numberofposts,
					'order'	=> 'ASC',
					'orderby'=> 'meta_value',
					'meta_key' => 'event_start_date',
					);
					$newevents = new WP_Query($eventargs);
					if ( $newevents->have_posts() ) :
							while ( $newevents->have_posts() ) : $newevents->the_post();

		$starteventdate = event_meta_box_get_meta('event_start_date');
		$starteventtime = event_meta_box_get_meta('event_start_time');  
		$endeventdate = event_meta_box_get_meta('event_end_date');
		$endtime = event_meta_box_get_meta('event_end_time');
		$starttimevar=strtotime($starteventtime);
		$starttime=	date("g:i a",$starttimevar);
		$starteventtimehours = date("G",$starttimevar);
		$starteventtimeminutes = date("i",$starttimevar);
		$startdate=strtotime($starteventdate);
		$displaystartevent = date("m/d/Y",$startdate);
		$eventstartdate=date("Y-m-d",$startdate);
		$eventstartmonth=date("M",$startdate);
		$eventstartday =date("j",$startdate);								
		$endeventtimevar=strtotime($endtime);
		$endeventtime = date("h:i a",$endeventtimevar);
		$endeventtimehours = date("G",$endeventtimevar);
		$endeventtimeminutes = date("i",$endeventtimevar);
		$enddate=strtotime($endeventdate);
		$endeventdate = date("Y-m-d",$enddate);								
		$duration = '';
		if($endeventtimehours == 0){
			$endeventtimehours =24;
		}
		$durationhours =	$endeventtimehours - $starteventtimehours;
		if($durationhours > 0){
				if($durationhours == 24){
				$duration .= '1 day';
				}else{
				$duration .= $durationhours.'hrs'; 
				}
		}
		$durationminutes = $endeventtimeminutes - $starteventtimeminutes;
		if($durationminutes > 0){
			$duration .= $durationminutes.'mins';
		}
										
	$date=strtotime($today);
	$today_event_month=date("M",$date);
         $today_event_day=date("j",$date);
         //echo 'Today:'.$today.'<br />';
	//echo 'Event Start Date and Time: '. $starteventdate.'<br />';
	//echo 'Event Start:'.$eventstartdate.'<br />';
	//echo 'Event Start Time:'.$starttime.'<br />';
	//echo 'Event Start Month:'.$eventstartmonth.'<br />';
	//echo 'Event Start Day:'.$eventstartday.'<br />';
	//echo 'Event End Date:'.$endeventdate.'<br />';
	if( $endeventdate >= $today){
							echo '<div class="small-12 medium-12 large-12 columns eventcontainer">';
							echo '<div class="small-12 medium-12 large-3 columns calender-small">';
							echo '<p class="month">'.$eventstartmonth.'</p>';
							echo '<p class="day">'.$eventstartday.'</p>';
							echo '</div>';
							echo '<div class="small-12 medium-12 large-9 columns">';
															?><a href="<?php the_permalink();?>"><?php the_title('<h3 class="eventtitle">','</h3>');?></a>
								<?php
						echo $displaystartevent;
							echo '<p style="font-weight: bold;margin-bottom: 0;">Start Time: '.$starttime.'</p>';	
											the_excerpt('<p>','</p>');
							echo '</div>';

							echo '</div>';

					}
					
							endwhile;
					endif;
		}
		echo '<div class="small-12 medium-12 large-12 columns view-all-link">';
							echo '<a href="'.get_post_type_archive_link( $whattodisplay ).'" class="button expand">View All Events </a>';
		echo '</div>';	
		echo '</div>';
		

		

  echo $after_widget;
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		
// Check values
if( $instance) {
					$eventheader = esc_attr($instance['eventheader']);
					$numberofposts = esc_attr($instance['numberofposts']);
					$widgetcategory = esc_attr($instance['category']);
} else {
					$eventheader = '';
					$numberofposts = '';
					$widgetcategory = '';
}
?>

		<p>
<label for="<?php echo $this->get_field_id('eventheader'); ?>"><?php _e('Number of posts', 'wp_widget_plugin'); ?></label>
<select name="<?php echo $this->get_field_name('eventheader'); ?>" id="<?php echo $this->get_field_id('eventheader'); ?>">
<?php
$options = array('lccc-header','stocker-header','athletics-header','sport-header');
foreach ($options as $option) {
echo '<option value="' . $option . '" id="' . $option . '"', $eventheader == $option ? ' selected="selected"' : '', '>', $option, '</option>';
}
?>
</select>		
</p>
<p>
<label for="<?php echo $this->get_field_id('numberofposts'); ?>"><?php _e('Number of posts', 'wp_widget_plugin'); ?></label>
<select name="<?php echo $this->get_field_name('numberofposts'); ?>" id="<?php echo $this->get_field_id('numberofposts'); ?>">
<?php
$options = array('select..',5, 10, 15);
foreach ($options as $option) {
echo '<option value="' . $option . '" id="' . $option . '"', $numberofposts == $option ? ' selected="selected"' : '', '>', $option, '</option>';
}
?>
</select>
</p>
		<p>
<label for="<?php echo $this->get_field_id('whattodisplay'); ?>"><?php _e('Where To Display:', 'wp_widget_plugin'); ?></label>

<select name="<?php echo $this->get_field_name('whattodisplay'); ?>" id="<?php echo $this->get_field_id('whattodisplay'); ?>"class="widefat">
		<?php
$options = array('select..','sitewide','stocker-home','athletics-home','athletics-cross-country', 'athletics-womens-basketball','athletics-womens-softball', 'athletics-womens-volleyball', 'athletics-mens-baseball', 'athletics-mens-basketball', 'athletics-club' , 'getting-started', 'student-resources','programs-and-careers','campus-life','business-services','community-services','about',);
foreach ($options as $option) {
echo '<option value="' . $option . '" id="' . $option . '"', $widgetcategory == $option ? ' selected="selected"' : '', '>', $option, '</option>';
}
?>
</select>		
		
</p>
<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		       $instance = $old_instance;
      // Fields
     	$instance['numberofposts'] = strip_tags($new_instance['numberofposts']);
	$instance['whattodisplay'] = strip_tags($new_instance['whattodisplay']);
	$instance['category'] = strip_tags($new_instance['category']);
	$instance['eventheader'] = strip_tags($new_instance['eventheader']);
					return $instance;
	}
}
add_action( 'widgets_init', function(){
	register_widget( 'LCCC_Whats_Going_On_Event_Widget' );
});
?>