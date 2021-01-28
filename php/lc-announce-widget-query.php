<?php

/** Widget Code */

class LCCC_Announcement_Query_Widget extends WP_Widget {

 /**
  * Sets up the widgets name etc
  */
 	public function __construct() {
		$widget_ops = array(
			'classname' 		=> 'LCCC_Announcement_Query_Widget',
			'description' =>	'LCCC Query widget for displaying LCCC Announcments from other LCCC web sites.',
		);
		parent::__construct( 'LCCC_Announcement_Query_Widget', 'LCCC Announcement Query Widget', $widget_ops );
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
         if ($whattodisplay == 'lccc_announcement'){
			switch($displaylayout){
				case 'Home-page':
			echo '<div id="lc-announcement-feed" class="small-12 medium-12 large-12 columns '.$whattodisplay.'_header">';
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
        //$widgetcategory = get_cat_slug($widgetcategory);

if ($whattodisplay == 'lccc_announcement'){
    $posts = lc_get_all_announcements();

    if( $posts->have_posts() ){
        while ( $posts->have_posts() ) : $posts->the_post();
        echo '<div id="lc_news_' . $post->slug . '" class="small-12 medium-12 large-12 columns news-container">';
        echo '  <div class="small-12 medium-3 large-3 columns eventhumbnail">';
        the_post_thumbnail();
        echo '  </div>';
        echo '	<div class="small-12 medium-9 large-9 columns">';

        $lc_link = get_the_permalink();
        $lc_link = str_replace("/blog/", "/", $lc_link);

        ?>
        <a href="<?php echo $lc_link; ?>">
        <?php
        the_title( '<h3 class="entry-title">', '</h3>' );
        echo '  </a>';
        the_content();
        echo'	</div>';
        echo '</div>';
        echo '<div class="column row">';
              echo '<hr />';
        echo '</div>';
        endwhile;
        }
    echo '</div>';
    echo $after_widget;
      }
    }

/*

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

  $feedtypeoptions = array('all-announcements', 'all-athletics', 'homepage');

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
 <select name="<?php echo $this->get_field_name('category');?>" id="<?php echo $this->get_field_id('category'); ?>">
     <?php //wp_dropdown_categories( array( 'show_option_none' =>' ','name' => $this->get_field_name( 'category' ), 'selected' => $widgetcategory ) );
  $lcannounce_cats = array('select...', 'Athletics News');
  foreach ($lcannounce_cats as $category ){
   $catslug = str_replace(' ', '-', $category);
   $catslug = strtolower($catslug);
   echo '<option value="' . $catslug .'" id="' . $catslug . '"', $widgetcategory == $catslug ? 'selected="selected"' : '', '>', $category, '</option>' ;
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
     		$instance['layout'] = strip_tags($new_instance['layout']);
							$instance['numberofposts'] = strip_tags($new_instance['numberofposts']);
     		$instance['selectedfeedtype'] = strip_tags($new_instance['selectedfeedtype']);
     		$instance['category'] = $new_instance['category'];
		return $instance;
	}
}
add_action( 'widgets_init', function(){
	register_widget( 'LCCC_Announcement_Query_Widget' );
});

?>