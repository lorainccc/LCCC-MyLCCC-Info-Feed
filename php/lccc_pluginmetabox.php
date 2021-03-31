<?php


/**
 * Generated by the WordPress Meta Box generator
 * at http://jeremyhixon.com/tool/wordpress-meta-box-generator/
 */

/*
	Event Metabox
	*/
function event_meta_box_get_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

function event_meta_box_add_meta_box() {
	add_meta_box(
		'event_meta_box-event-meta-box',
		__( 'Event Meta Box', 'event_meta_box' ),
		'event_meta_box_html',
		array('lccc_events'),
		'advanced',
		'core'
	);
}
add_action( 'add_meta_boxes', 'event_meta_box_add_meta_box' );


function event_meta_box_html( $post) {
	wp_nonce_field( '_event_meta_box_nonce', 'event_meta_box_nonce' ); ?>

<script>
jQuery(document).ready(function(){
jQuery('#event_start_date').datepicker({
	dateFormat: "yy-mm-dd"
});
jQuery('#event_start_time').timepicker({
	timeFormat: "hh:mm tt"
});
jQuery('#event_end_date').datepicker({
	dateFormat: "yy-mm-dd"
});
jQuery('#event_end_time').timepicker({
	timeFormat: "hh:mm tt"
});

});
</script>
<?php 

if( event_meta_box_get_meta( 'event_meta_box_stocker_spektrix_event_id' ) != '' ){
?>
<h4>Spektrix Event Update</h4>

<p>
	<a href="/stocker/wp-admin/edit.php?post_type=lccc_events&page=lc-event-import&lc_event_update=<?php echo $post->ID . "|" . event_meta_box_get_meta( 'event_meta_box_stocker_spektrix_event_id' ); ?>" class="button button-primary button-large">Update Event from Spektrix</a>
	<span style="display:block; margin: 20px 0;"><i>Please note:</i> Clicking the above link will not update the Title or Description, just the start date and time, end date and time, price and location.</span>
</p>

<?php


	    //$sDomain = "https://system.spektrix.com/stockerartscenter_run1";
		$sDomain = "https://system.spektrix.com/stockerartscenter";
		$lc_event_import = event_meta_box_get_meta('event_meta_box_stocker_spektrix_event_id');
        $requestUrl =  $sDomain . "/api/v3/events/" . $lc_event_import . "?\$expand=instances";

		$response = wp_remote_get( $requestUrl );
        $json = json_decode( $response['body'] );        

		$instances = $json->instances;

		if( count($instances) > 1 ){
			$i = count($instances);
			do {
				if($i == 1){
					echo date_format(date_create($instances[$i-1]->start),"n/j/Y g:i A") . "" ;
				}else{
					echo date_format(date_create($instances[$i-1]->start),"n/j/Y g:i A") . " - Create Instance Specific Event" ;
				}
			} while ($i < count($instances));
		}
	}
?>
<h4>Sub Heading:</h4>
<p>
		<label for="event_meta_box_sub_heading"><?php _e( 'Stocker Event Sub Heading', 'event_meta_box' ); ?></label><br>
		<input class="widefat" type="text" name="event_meta_box_sub_heading" id="event_meta_box_sub_heading" value="<?php echo event_meta_box_get_meta( 'event_meta_box_sub_heading' ); ?>">
	</p>

<h4>Submitted by:</h4>
	<p>
		<label for="event_meta_box_name"><?php _e( 'Your Name', 'event_meta_box' ); ?></label><br>
		<input class="widefat" type="text" name="event_meta_box_name" id="event_meta_box_name" value="<?php echo event_meta_box_get_meta( 'event_meta_box_name' ); ?>">

	</p>
<p>
		<label for="event_meta_box_e_mail"><?php _e( 'E-Mail', 'event_meta_box' ); ?></label><br>
		<input class="widefat"  type="text" name="event_meta_box_e_mail" id="event_meta_box_e_mail" value="<?php echo event_meta_box_get_meta( 'event_meta_box_e_mail' ); ?>">

	</p>
<p>
		<label for="event_meta_box_phone"><?php _e( 'Phone', 'event_meta_box' ); ?></label><br>
		<input class="widefat"  type="text" name="event_meta_box_phone" id="event_meta_box_phone" value="<?php echo event_meta_box_get_meta( 'event_meta_box_phone' ); ?>">

	</p>
	<p>
		<label for="event_meta_box_department_organization_sponsor_"><?php _e( 'Sponsoring/Host Department or Organization:', 'event_meta_box' ); ?></label><br>
		<input class="widefat"  type="text" name="event_meta_box_department_organization_sponsor_" id="event_meta_box_department_organization_sponsor_" value="<?php echo event_meta_box_get_meta( 'event_meta_box_department_organization_sponsor_' ); ?>">
	</p>
<p>
<br>
	<p>
<h4 class="metabox-field-title">Audience:</h4>

	<p>
		<input type="checkbox" name="event_meta_box_employee" id="event_meta_box_employee" value="LCCC Employees" <?php echo ( event_meta_box_get_meta( 'event_meta_box_employee' ) === 'LCCC Employees' ) ? 'checked' : ''; ?>>
		<label for="event_meta_box_employee"><?php _e( 'LCCC Employees', 'lccc_event_metabox' ); ?></label>
</p>
<p>
		<input type="checkbox" name="event_meta_box_community" id="event_meta_box_community" value="Community and Perspective Students" <?php echo ( event_meta_box_get_meta( 'event_meta_box_community' ) === 'Community and Perspective Students' ) ? 'checked' : ''; ?>>
		<label for="event_meta_box_community"><?php _e( 'Community and Perspective Students', 'lccc_event_metabox' ); ?></label>
</p>
		<p>
		<input type="checkbox" name="event_meta_box_students" id="event_meta_box_students" value="LCCC Students" <?php echo ( event_meta_box_get_meta( 'event_meta_box_students' ) === 'LCCC Students' ) ? 'checked' : ''; ?>>
		<label for="event_meta_box_students"><?php _e( 'LCCC Students', 'lccc_event_metabox' ); ?></label>
</p>
<p>
		<label for="event_meta_box_event_location"><?php _e( 'Event Location', 'event_meta_box' ); ?></label><br>
		<input class="widefat"  type="text" name="event_meta_box_event_location" id="event_meta_box_event_location" value="<?php echo event_meta_box_get_meta( 'event_meta_box_event_location' ); ?>">

	</p>

<h4 class="metabox-field-title">Ticket Price(s):</h4>
<p>
		<input type="text" name="event_meta_box_ticket_price_s_" id="event_meta_box_ticket_price_s_" value="<?php echo event_meta_box_get_meta( 'event_meta_box_ticket_price_s_' ); ?>">
	</p>
<h4 class="metabox-field-title">Event Dates and Times:</h4>

	<p>
		<label for="event_start_date"><?php _e( 'Event Start date:', 'event_meta_box' ); ?></label><br>
		<input type="text" name="event_start_date" id="event_start_date" value="<?php echo event_meta_box_get_meta( 'event_start_date' ); ?>">
	</p>

	<p>
		<label for="event_start_time"><?php _e( 'Event Start time:', 'event_meta_box' ); ?></label><br>
		<input type="text" name="event_start_time" id="event_start_time" value="<?php echo event_meta_box_get_meta( 'event_start_time' ); ?>">
	</p>

	<p>
		<label for="event_end_date"><?php _e( 'Event End date:', 'event_meta_box' ); ?></label><br>
		<input type="text" name="event_end_date" id="event_end_date" value="<?php echo event_meta_box_get_meta( 'event_end_date' ); ?>">
	</p>

	<p>
		<label for="event_end_time"><?php _e( 'Event End time:', 'event_meta_box' ); ?></label><br>
		<input type="text" name="event_end_time" id="event_end_time" value="<?php echo event_meta_box_get_meta( 'event_end_time' ); ?>">
	</p>

	<p>
		<label for="event_meta_box_stoccker_bg_color"><?php _e( 'Stocker Backgound Color: ', 'event_meta_box' ); ?></label><br>
		<div style="margin-left: 15px; display:block;"> 
		<?php 

			$colors=array
			(
				"Purple" 	=> "#67296e",
				"Orange"	=> "#e36000",
				"Green" 	=> "#6db400",
				"Blue" 		=> "#1583cc",
			);

			$selectedBgColor = event_meta_box_get_meta( 'event_meta_box_stoccker_bg_color' );

			foreach($colors as $color => $colorValue){
				?>
				<div style="margin: 5px 0; display:block;">
					<div style="display:inline-block; width: 85px;"><input name="event_meta_box_stoccker_bg_color" type="radio" id="event_meta_box_stoccker_bg_color" value="<?php echo $colorValue; ?>" <?php echo ($selectedBgColor== $colorValue) ?  "checked" : "" ;  ?>/> <?php echo $color; ?></div>
					<div style="min-width: 20px; min-height: 20px; display: inline-block; background-color: <?php echo $colorValue; ?>;">&nbsp;</div>
				</div>
			<?php
			}
		?>
		</div>
	</p>
<p>
		<label for="event_meta_box_stocker_ticket_link"><?php _e( 'Stocker Buy Tickets Link', 'event_meta_box' ); ?></label><br>
		<input class="widefat" type="text" name="event_meta_box_stocker_ticket_link" id="event_meta_box_stocker_ticket_link" value="<?php echo event_meta_box_get_meta( 'event_meta_box_stocker_ticket_link' ); ?>">
	</p>
<h4 class="metabox-field-title">Who should your audience contact for more information?</h4>
<p>
			<label for="lccc_event_contact_name"><?php _e( 'Name:', 'event_meta_box' ); ?></label><br>
			<input class="widefat"  type="text" name="lccc_event_contact_name" id="lccc_event_contact_name" value="<?php echo event_meta_box_get_meta( 'lccc_event_contact_name' ); ?>">
		<label for="event_meta_box_contact_phone_"><?php _e( 'Phone:', 'event_meta_box' ); ?></label><br>
		<input class="widefat"  type="text" name="event_meta_box_contact_phone_" id="event_meta_box_contact_phone_" value="<?php echo event_meta_box_get_meta( 'event_meta_box_contact_phone_' ); ?>">
		<label for="event_meta_box_contact_email"><?php _e( 'Email:', 'event_meta_box' ); ?></label><br>
		<input class="widefat"  type="text" name="event_meta_box_contact_email" id="event_meta_box_contact_email" value="<?php echo event_meta_box_get_meta( 'event_meta_box_contact_email' ); ?>">

	<label for="event_meta_box_associated_web_address_"><?php _e( 'Website:', 'event_meta_box' ); ?></label><br>
		<input class="widefat"  type="text" name="event_meta_box_associated_web_address_" id="event_meta_box_associated_web_address_" value="<?php echo event_meta_box_get_meta( 'event_meta_box_associated_web_address_' ); ?>">
	</p>

<?php
}

function event_meta_box_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['event_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['event_meta_box_nonce'], '_event_meta_box_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['event_meta_box_name'] ) )
		update_post_meta( $post_id, 'event_meta_box_name', esc_attr( $_POST['event_meta_box_name'] ) );
	if ( isset( $_POST['event_meta_box_stoccker_bg_color'] ) )
		update_post_meta( $post_id, 'event_meta_box_stoccker_bg_color', esc_attr( $_POST['event_meta_box_stoccker_bg_color'] ) );
if ( isset( $_POST['event_meta_box_stocker_ticket_link'] ) )
		update_post_meta( $post_id, 'event_meta_box_stocker_ticket_link', esc_attr( $_POST['event_meta_box_stocker_ticket_link'] ) );
	if ( isset( $_POST['event_meta_box_sub_heading'] ) )
		update_post_meta( $post_id, 'event_meta_box_sub_heading', esc_attr( $_POST['event_meta_box_sub_heading'] ) );

	if ( isset( $_POST['event_meta_box_phone'] ) )
		update_post_meta( $post_id, 'event_meta_box_phone', esc_attr( $_POST['event_meta_box_phone'] ) );
	if ( isset( $_POST['event_meta_box_e_mail'] ) )
		update_post_meta( $post_id, 'event_meta_box_e_mail', esc_attr( $_POST['event_meta_box_e_mail'] ) );


	if ( isset( $_POST['event_meta_box_employee'] ) )
		update_post_meta( $post_id, 'event_meta_box_employee', esc_attr( $_POST['event_meta_box_employee'] ) );
	else
		update_post_meta( $post_id, 'event_meta_box_employee', null );
	if ( isset( $_POST['event_meta_box_community'] ) )
		update_post_meta( $post_id, 'event_meta_box_community', esc_attr( $_POST['event_meta_box_community'] ) );
	else
		update_post_meta( $post_id, 'event_meta_box_community', null );

	if ( isset( $_POST['event_meta_box_students'] ) )
		update_post_meta( $post_id, 'event_meta_box_students', esc_attr( $_POST['event_meta_box_students'] ) );
	else
		update_post_meta( $post_id, 'event_meta_box_students', null );

	if ( isset( $_POST['event_meta_box_event_location'] ) )
		update_post_meta( $post_id, 'event_meta_box_event_location', esc_attr( $_POST['event_meta_box_event_location'] ) );

	if ( isset( $_POST['event_start_date'] ) )
   update_post_meta( $post_id, 'event_start_date', esc_attr( $_POST['event_start_date'] ) );

	if ( isset( $_POST['event_start_date'] ) )
   update_post_meta( $post_id, 'start_date', esc_attr( $_POST['event_start_date'] ) );

 if ( isset( $_POST['event_start_time'] ) )
   update_post_meta( $post_id, 'event_start_time', esc_attr( $_POST['event_start_time'] ) );

 if ( isset( $_POST['event_start_date'] ) )
  $time = strtotime($_POST['event_start_time']);
  $lc_event_start_date_time = $_POST['event_start_date'] . ' ' . date('H:i', $time);
   update_post_meta( $post_id, 'event_start_date_time', esc_attr( $lc_event_start_date_time ) );
 
	if ( isset( $_POST['event_end_date'] ) )
   update_post_meta( $post_id, 'event_end_date', esc_attr( $_POST['event_end_date'] ) );

	if ( isset( $_POST['event_end_time'] ) )
   update_post_meta( $post_id, 'event_end_time', esc_attr( $_POST['event_end_time'] ) );
	if ( isset( $_POST['event_meta_box_event_end_date_and_time_'] ) )

		update_post_meta( $post_id, 'event_meta_box_event_end_date_and_time_', esc_attr( $_POST['event_meta_box_event_end_date_and_time_'] ) );

	if ( isset( $_POST['event_meta_box_ticket_price_s_'] ) )
		update_post_meta( $post_id, 'event_meta_box_ticket_price_s_', esc_attr( $_POST['event_meta_box_ticket_price_s_'] ) );
	if ( isset( $_POST['event_meta_box_department_organization_sponsor_'] ) )
		update_post_meta( $post_id, 'event_meta_box_department_organization_sponsor_', esc_attr( $_POST['event_meta_box_department_organization_sponsor_'] ) );


	if ( isset( $_POST['lccc_event_contact_name'] ) )
		update_post_meta( $post_id, 'lccc_event_contact_name', esc_attr( $_POST['lccc_event_contact_name'] ) );

	if ( isset( $_POST['event_meta_box_contact_phone_'] ) )
		update_post_meta( $post_id, 'event_meta_box_contact_phone_', esc_attr( $_POST['event_meta_box_contact_phone_'] ) );

	if ( isset( $_POST['event_meta_box_contact_email'] ) )
		update_post_meta( $post_id, 'event_meta_box_contact_email', esc_attr( $_POST['event_meta_box_contact_email'] ) );

	if ( isset( $_POST['event_meta_box_associated_web_address_'] ) )
		update_post_meta( $post_id, 'event_meta_box_associated_web_address_', esc_attr( $_POST['event_meta_box_associated_web_address_'] ) );

	if ( isset( $_POST['event_meta_box_display_start_date_and_time'] ) )
		update_post_meta( $post_id, 'event_meta_box_display_start_date_and_time', esc_attr( $_POST['event_meta_box_display_start_date_and_time'] ) );
	if ( isset( $_POST['event_meta_box_display_end_date_and_time'] ) )
		update_post_meta( $post_id, 'event_meta_box_display_end_date_and_time', esc_attr( $_POST['event_meta_box_display_end_date_and_time'] ) );
}
add_action( 'save_post', 'event_meta_box_save' );
/*
	Announcement Metabox
	*/

function announcement_meta_box_get_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

function announcement_meta_box_add_meta_box() {
	add_meta_box(
		'announcement_meta_box-announcement-meta-box',
		__( 'announcement Meta Box', 'announcement_meta_box' ),
		'announcement_meta_box_html',
		array('lccc_announcement'),
		'advanced',
		'core'
	);
}
add_action( 'add_meta_boxes', 'announcement_meta_box_add_meta_box' );


function announcement_meta_box_html( $post) {
	wp_nonce_field( '_announcement_meta_box_nonce', 'announcement_meta_box_nonce' ); ?>

<script>
jQuery(document).ready(function(){
jQuery('#announcement_start_date').datepicker({
	dateFormat: "mm/dd/yy"
});
jQuery('#announcement_start_time').timepicker({
	timeFormat: "hh:mm tt"
});
jQuery('#announcement_end_date').datepicker({
	dateFormat: "mm/dd/yy"
});
jQuery('#announcement_end_time').timepicker({
	timeFormat: "hh:mm tt"
});

});
</script>

<p>
		<label for="announcement_meta_box_altlink"><?php _e( 'Alternate Destination', 'announcement_meta_box' ); ?></label><br>
		<input class="widefat" type="text" name="announcement_meta_box_altlink" id="announcement_meta_box_altlink" value="<?php echo announcement_meta_box_get_meta( 'announcement_meta_box_altlink' ); ?>">
</p>

<p>
		<label for="announcement_meta_box_sub_heading"><?php _e( 'Sub Heading:', 'announcement_meta_box' ); ?></label><br>
		<input class="widefat"  type="text" name="announcement_meta_box_sub_heading" id="announcement_meta_box_sub_heading" value="<?php echo announcement_meta_box_get_meta( 'announcement_meta_box_sub_heading' ); ?>">
</p>

<p>
		<label for="announcement_meta_box_learn_more_text"><?php _e( 'Learn More Text:', 'announcement_meta_box' ); ?></label><br>
		<input class="widefat"  type="text" name="announcement_meta_box_learn_more_text" id="announcement_meta_box_learn_more_text" value="<?php echo announcement_meta_box_get_meta( 'announcement_meta_box_learn_more_text' ); ?>">
</p>


<h4>Submitted by:</h4>
	<p>
		<label for="announcement_meta_box_name"><?php _e( 'Your Name', 'announcement_meta_box' ); ?></label><br>
		<input class="widefat" type="text" name="announcement_meta_box_name" id="announcement_meta_box_name" value="<?php echo announcement_meta_box_get_meta( 'announcement_meta_box_name' ); ?>">

	</p>
<p>
		<label for="announcement_meta_box_e_mail"><?php _e( 'E-Mail', 'announcement_meta_box' ); ?></label><br>
		<input class="widefat"  type="text" name="announcement_meta_box_e_mail" id="announcement_meta_box_e_mail" value="<?php echo announcement_meta_box_get_meta( 'announcement_meta_box_e_mail' ); ?>">

	</p>
<p>
		<label for="announcement_meta_box_phone"><?php _e( 'Phone', 'announcement_meta_box' ); ?></label><br>
		<input class="widefat"  type="text" name="announcement_meta_box_phone" id="announcement_meta_box_phone" value="<?php echo announcement_meta_box_get_meta( 'announcement_meta_box_phone' ); ?>">

	</p>
	<p>
		<label for="announcement_meta_box_department_organization_sponsor_"><?php _e( 'Sponsoring/Host Department or Organization:', 'announcement_meta_box' ); ?></label><br>
		<input class="widefat"  type="text" name="announcement_meta_box_department_organization_sponsor_" id="announcement_meta_box_department_organization_sponsor_" value="<?php echo announcement_meta_box_get_meta( 'announcement_meta_box_department_organization_sponsor_' ); ?>">
	</p>
<p>
<br>
</p>

<h4 class="metabox-field-title">Audience:</h4>
	<p>
		<input type="checkbox" name="announcement_meta_box_employee" id="announcement_meta_box_employee" value="LCCC Employees" <?php echo ( announcement_meta_box_get_meta( 'announcement_meta_box_employee' ) === 'LCCC Employees' ) ? 'checked' : ''; ?>>
		<label for="announcement_meta_box_employee"><?php _e( 'LCCC Employees', 'lccc_announcement_metabox' ); ?></label>
</p>
<p>
		<input type="checkbox" name="announcement_meta_box_community" id="announcement_meta_box_community" value="Community and Perspective Students" <?php echo ( announcement_meta_box_get_meta( 'announcement_meta_box_community' ) === 'Community and Perspective Students' ) ? 'checked' : ''; ?>>
		<label for="announcement_meta_box_community"><?php _e( 'Community and Perspective Students', 'lccc_announcement_metabox' ); ?></label>
</p>
		<p>
		<input type="checkbox" name="announcement_meta_box_students" id="announcement_meta_box_students" value="LCCC Students" <?php echo ( announcement_meta_box_get_meta( 'announcement_meta_box_students' ) === 'LCCC Students' ) ? 'checked' : ''; ?>>
		<label for="announcement_meta_box_students"><?php _e( 'LCCC Students', 'lccc_announcement_metabox' ); ?></label>
</p>

<h4 class="metabox-field-title">announcement Location</h4><br>
<select name='announcement_meta_box_announcement_location' id='announcement_meta_box_announcement_location'>
 		<?php
			$mypages = get_pages('post_type=lccc_location');
			foreach($mypages as $page)
			{
				?><option><?php echo $page->post_title;?></option><?php
			}
			?>
</select>

<h4 class="metabox-field-title">Announcement Dates and Times:</h4>

<p>
		<label for="announcement_start_date"><?php _e( 'announcement Start date:', 'announcement_meta_box' ); ?></label><br>
		<input type="text" name="announcement_start_date" id="announcement_start_date" value="<?php echo announcement_meta_box_get_meta( 'announcement_start_date' ); ?>">
	</p>

<p>
		<label for="announcement_start_time"><?php _e( 'announcement Start time:', 'announcement_meta_box' ); ?></label><br>
		<input type="text" name="announcement_start_time" id="announcement_start_time" value="<?php echo announcement_meta_box_get_meta( 'announcement_start_time' ); ?>">
	</p>

<p>
		<label for="announcement_end_date"><?php _e( 'announcement End date:', 'announcement_meta_box' ); ?></label><br>
		<input type="text" name="announcement_end_date" id="announcement_end_date" value="<?php echo announcement_meta_box_get_meta( 'announcement_end_date' ); ?>">
	</p>

<p>
		<label for="announcement_end_time"><?php _e( 'announcement End time:', 'announcement_meta_box' ); ?></label><br>
		<input type="text" name="announcement_end_time" id="announcement_end_time" value="<?php echo announcement_meta_box_get_meta( 'announcement_end_time' ); ?>">
	</p>

<h4 class="metabox-field-title">Who should your audience contact for more information?</h4>
<p>
			<label for="lccc_announcement_contact_name"><?php _e( 'Name:', 'announcement_meta_box' ); ?></label><br>
			<input class="widefat"  type="text" name="lccc_announcement_contact_name" id="lccc_announcement_contact_name" value="<?php echo announcement_meta_box_get_meta( 'lccc_announcement_contact_name' ); ?>">
		<label for="announcement_meta_box_contact_phone_"><?php _e( 'Phone:', 'announcement_meta_box' ); ?></label><br>
		<input class="widefat"  type="text" name="announcement_meta_box_contact_phone_" id="announcement_meta_box_contact_phone_" value="<?php echo announcement_meta_box_get_meta( 'announcement_meta_box_contact_phone_' ); ?>">
		<label for="announcement_meta_box_contact_email"><?php _e( 'Email:', 'announcement_meta_box' ); ?></label><br>
		<input class="widefat"  type="text" name="announcement_meta_box_contact_email" id="announcement_meta_box_contact_email" value="<?php echo announcement_meta_box_get_meta( 'announcement_meta_box_contact_email' ); ?>">

	<label for="announcement_meta_box_associated_web_address_"><?php _e( 'Website:', 'announcement_meta_box' ); ?></label><br>
		<input class="widefat"  type="text" name="announcement_meta_box_associated_web_address_" id="announcement_meta_box_associated_web_address_" value="<?php echo announcement_meta_box_get_meta( 'announcement_meta_box_associated_web_address_' ); ?>">
	</p>

<?php
}

function announcement_meta_box_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['announcement_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['announcement_meta_box_nonce'], '_announcement_meta_box_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['announcement_meta_box_sub_heading'] ) )
		update_post_meta( $post_id, 'announcement_meta_box_sub_heading', esc_attr( $_POST['announcement_meta_box_sub_heading'] ) );

 	if ( isset( $_POST['announcement_meta_box_altlink'] ) )
		update_post_meta( $post_id, 'announcement_meta_box_altlink', esc_attr( $_POST['announcement_meta_box_altlink'] ) );

	if ( isset( $_POST['announcement_meta_box_learn_more_text'] ) )
	update_post_meta( $post_id, 'announcement_meta_box_learn_more_text', esc_attr( $_POST['announcement_meta_box_learn_more_text'] ) );

	if ( isset( $_POST['announcement_meta_box_name'] ) )
		update_post_meta( $post_id, 'announcement_meta_box_name', esc_attr( $_POST['announcement_meta_box_name'] ) );
	
	if ( isset( $_POST['announcement_meta_box_phone'] ) )
		update_post_meta( $post_id, 'announcement_meta_box_phone', esc_attr( $_POST['announcement_meta_box_phone'] ) );
	if ( isset( $_POST['announcement_meta_box_e_mail'] ) )
		update_post_meta( $post_id, 'announcement_meta_box_e_mail', esc_attr( $_POST['announcement_meta_box_e_mail'] ) );


	if ( isset( $_POST['announcement_meta_box_employee'] ) )
		update_post_meta( $post_id, 'announcement_meta_box_employee', esc_attr( $_POST['announcement_meta_box_employee'] ) );
	else
		update_post_meta( $post_id, 'announcement_meta_box_employee', null );
	if ( isset( $_POST['announcement_meta_box_community'] ) )
		update_post_meta( $post_id, 'announcement_meta_box_community', esc_attr( $_POST['announcement_meta_box_community'] ) );
	else
		update_post_meta( $post_id, 'announcement_meta_box_community', null );

	if ( isset( $_POST['announcement_meta_box_students'] ) )
		update_post_meta( $post_id, 'announcement_meta_box_students', esc_attr( $_POST['announcement_meta_box_students'] ) );
	else
		update_post_meta( $post_id, 'announcement_meta_box_students', null );

	if ( isset( $_POST['announcement_meta_box_announcement_location'] ) )
		update_post_meta( $post_id, 'announcement_meta_box_announcement_location', esc_attr( $_POST['announcement_meta_box_announcement_location'] ) );

	if ( isset( $_POST['announcement_start_date'] ) )
   update_post_meta( $post_id, 'announcement_start_date', esc_attr( $_POST['announcement_start_date'] ) );

if ( isset( $_POST['announcement_start_time'] ) )
   update_post_meta( $post_id, 'announcement_start_time', esc_attr( $_POST['announcement_start_time'] ) );

	if ( isset( $_POST['announcement_end_date'] ) )
   update_post_meta( $post_id, 'announcement_end_date', esc_attr( $_POST['announcement_end_date'] ) );

	if ( isset( $_POST['announcement_end_time'] ) )
   update_post_meta( $post_id, 'announcement_end_time', esc_attr( $_POST['announcement_end_time'] ) );

	if ( isset( $_POST['announcement_meta_box_announcement_end_date_and_time_'] ) )
		update_post_meta( $post_id, 'announcement_meta_box_announcement_end_date_and_time_', esc_attr( $_POST['announcement_meta_box_announcement_end_date_and_time_'] ) );

	if ( isset( $_POST['announcement_meta_box_ticket_price_s_'] ) )
		update_post_meta( $post_id, 'announcement_meta_box_ticket_price_s_', esc_attr( $_POST['announcement_meta_box_ticket_price_s_'] ) );
	if ( isset( $_POST['announcement_meta_box_department_organization_sponsor_'] ) )
		update_post_meta( $post_id, 'announcement_meta_box_department_organization_sponsor_', esc_attr( $_POST['announcement_meta_box_department_organization_sponsor_'] ) );


	if ( isset( $_POST['lccc_announcement_contact_name'] ) )
		update_post_meta( $post_id, 'lccc_announcement_contact_name', esc_attr( $_POST['lccc_announcement_contact_name'] ) );

	if ( isset( $_POST['announcement_meta_box_contact_phone_'] ) )
		update_post_meta( $post_id, 'announcement_meta_box_contact_phone_', esc_attr( $_POST['announcement_meta_box_contact_phone_'] ) );

	if ( isset( $_POST['announcement_meta_box_contact_email'] ) )
		update_post_meta( $post_id, 'announcement_meta_box_contact_email', esc_attr( $_POST['announcement_meta_box_contact_email'] ) );

	if ( isset( $_POST['announcement_meta_box_associated_web_address_'] ) )
		update_post_meta( $post_id, 'announcement_meta_box_associated_web_address_', esc_attr( $_POST['announcement_meta_box_associated_web_address_'] ) );

	if ( isset( $_POST['announcement_meta_box_display_start_date_and_time'] ) )
		update_post_meta( $post_id, 'announcement_meta_box_display_start_date_and_time', esc_attr( $_POST['announcement_meta_box_display_start_date_and_time'] ) );
	if ( isset( $_POST['announcement_meta_box_display_end_date_and_time'] ) )
		update_post_meta( $post_id, 'announcement_meta_box_display_end_date_and_time', esc_attr( $_POST['announcement_meta_box_display_end_date_and_time'] ) );
}
add_action( 'save_post', 'announcement_meta_box_save' );



/*
	Usage: event_meta_box_get_meta( 'event_meta_box_name' )
	Usage: event_meta_box_get_meta( 'event_meta_box_phone' )
	Usage: event_meta_box_get_meta( 'event_meta_box_e_mail' )
	Usage: event_meta_box_get_meta( 'event_meta_box_employee' )
	Usage: event_meta_box_get_meta( 'event_meta_box_community' )
	Usage: event_meta_box_get_meta( 'event_meta_box_students' )
	Usage: event_meta_box_get_meta( 'event_meta_box_event_location' )
	Usage: event_meta_box_get_meta( 'event_meta_box_event_start_date_and_time_' )
	Usage: event_meta_box_get_meta( 'event_meta_box_event_end_date_and_time_' )
	Usage: event_meta_box_get_meta( 'event_meta_box_ticket_price_s_' )
	Usage: event_meta_box_get_meta( 'event_meta_box_department_organization_sponsor_' )
	Usage: event_meta_box_get_meta( 'event_meta_box_associated_web_address_' )
	Usage: event_meta_box_get_meta( 'event_meta_box_display_start_date_and_time' )
	Usage: event_meta_box_get_meta( 'event_meta_box_display_end_date_and_time' )
*/

?>