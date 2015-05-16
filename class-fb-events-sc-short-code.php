<?php
/**
 * @author Joe Mizzi <themizzi@me.com>
 */

use Facebook\FacebookSession;
use Facebook\FacebookRequest;

/**
 * Class FB_Events_SC_Short_Code
 */
class FB_Events_SC_Short_Code {

	/** @var array */
	protected $_options;

	/** @var FacebookSession */
	protected $_session;

	/** @var  string */
	protected $_date_format;

	/** @var Mustache_Engine */
	protected $_mustache;

	public function __construct() {
		if (!$this->get_app_id() || !$this->get_app_secret()) {
			add_action( 'admin_notices', array( $this, 'app_id_secret_admin_notice' ) ); ;
			return;
		}
		FacebookSession::setDefaultApplication($this->get_app_id(), $this->get_app_secret());
		add_shortcode( 'fb_events_sc', array( $this, 'short_code' ) );
		wp_enqueue_style( 'fb-events-sc', plugins_url( '/css/fb-events-sc.css', __FILE__ ) );
	}

	public function app_id_secret_admin_notice() {
		echo $this->get_mustache()->render( 'admin-notice', array(
			'class' => 'update-nag notice is-dismissible below-h2',
			'message' => '<a href="/wp-admin/options-general.php?page=fb-events-sc-setting-admin">Please configure your Facebook App ID and Secret</a>'
		) );
	}

	/**
	 * @param $atts
	 * @return bool|string
	 */
	public function short_code( $atts ) {
		if ( !isset( $atts['page_id'] ) ) {
			return false;
		}
		$events = $this->get_events( $atts['page_id'] );
		if ( $events ) {
			return $this->get_mustache()->render('table', array(
				'events' => $events
			));
		} else {
			return false;
		}
	}

	/**
	 * @return FacebookSession
	 */
	public function getFacebookSession()
	{
		if (!$this->_session) {
			$this->_session = new FacebookSession($this->get_app_id() . '|' . $this->get_app_secret());
		}
		return $this->_session;
	}

	/**
	 * @param $page_id
	 * @return bool|mixed
	 */
	public function get_facebook_events( $page_id )
	{
		$request = new FacebookRequest(
			$this->getFacebookSession(),
			'GET',
			'/' . $page_id . '/events',
			array(
				'fields' => 'id,description,end_time,is_date_only,name,start_time,ticket_uri,timezone,place'
			)
		);
		try {
			$response = $request->execute();
			return $response->getGraphObjectList();
		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * @param $page_id
	 * @return array|bool
	 */
	public function get_events( $page_id )
	{
		if ($facebookEvents = $this->get_facebook_events( $page_id )) {
			$events = [];
			foreach ($facebookEvents as $facebookEvent) {
				/** @var Facebook\GraphObject $facebookEvent */
				$date = new DateTime( $facebookEvent->getProperty( 'start_time' ) );
				$events[] = array(
					'date' => $date->format( $this->get_date_format() ),
					'title' => $facebookEvent->getProperty( 'name' ),
					'link' => 'https://www.facebook.com/events/' . $facebookEvent->getProperty( 'id' )
				);
			}
			return $events;
		} else {
			return false;
		}
	}

	/**
	 * @return mixed|string|void
	 */
	public function get_date_format()
	{
		if ( !$this->_date_format ) {
			$options = $this->get_options();
			if ( isset( $options['date_format'] ) && !empty( $options['date_format'] ) ) {
				$this->_date_format =  $options['date_format'];
			} else {
				$this->_date_format = get_option( 'date_format' );
			}
		}
		return $this->_date_format;
	}

	/**
	 * @return string|null
	 */
	public function get_app_id()
	{
		$options = $this->get_options();
		return isset( $options['app_id'] ) ? $options['app_id'] : null;
	}

	/**
	 * @return string|null
	 */
	public function get_app_secret()
	{
		$options = $this->get_options();
		return isset( $options['app_secret'] ) ? $options['app_secret'] : null;
	}

	/**
	 * @return array|mixed|void
	 */
	public function get_options()
	{
		if ( !$this->_options ) {
			$this->_options = get_option( 'fb_events_sc_options' );
		}
		return $this->_options;
	}

	/**
	 * @return Mustache_Engine
	 */
	public function get_mustache()
	{
		if ( !$this->_mustache ) {
			$this->_mustache = new Mustache_Engine( array(
				'loader'          => new Mustache_Loader_FilesystemLoader( dirname( __FILE__ )
				                                                           . '/views' ),
				'partials_loader' => new Mustache_Loader_FilesystemLoader( dirname( __FILE__ )
				                                                           . '/views' )
			) );
		}
		return $this->_mustache;
	}
}

/** @var FB_Events_SC_Short_Code $shortcode */
$shortcode = new FB_Events_SC_Short_Code();