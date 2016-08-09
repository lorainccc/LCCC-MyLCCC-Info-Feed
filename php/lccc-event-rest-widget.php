<?php

// Call Fetch Code
//require_once( plugin_dir_path( __FILE__ ).'php/event-rest-api-fetch.php' );

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
				echo '<i class="lccc-font-lccc-reverse">'.'</i>';
			echo '</div>';
			echo '<div class="small-7 medium-7 large-7 columns event-header-text-container">';
				echo '<h2 class="headertext">'.'Events'.'</h2>';
			echo '</div>';
		echo '</div>';
	}else{ 	
		echo '<div class="small-12 medium-12 large-12 columns '.$whattodisplay.'_header">';
			echo '<div class="small-5 medium-5 large-5 columns '.$whattodisplay.' headerlogo">';
				echo '<i class="lccc-font-lccc-reverse">'.'</i>';
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
	switch ( $eventfeeds ){
		case 'all-events':
			$lcccevents = new Endpoint( 'http://lorainccc.dev/mylccc/wp-json/wp/v2/posts' );
			$athleticevents = new Endpoint( 'https://test.lorainccc.edu/athletics/wp-json/wp/v2/lccc_events' );
			$stockerevents = new Endpoint( 'http://sites.lorainccc.edu/stocker/wp-json/wp/v2/lccc_events' );			
			break;

		case 'all-athletics':
			$athleticevents = new Endpoint( 'http://test.lorainccc.edu/athletics/wp-json/wp/v2/lccc_events' );
			break;
			
		case 'all-stocker':
			$stockerevents = new Endpoint( 'http://sites.lorainccc.edu/stocker/wp-json/wp/v2/lccc_events' );
			break;
	}
		
		
	//Create instance
	$multi = new MultiBlog( 1 );
	
	//Add endpoints to instance
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
			
	//$posts will be an array of all posts sorted by post date
	foreach ( $posts as $post ){
		//echo posts		
		echo '<div class="small-12 medium-12 large-12 columns eventcontainer">';
	echo '<div class="samll-12 medium-12 large-3 columns calendar">';
		echo '</div>';
				echo '<div class="small-12 medium-12 large-9 columns">';?>
						<a href="<?php echo $post->link; ?>"><?php echo $post->title->rendered; ?></a><?php
						echo '<p>' . $post->excerpt->rendered . '</p>' ;
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
<select name="<?php echo $this->get_field_name('selectedfeedtype'); ?>" id="<?php echo $this->get_field_id('feedtype'); ?>" class="widefat">
	<?php
		$feedtypes = array('select..', 'All Events', 'All Stocker', 'All Athletics', '&nbsp;&nbsp;-&nbsp;Mens Soccer');
		foreach ( $feedtypes as $feedtype ) {
   $feedtypeslug = trim(str_replace('&nbsp;&nbsp;-&nbsp;', '', $feedtype));
			$feedtypeslug = strtolower(str_replace(' ', '-', $feedtypeslug));
			echo '<option value="' . $feedtypeslug . '" id="' . $feedtype . '"', $selectedfeedtype == $feedtypeslug ? 'selected="selected"' : '', '>', $feedtype, '</option>'; 
		}
		?>
	</select>
</p>
		<p>
<label for="<?php echo $this->get_field_id('wheretodisplay'); ?>"><?php _e('Where To Display:', 'wp_widget_plugin'); ?></label>

<select name="<?php echo $this->get_field_name('wheretodisplay'); ?>" id="<?php echo $this->get_field_id('wheretodisplay'); ?>"class="widefat">
		<?php
$options = array('select..','sitewide','stocker-home','athletics-home','athletics-cross-country', 'athletics-womens-basketball','athletics-womens-softball', 'athletics-womens-volleyball', 'athletics-mens-baseball', 'athletics-mens-basketball', 'athletics-club' , 'getting-started', 'student-resources','programs-and-careers','campus-life','business-services','community-services','about',);
foreach ($options as $option) {
echo '<option value="' . $option . '" id="' . $option . '"', $wheretodisplay == $option ? ' selected="selected"' : '', '>', $option, '</option>';
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
		$instance['wheretodisplay'] = strip_tags($new_instance['wheretodisplay']);
		$instance['eventheader'] = strip_tags($new_instance['eventheader']);
		return $instance;
	}
}
add_action( 'widgets_init', function(){
	register_widget( 'LCCC_Feed_Widget' );
});
	

?>