<?php
/*
Adapted From Plugin Name: Carleton REST Fetcher
Adapted From Plugin URL: http://www.carleton.ca
Adapted From Description: Fetch content from multiple endpoints and combine / sort
Adapted From Version: 1.0
Adapted From Author: Mike Corkum
Adapted From Author URI: http://www.carleton.ca/ccs
*/

class Endpoint {

	/** @var  string */
	protected $url;

	/** @var array  */
	protected $posts = [];

	/**
	 * Endpoint constructor.
	 *
	 * @param string $url Url for endpoint to request
	 */
	public function __construct( $url ) {
		$this->url = $url;
		$this->get_cache();
	}

	/**
	 * Get one page of posts
	 *
	 * @param int $page
	 *
	 * @return array Array of posts, will be empty if page can't be found.
	 */
	public function get_posts( $page = 1 ) {
		if ( isset( $this->posts[ $page ] ) ) {
			return $this->posts[ $page ];
		} else {
			$this->make_request( $page );
			if ( isset( $this->posts[ $page ] ) ) {
				return $this->posts[ $page ];
			}
		}
		return [];
	}

	/**
	 * Clear cache
	 */
	public function clear_cache() {
		delete_transient( $this->cache_key() );
	}

	/**
	 * Get a page of posts from remote API
	 *
	 * @param $page
	 */
	protected function make_request( $page ){
		$request = wp_remote_get( add_query_arg( 'page', (int) $page, $this->url ) );

		if ( ! is_wp_error( $request ) && 200 === wp_remote_retrieve_response_code( $request ) ) {
			$this->posts[ $page ] = json_decode( wp_remote_retrieve_body( $request ) );
			$this->set_cache();
		}
	}

	/**
	 * Reset cache every 3 Hours
	 */
	protected function set_cache() {
		if ( ! empty( $this->posts ) ) {
			set_transient( $this->cache_key(), $this->posts, 10800 );
		}
	}

	/**
	 * Get cached posts if possible
	 */
	protected function get_cache() {
		if ( is_array( $posts = get_transient( $this->cache_key() ) ) ) {
			$this->posts = $posts;
		}
	}

	/**
	 * Form cache key based on URL
	 *
	 * @return string
	 */
	protected function cache_key() {
		return 'LCCC_Feeds_' . md5( preg_replace( '(^https?://)', '', $this->url ) );
	}
}


class MultiBlog {

	/** @var array  */
	protected $posts = [];

	/** @var array  */
	protected $endpoints = [];

	/** @var bool  */
	protected $looped = false;

	/** @var int  */
	protected $page;

	/**
	 * MultiBlog constructor.
	 *
	 * @param int $page What page of results to get
	 */
	public function __construct( $page = 1 ) {
		$this->page = 1;
	}

	/**
	 * Add an endpoint to theis collection
	 *
	 * @param $endpoint
	 */
	public function add_endpoint( Endpoint $endpoint ) {
		$this->endpoints[] = $endpoint;
	}

	public function get_posts( $page = 1 ) {
		if ( isset( $this->posts[ $page ] ) ) {
			return $this->posts[ $page ];
		} else {
			$this->merge();
			if ( ! empty( $this->posts ) ) {
				return $this->posts;
			}
		}
		return array();
	}

	/**
	 * Merge posts
	 */
	protected function merge() {
		if ( ! empty( $this->endpoints ) ) {

			foreach ( $this->endpoints as $endpoint ) {
				$this->posts[] = $endpoint->get_posts( $this->page );
			}

			$new_post_array = array();

			foreach ( $this->posts as $key => $value ) {
				foreach ( $value as $post ) {
					// unset( $post->post_content );
					$new_post_array[] = $post;
				}
			}
			$this->posts = $new_post_array;
			$this->posts = $this->sort( $this->posts );
		}
		$this->looped = true;
	}

	/**
	 * Sort posts by date
	 *
	 * @param array $data
	 *
	 * @return array
	 */
		protected function sort( array $data ) {
  
  function sort_dates( $a, $b ){
   if($a->event_start_date != ''){
     return strtotime( $a->event_start_date ) - strtotime( $b->event_start_date );
    //reverse the order to desc
    }else{
     return strtotime( $a->date ) - strtotime( $b->date );
   }
  }
  
  usort( $data, "sort_dates" );

		$data = array_reverse( $data );
		return $data;
	}
}