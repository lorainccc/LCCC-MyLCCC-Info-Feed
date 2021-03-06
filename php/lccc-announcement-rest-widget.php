<?php

/** Widget Code */

class LCCC_Announcement_Feed_Widget extends WP_Widget {

 /**
  * Sets up the widgets name etc
  */
 	public function __construct() {
		$widget_ops = array(
			'classname' 		=> 'LCCC_Announcement_Feed_Widget',
			'description' =>	'LCCC Feed widget for displaying LCCC Announcements from other LCCC web sites.',
		);
		parent::__construct( 'LCCC_Announcement_Feed_Widget', 'LCCC Announcement Feed Widget', $widget_ops );
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
			$whattodisplay = 'lccc_announcement';
   $selectedfeedtype = $instance['selectedfeedtype'];
			$widgetcategory = $instance['category'];
			$displaylayout = $instance['layout'];
     
   echo $before_widget;
   // Display the widget
		 echo '<div class="small-12 medium-12 large-12 columns '.$whattodisplay.' nopadding">';
		 if ($whattodisplay == 'lccc_event'){
   echo '<div class="small-12 medium-12 large-12 columns '.$whattodisplay.'_header">';
							echo '<div class="small-12 medium-4 large-4 columns '.$whattodisplay.' headerlogo">';
											echo '<i class="lccc-font-lccc-reverse">'.'</i>';
							echo '</div>';
							echo '<div class="small-12 medium-8 large-8 columns ">';
										echo '<h2 class="headertext">'.'Events'.'</h2>';
							echo '</div>';
			echo '</div>';
			}
		if ($whattodisplay == 'lccc_announcement'){
			switch($displaylayout){
				case 'Home-page':	
			echo '<div class="small-12 medium-12 large-12 columns '.$whattodisplay.'_header">';
						echo '<h2 class="announcementheader">'.'In The News'.'</h2>';
			echo '</div>';	
				break;
				case 'Sub-page':
			   echo '<div class="small-12 medium-12 large-12 columns lccc_announcement-sub-site">';
							echo '<div class="small-12 medium-4 large-3 columns '.$whattodisplay.' headerlogo">';
											echo '<i class="lccc-font-lccc-reverse">'.'</i>';
							echo '</div>';
							echo '<div class="small-12 medium-8 large-9 columns ">';
										echo '<h2 class="headertext">'.'Announcements'.'</h2>';
							echo '</div>';
			echo '</div>';
				break;
			}
   
		}
	  	$today = getdate();
				$widgetcategory = get_cat_slug($widgetcategory);
  
		if ($whattodisplay == 'lccc_announcement'){
/*
     $announcementargs=array(
					'post_type' => 'lccc_announcement',
					'post_status' => 'publish',
					'taxonomy'	=> 'category',
					'term'	=> $widgetcategory,
					//'cat' => $widgetcategory,
					'orderby' => 'date',
					'order' => 'DESC',
					);
					$newevents = new WP_Query($announcementargs);*/

   $lcccannouncements = '';
   $athleticannouncements = '';

   //$domain = 'http://' . $_SERVER['SERVER_NAME'];
   $domain = 'http://test.lorainccc.edu';

   switch ( $selectedfeedtype ){
    case 'all-announcements':
        $lcccannouncements = new EndPoint( $domain . '/mylccc/wp-json/wp/v2/lccc_announcement' );

        $athleticannouncements = new EndPoint( $domain . '/athletics/wp-json/wp/v2/lccc_announcement' );

     break;

    case 'all-athletics':
        $athleticannouncements = new EndPoint( $domain . '/athletics/wp-json/wp/v2/lccc_announcement' );
        break;
   }


   if ($widgetcategory != ''){
    // filters by categories
    $athleticannouncements = new EndPoint( $domain .'/athletics/wp-json/wp/v2/lccc_announcement?filter[taxonomy]=category&filter[term]=' . $widgetcategory );
   }
   
   //Create instance
   $multi = new MultiBlog( 1 );

   //Add endpoints to instance
   if ( $lcccannouncements != '' ){
    $multi->add_endpoint ( $lcccannouncements );
   };

   if ($athleticannouncements != '' ){
    $multi->add_endpoint ( $athleticannouncements );
   };



   //Fetch Endpoints
   $posts = $multi->get_posts();
   if(empty ($posts)){
    echo 'No Posts Found!';
   }
			switch($displaylayout){
					case 'Home-page':	
									foreach ( $posts as $post ){
			     echo '<div class="small-12 medium-12 large-12 columns news-container">';
								echo '<div class="small-12 medium-3 large-3 columns eventhumbnail">';
								echo '<img src="' . $post->better_featured_image->media_details->sizes->thumbnail->source_url .'" border="0">';
								echo '</div>';
								echo '<div class="small-12 medium-9 large-9 columns">';
  						?>
								<a href="<?php echo $post->link; ?>"><h3><?php echo $post->title->rendered;?></h3></a>
								<?php
											echo '<p>' . $post->excerpt->rendered . '</p>';
								echo '</div>';
			  			echo '<div class="column row">';
								echo '<hr />';
 						 echo '</div>';
								echo '</div>';
          
     }

   switch ( $selectedfeedtype ){
    case 'all-announcements' :
							$currentpostype = 'Announcements';
       echo '<div class="small-12 medium-12 large-12 columns">';
							echo '<a href="https://test.lorainccc.edu/mylccc/lccc_announcement" class="button">View All News</a>';
		     echo '</div>';
       break;
					case 'all-athletics' :
							$currentpostype = 'Announcements';
       echo '<div class="small-12 medium-12 large-12 columns">';
							echo '<a href="/athletics/lccc_announcement/" class="button">View All Athletic News</a>';
		     echo '</div>';
       break;
					
		}
					break;
					case 'Sub-page':
								foreach ( $posts as $post ){
			     echo '<div class="small-12 medium-12 large-12 columns sub-announcement-container">';
															echo '<div class="samll-12 medium-12 large-3 columns calendar-small">';
																			echo '<p class="month">'.$post->announcement_start_date_month.'</p>';
  																	echo '<p class="day">'.$post->announcement_start_date_day.'</p>';
															echo '</div>';
															echo '<div class="small-12 medium-12 large-9 columns">';?>
																			<a href="<?php echo $post->link; ?>"><?php echo $post->title->rendered; ?></a><?php
															echo '<p>' . $post->excerpt->rendered . '</p>' ;
												echo '</div>';
								echo '</div>';

     }
   switch ( $selectedfeedtype ){
    case 'all-announcements' :
							$currentpostype = 'Announcements';
       echo '<div class="small-12 medium-12 large-12 columns view-all-athletics-button">';
							echo '<a href="https://test.lorainccc.edu/mylccc/lccc_announcement" class="button">View All News</a>';
		     echo '</div>';
     break;
					case 'all-athletics' :
							$currentpostype = 'Announcements';
       echo '<div class="small-12 medium-12 large-12 columns view-all-athletics-button">';
							echo '<a href="/athletics/lccc_announcement/" class="button">View All Athletic News</a>';
		     echo '</div>';
     break;
					
		}
								echo '</div>';
					break;
			}


			
		echo '</div>';
  echo $after_widget;
	}
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
					$layout = esc_attr($instance['layout']);
					$numberofposts = esc_attr($instance['numberofposts']);
					$widgetcategory = esc_attr($instance['category']);
     $selectedfeedtype = esc_attr($instance['selectedfeedtype']);
} else {
					$layout = '';
					$numberofposts = '';
					$widgetcategory = '';
     $selectedfeedtype = '';
}
?>
<p>
<label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Announcement Layout', 'wp_widget_plugin'); ?></label>
<select name="<?php echo $this->get_field_name('layout'); ?>" id="<?php echo $this->get_field_id('layout'); ?>">
<?php
$options = array('select..','Home-page','Sub-page');
foreach ($options as $option) {
echo '<option value="' . $option . '" id="' . $option . '"', $layout == $option ? ' selected="selected"' : '', '>', $option, '</option>';
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
 <label for="<?php echo $this->get_field_id( 'selectedfeedtype' ); ?>"><?php _e( 'Select feed type', 'wp_widget_plugin' ); ?>:</label>
 <select name="<?php echo $this->get_field_name( 'selectedfeedtype' ); ?>" id="<?php echo $this->get_field_id( 'selectedfeedtype' ); ?>" class="widefat">
 <?php
   
  $feedtypeoptions = array('all-announcements', 'all-athletics');
  
  foreach ( $feedtypeoptions as $feedtype ) {
   //$feedtypeslug = trim(str_replace('&nbsp;&nbsp;-&nbsp;', '', $feedtype));
   //$feedtypeslug = strtolower(str_replace(' ', '-', $feedtypeslug));
   echo '<option value="' . $feedtype . '" id="' . $feedtype . '"', $selectedfeedtype == $feedtype ? ' selected="selected"' : '', '>', $feedtype, '</option>';
  }
  ?>
</select>
</p>
<p>
 <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Select category', 'wp_widget_plugin' ); ?>:</label>
    <?php wp_dropdown_categories( array( 'show_option_none' =>' ','name' => $this->get_field_name( 'category' ), 'selected' => $widgetcategory ) ); ?>
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
     		$instance['layout'] = strip_tags($new_instance['layout']);
							$instance['numberofposts'] = strip_tags($new_instance['numberofposts']);
     		$instance['selectedfeedtype'] = strip_tags($new_instance['selectedfeedtype']);
     		$instance['category'] = $new_instance['category'];
		return $instance;
	}
}
add_action( 'widgets_init', function(){
	register_widget( 'LCCC_Announcement_Feed_Widget' );
});

?>