<?php

$domain = 'https://' . $_SERVER['SERVER_NAME'];

function lc_get_all_announcements(){

  global $domain;

    $all_announce_transient = get_transient( 'LCCC_HomePage_Announcements' );
   
    if( ! empty( $all_announce_transient ) ){
     
     return $all_announce_transient;
     
    } else {
   
      $response = wp_remote_get( $domain . '/mylccc/wp-json/wp/v2/lccc_announcement?filter[taxonomy]=category&filter[term]=lccc-home-page', array(
        'sslverify' => false,) );
          if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {

            $posts = json_decode( wp_remote_retrieve_body( $response ) );

            $posts = lc_sort( $posts );

            // Set cache to 24 hours
            set_transient( 'LCCC_HomePage_Announcements' , $posts, 86400);

            return json_decode( wp_remote_retrieve_body( $response ) );

          }    
    }
}

function lc_get_lccc_events(){  

  global $domain;

  $lccc_events_transient = get_transient( 'LCCC_Events' );
  
  if ( ! empty( $lccc_events_transient ) ){

    return $lccc_events_transient;
  } else {

    $response = wp_remote_get( $domain . '/mylccc/wp-json/wp/v2/lccc_events?per_page=100' );

        if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
         
          $lccc_posts = json_decode( wp_remote_retrieve_body( $response ) );

          // Set cache to 6 hours
          set_transient( 'LCCC_Events' , $lccc_posts, 18000);

          return $lccc_posts;

        }

      }
}

function lc_get_stocker_events(){
 
  global $domain;

  $lc_stocker_events_transient = get_transient( 'LCCC_Stocker_Events' );

    if ( ! empty( $lc_stocker_events_transient ) ){
      return $lc_stocker_events_transient;
    } else {

      $response = wp_remote_get( $domain . '/stocker/wp-json/wp/v2/lccc_events?per_page=100' );

        if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {

          $stocker_posts = json_decode( wp_remote_retrieve_body( $response ) );

          // Set cache to 6 hours
          set_transient( 'LCCC_Stocker_Events' , $stocker_posts, 18000);

          return $stocker_posts;

        }
      }
}

function lc_get_all_events(){

 $all_events_transient = get_transient( 'LCCC_All_Events' );
     
    if( ! empty( $all_events_transient ) ){
     
     return $all_events_transient;
     
    } else {

        $lccc_events = lc_get_lccc_events();
        $lc_stocker_events = lc_get_stocker_events();

        $posts = array_merge( $lccc_events, $lc_stocker_events );

        $posts = lc_sort( $posts );


        // Set cache to 6 hours
        set_transient( 'LCCC_All_Events' , $posts, 43200);

        return $posts;
      }  

    }

	/**
	 * Sort posts by date
	 *
	 * @param array $data
	 *
	 * @return array
	 */

	function lc_sort( array $data ) {
    usort( $data, function ( $a, $b ) {
     if($a->event_start_date != ''){
       return strtotime( $a->event_start_date ) - strtotime( $b->event_start_date );
      }else{
       return strtotime( $a->date ) - strtotime( $b->date );
     }
    } );
  
      //$data = array_reverse( $data );
      return $data;
    }