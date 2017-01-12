<?php

/** Widget Code */
class LCCC_Feed_Widget extends WP_Widget {

	/**
	 * Sets up the widget's name and attributes
		*/
	public function __construct() {
		$widget_ops = array(
			'classname' 		=> 'LCCC_Feed_Widget',
			'description' =>	'LCCC Feed widget for displaying LCCC Events from other LCCC web sites.',
		);
		parent::__construct( 'LCCC_Feed_Widget', 'LCCC Feed Widget', $widget_ops );
	}

	/**
		* Outputs the content of the widget
		*
		* @param array $args
		* @param array $instance
		*
		*/

		public function widget( $args, $instance ) {
			//outputs the content of the widget
			extract( $args );
			// these are the widget options
			$numberofposts = $instance['numberofposts'];
			$eventfeeds = $instance['selectedfeedtype'];
   $displaytype = esc_attr($instance['displaytype']);
			$wheretodisplay  = $instance['wheretodisplay'];
			$widgetheader = $instance['eventheader'];
			$whattodisplay = 'lccc_events';
			echo $before_widget;
			//Display the widget
			echo '<div class="">';
			echo '  <div class="">';
			echo '	 ';
			echo '  </div>';
			echo '</div>';
					 echo '<div class="small-12 medium-12 large-12 columns lccc_events">';
//displays the header block of the events
	if( $widgetheader == 'stocker-header'){
		echo '<div class="small-12 medium-12 large-12 columns '.$whattodisplay.'_header">';
			echo '<div class="small-12 medium-12 large-12 columns event-header-text-container">';
				echo '<h2 class="headertext">'.' Stocker Events'.'</h2>';
			echo '</div>';
		echo '</div>';
	}elseif( $widgetheader == 'athletics-header'){
echo '<div class="small-12 medium-12 large-12 columns '.$whattodisplay.'_header">';
			echo '<div class="small-12 medium-12 large-12 columns event-header-text-container">';
				echo '<h2 class="athletics-headertext">'.' Athletics Events'.'</h2>';
			echo '</div>';
		echo '</div>';
}elseif (  $widgetheader =='lccc-header' ){
		echo '<div class="small-12 medium-12 large-12 columns '.$whattodisplay.'_header">';
			echo '<div class="small-5 medium-5 large-5 columns '.$whattodisplay.' headerlogo">';
   echo '<img src="' . plugins_url( '../images/lccc-logo.svg', __FILE__ ) . '"  height="60" width="73" alt="Lorain County Community College Logo" > ';
			echo '</div>';
			echo '<div class="small-7 medium-7 large-7 columns event-header-text-container">';
				echo '<h2 class="headertext">'.'Events'.'</h2>';
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

		//$lcccevents = '';
		//$stockerevents = '';
		//$athleticevents = '';
//Grab posts (endpoints)

		$lcccevents = '';
		$stockerevents = '';
		$athleticevents = '';
		$sportevents = '';
		$categoryevents = '';
		//$numberoffeeds = 3;
		//$displaynumber = $numberofposts/$numberoffeeds;

	//Grab posts (endpoints)
  $domain = 'http://' . $_SERVER['SERVER_NAME'];
  //$domain = 'http://test.lorainccc.edu';
	switch ( $eventfeeds ){
		case 'all-events':
   //?filter[posts_per_page]='.$displaynumber.'
			$lcccevents = new Endpoint( $domain . '/mylccc/wp-json/wp/v2/lccc_events?filter[posts_per_page]=-1' );
			$athleticevents = new Endpoint( $domain . '/athletics/wp-json/wp/v2/lccc_events?per_page=100' );
			$stockerevents = new Endpoint( 'http://sites.lorainccc.edu/stocker/wp-json/wp/v2/lccc_events?filter[posts_per_page]=-1' );
			break;

		case 'all-athletics':
			$athleticevents = new Endpoint( $domain . '/athletics/wp-json/wp/v2/lccc_events?per_page=100' );
			break;

		case 'all-stocker':
			$stockerevents = new Endpoint( 'http://sites.lorainccc.edu/stocker/wp-json/wp/v2/lccc_events?filter[posts_per_page]=-1' );
			break;
			case 'volleyball':
			case 'baseball':
			case 'mens-basketball':
			case 'womens-basketball':
			case 'cross-country':
			case 'softball':
				$sportevents = new Endpoint( $domain . '/athletics/wp-json/wp/v2/lccc_events?filter[event_categories]='.$eventfeeds );
			break;
			default:
			$categoryevents = new Endpoint( $domain . '/mylccc/wp-json/wp/v2/lccc_events?filter[event_categories]='.$eventfeeds );
	}


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

		if ( $sportevents != ''){
		$multi->add_endpoint ( $sportevents );
	};
		if ( $categoryevents != ''){
		$multi->add_endpoint ( $categoryevents );
	};


	//Fetch Endpoints
	$posts = $multi->get_posts();
	if(empty($posts)){
		echo 'No Posts Found!';
	}
	
usort( $posts, function ( $a, $b) {
return strtotime( $a->event_start_date ) - strtotime( $b->event_start_date );
});

   $icounter = 1;
   $currentdate = date("Y-m-d");
   $currentday = date("d");
   $currentmonth = date("m");
   $currentmonthname = date("M");

	//$posts will be an array of all posts sorted by post date

 foreach ( $posts as $post ){

  if( $icounter <= $numberofposts ){

   if( $post->event_end_date > $currentdate ){
    echo '<div class="small-12 medium-12 large-12 columns eventcontainer">';
    echo ' <div class="samll-12 medium-12 large-3 columns calendar-small">';
    
    $date = date_create($post->event_end_date);
    $post_month = date_format($date, 'm');

    //if ( $post_month <= $currentmonth ){
     //echo ' <p class="month">'. $currentmonthname .'</p>';
    //} else {
     echo ' <p class="month">'.$post->event_start_date_month.'</p>';
    //}

    //if( $post->event_start_date < $currentdate && $post->event_end_date >= $currentdate ){
     //echo ' <p class="day">'. $currentday . '</p>';
    //}else{
     echo ' <p class="day">'.$post->event_start_date_day.'</p>';
    //}
    echo ' </div>';
    echo ' <div class="small-12 medium-12 large-9 columns">';
switch($displaytype){
     case 'expanded':
?>
     <a href="<?php echo $post->link; ?>"><?php echo $post->title->rendered; ?></a><?php

      echo ' <p>' . $post->excerpt->rendered . '</p>' ;
     break;

     case 'collapsed':
?>
     <a href="<?php echo $post->link; ?>" style="font-weight:600;"><?php echo $post->title->rendered; ?></a><br />
     <?php
      if($post->event_start_date != ''){
       $postdate = new DateTime($post->event_start_date);
      echo $postdate->format('M. j, Y');
      }

      if($post->event_start_time != ''){
	      $posttime = new DateTime($post->event_start_time);
       echo ' - ' . $posttime->format('g:i a') . '<br />';
      }else{
       echo '<br />';
      }
      if($post->event_location != ''){
       echo '<span style="font-size:.9rem;">Location: ' . $post->event_location . '</span>';
      }
     break;
    }
    echo ' </div>';
    echo '</div>';
    $icounter++;

   }

		}

 }

   /* Generate View all button at bottom of event feed
    * Based upon which event feed is being shown.
    */

				switch ( $eventfeeds ){
						case 'all-events':
									echo '<div class="small-12 medium-12 large-12 columns view-all-link">';
										echo '<a href="' . $domain . '/mylccc/lccc_events/" class="button expand">View All Events </a>';
									echo '</div>';
							echo '</div>';
						break;
						case 'all-athletics':
									echo '<div class="small-12 medium-12 large-12 columns view-all-link">';
										echo '<a href="' . $domain . '/athletics/lccc_events/" class="button expand">View All Events </a>';
									echo '</div>';
							echo '</div>';
						break;
						case 'all-stocker':
									echo '<div class="small-12 medium-12 large-12 columns view-all-link">';
										echo '<a href="' . $domain . '/stocker/lccc_events/" class="button expand">View All Events </a>';
									echo '</div>';
							echo '</div>';
						break;
						case 'volleyball':
						case 'baseball':
						case 'mens-basketball':
						case 'womens-basketball':
						case 'cross-country':
						case 'softball':
							echo '<div class="small-12 medium-12 large-12 columns view-all-link">';
										echo '<a href="' . $domain . '/athletics/event-categories/'.$eventfeeds.'" class="button expand">View All Events </a>';
									echo '</div>';
							echo '</div>';
						break;
						default:
						echo '<div class="small-12 medium-12 large-12 columns view-all-link">';
										echo '<a href="' . $domain . '/mylccc/event-categories/'.$eventfeeds.'" class="button expand">View All Events </a>';
									echo '</div>';
							echo '</div>';
				}
			echo $after_widget;
	}
	/**
		*	Outputs the options form on admin
		*
		* @param array $instance The widget options
		*/

	public function form($instance) {
		// outputs the options form on admin

		// Check values
		if( $instance ){
			$numberofposts = esc_attr($instance['numberofposts']);
			$eventheader = esc_attr($instance['eventheader']);
   $displaytype = esc_attr($instance['displaytype']);
			$selectedfeedtype = esc_attr($instance['selectedfeedtype']);
			$wheretodisplay = esc_attr($instance['wheretodisplay']);
		} else {
			$numberofposts = '';
			$eventheader = '';
			$eventfeeds = '';
			$wheretodisplay = '';
		}
		?>
		<p>
<label for="<?php echo $this->get_field_id('displaytype'); ?>"><?php _e('Display Type', 'wp_widget_plugin'); ?></label>
<select name="<?php echo $this->get_field_name('displaytype'); ?>" id="<?php echo $this->get_field_id('displaytype'); ?>">
<?php
$options = array('select...', 'collapsed', 'expanded');
foreach ($options as $option) {
echo '<option value="' . $option . '" id="' . $option . '"', $displaytype == $option ? ' selected="selected"' : '', '>', $option, '</option>';
}
?>
</select>
</p>

		<p>
<label for="<?php echo $this->get_field_id('eventheader'); ?>"><?php _e('Type of Header', 'wp_widget_plugin'); ?></label>
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
	<label for="<?php echo $this->get_field_id('numberofposts'); ?>"><?php _e('Number of posts', 'lc_myinfo_feed'); ?></label>
	<select name="<?php echo $this->get_field_name('numberofposts'); ?>" id="<?php echo $this->get_field_id('numberofposts'); ?>">
		<?php
			$options = array('select..', 5, 10, 15);
		foreach ($options as $option) {
			echo '<option value="' . $option . '" id="' . $option . '"', $numberofposts == $option ? 'selected="selected"' : '', '>', $option, '</option>';
		}
		?>
	</select>
</p>

<p>
	<label for="<?php echo $this->get_field_id('selectedfeedtype'); ?>"><?php _e('Feed Name:', 'lc_myinfo_feed');?></label>
<select name="<?php echo $this->get_field_name('selectedfeedtype'); ?>" id="<?php echo $this->get_field_id('selectedfeedtype'); ?>" class="widefat">
	<?php
		$feedtypes = array('select..', 'All Events', 'All Stocker', 'All Athletics', '&nbsp;&nbsp;-&nbsp;Volleyball',  '&nbsp;&nbsp;-&nbsp;Baseball','&nbsp;&nbsp;-&nbsp;Mens Basketball', '&nbsp;&nbsp;-&nbsp;Womens Basketball', '&nbsp;&nbsp;-&nbsp;Cross Country', '&nbsp;&nbsp;-&nbsp;Softball','Enrollment','Financial Services','Careers','Bookstore','Library','Student Life','Faculty','eLearning','Community','Early College','Fab Lab','Fitness and Rec','Human Resources','Learning Centers','Security','Veterans','Womens Link');
		foreach ( $feedtypes as $feedtype ) {
   $feedtypeslug = trim(str_replace('&nbsp;&nbsp;-&nbsp;', '', $feedtype));
			$feedtypeslug = strtolower(str_replace(' ', '-', $feedtypeslug));
			echo '<option value="' . $feedtypeslug . '" id="' . $feedtype . '"', $selectedfeedtype == $feedtypeslug ? 'selected="selected"' : '', '>', $feedtype, '</option>';
		}
		?>
	</select>
</p>
<?php
	}

	/**
		* Processing widget options on save
		*
		* @param array $new_instance 'The new options'.
		* @param array $old_instance 'The previous (old) options'.
		*/

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = $old_instance;
		// fields
		$instance['numberofposts'] = strip_tags($new_instance['numberofposts']);
		$instance['selectedfeedtype'] = strip_tags($new_instance['selectedfeedtype']);
		$instance['eventheader'] = strip_tags($new_instance['eventheader']);
  $instance['displaytype'] = strip_tags($new_instance['displaytype']);
		return $instance;
	}
}
add_action( 'widgets_init', function(){
	register_widget( 'LCCC_Feed_Widget' );
});


?>