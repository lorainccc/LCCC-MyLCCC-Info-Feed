<?php
/** Widget Code */
class LCCC_Whats_Going_On_Announcement_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'LCCC_Whats_Going_On_Announcement_Widget',
			'description' => 'LCCC Whats Going On Announcement Widget fpr displaying LCCC Announcements on LCCC sites',
		);
		parent::__construct( 'LCCC_Whats_Going_On_Announcement_Widget', 'LCCC Whats Going On Announcement Widget', $widget_ops );
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
			$widgetcategory = $instance['category'];
   echo $before_widget;
   // Display the widget
		 echo '<div class="small-12 medium-12 large-12 columns '.$whattodisplay.'">';
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
   echo '<div class="small-12 medium-12 large-12 columns '.$whattodisplay.'_header">';
						echo '<h2 class="announcementheader">'.'In The News'.'</h2>';				
			echo '</div>';
			}
	  	$today = getdate();
				$widgetcategory = get_cat_slug($widgetcategory);
		
		if ($whattodisplay == 'lccc_announcement'){
					$announcementargs=array(
					'post_type' => 'lccc_announcement',
					'post_status' => 'publish',
					'taxonomy'	=> 'category',
					'term'	=> $widgetcategory,
					//'cat' => $widgetcategory,
					'orderby' => 'date',
					'order' => 'DESC',
					);
					$newevents = new WP_Query($announcementargs);
					if ( $newevents->have_posts() ) :
									while ( $newevents->have_posts() ) : $newevents->the_post();
			echo '<div class="small-12 medium-12 large-12 columns news-container">';
								echo '<div class="small-12 medium-3 large-3 columns eventhumbnail">';
												the_post_thumbnail();
								echo '</div>';
								echo '<div class="small-12 medium-9 large-9 columns">';
  						?>
								<a href="<?php the_permalink();?>"><?php the_title('<h3 class="eventtitle">','</h3>');?></a>
								<?php
											the_excerpt('<p>','</p>');
								echo '</div>';
			  			echo '<div class="column row">';
    								echo '<hr />';
  						echo '</div>';
								echo '</div>';
							endwhile;
					endif;
		}
		if ($whattodisplay == 'lccc_announcement'){
					$currentpostype = 'Announcements';
			echo '<div class="small-12 medium-12 large-12 columns">';
							echo '<a href="'.get_post_type_archive_link( $whattodisplay ).'" class="button">View All News</a>';
		echo '</div>';
		}
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
					$numberofposts = esc_attr($instance['numberofposts']);
					$widgetcategory = esc_attr($instance['category']);
} else {
					$numberofposts = '';
					$widgetcategory = '';
}
?>

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
     		$instance['numberofposts'] = strip_tags($new_instance['numberofposts']);
		 $instance['category'] = $new_instance['category'];
		return $instance;
	}
}
add_action( 'widgets_init', function(){
	register_widget( 'LCCC_Whats_Going_On_Announcement_Widget' );
});
?>
