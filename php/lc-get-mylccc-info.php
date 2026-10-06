<?php

$domain = 'https://' . $_SERVER['SERVER_NAME'];

function lc_get_all_announcements(){

  global $domain;

    $all_announce_transient = get_transient( 'LCCC_HomePage_Announcements' );
   
    if( ! empty( $all_announce_transient ) ){
     
     return $all_announce_transient;
     
    } else {
   
      $response = wp_remote_get( $domain . '/mylccc/wp-json/wp/v2/lccc_announcement?filter[taxonomy]=category&filter[term]=lccc-home-page' );
          if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {

            $posts = json_decode( wp_remote_retrieve_body( $response ), true );

            $posts = lc_sort( $posts, 'announcement' );

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

          $lccc_posts = json_decode( wp_remote_retrieve_body( $response ), true );
          $lccc_posts = lc_sort( $lccc_posts, 'event'  );

          // Set cache to 6 hours
          set_transient( 'LCCC_Events' , $lccc_posts, 36000);

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

          $stocker_posts = json_decode( wp_remote_retrieve_body( $response ), true );
          $stocker_posts = lc_sort( $stocker_posts, 'event'  );

          // Set cache to 6 hours
          set_transient( 'LCCC_Stocker_Events' , $stocker_posts, 36000);

          return $stocker_posts;

        }
      }
}

function lc_get_athletics_events(){
 
  global $domain;

  $lc_athletics_events_transient = get_transient( 'LCCC_Athletics_Events' );

    if ( ! empty( $lc_athletics_events_transient ) ){
      return $lc_athletics_events_transient;
    } else {

      $response = wp_remote_get( $domain . '/athletics/wp-json/wp/v2/lccc_events?per_page=100' );

        if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {

          $athletics_posts = json_decode( wp_remote_retrieve_body( $response ), true );

          $athletics_posts = lc_sort( $athletics_posts, 'event'  );

          // Set cache to 6 hours
          set_transient( 'LCCC_Athletics_Events' , $athletics_posts, 36000);

          return $athletics_posts;
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
        //$lc_athletics_events = lc_get_athletics_events();

        $posts = array_merge( $lccc_events, $lc_stocker_events );

        $posts = lc_sort( $posts, 'event' );

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

	/* function lc_sort( array $data ) {
    usort( $data, function ( $a, $b ) {
     if($a->event_start_date_time != ''){
      return strtotime( $a->event_start_date_time ) - strtotime( $b->event_start_date_time );
     }else if($a->event_start_date != ''){
       return strtotime( $a->event_start_date ) - strtotime( $b->event_start_date );
      }else{
       return strtotime( $a->date ) - strtotime( $b->date );
     }
    } );
   
      //$data = array_reverse( $data );
      return $data;
    } */
function lc_sort( array $data, string $type ) {
  /* $data_sort = array();

  foreach($data as $k=>$v){
    $data_sort['event_start_date_time'][$k] = $v['event_start_date_time'];
    $data_sort['title->rendered'][$k] = $v['title->rendered'];
  }

  array_multisort($data_sort['event_start_date_time'], SORT_DESC, $sort['title->rendered'], SORT_ASC, $data); */

switch($type){
  case 'event':
 
    foreach ($data as $key => $row ){      
      $event_start_date_and_time[$key] = $row['event_start_date_and_time'];
      $title[$key] = $row['title']['rendered'];
    }


    //$event_start_date_and_time = array_column($data, 'event_start_date_and_time');
    //$title = array_column($data, 'title->rendered');
  
    if(is_array($event_start_date_and_time)){
      array_multisort($event_start_date_and_time, SORT_ASC, $title, SORT_ASC, $data);
      return $data;
    }else{
      return null;
    }
    
  break;

  case 'announcement':
    foreach ($data as $key => $row ){      
      $date[$key] = $row->date;
      $title[$key] = $row->title->rendered;
    }

    array_multisort($date, SORT_ASC, $title, SORT_ASC, $data);
    return $data;
  break;
} 
  
}

/**
 * By default, cURL sends the "Expect" header all the time which severely impacts
 * performance. Instead, we'll send it if the body is larger than 1 mb like
 * Guzzle does.
 */
function lc_add_expect_header(array $arguments)
{
    $arguments['headers']['expect'] = '';
    
    if (is_array($arguments['body'])) {
        $bytesize = 0;
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($arguments['body']));

        foreach ($iterator as $datum) {
            $bytesize += strlen((string) $datum);

            if ($bytesize >= 1048576) {
                $arguments['headers']['expect'] = '100-Continue';
                break;
            }
        }
    } elseif (!empty($arguments['body']) && strlen((string) $arguments['body']) > 1048576) {
        $arguments['headers']['expect'] = '100-Continue';
    }

    return $arguments;
}
add_filter('http_request_args', 'lc_add_expect_header');