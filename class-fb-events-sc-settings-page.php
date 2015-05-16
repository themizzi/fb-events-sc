<?php
/**
 * @author Joe Mizzi <themizzi@me.com>
 */

/**
 * Class FB_Events_SC_Settings_Page
 */
class FB_Events_SC_Settings_Page {

	private $options;

	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	public function add_plugin_page()
	{
		// This page will be under "Settings"
		add_options_page(
			'FB Events SC',
			'FB Events SC Settings',
			'manage_options',
			'fb-events-sc-setting-admin',
			array( $this, 'create_admin_page' )
		);
	}

	public function create_admin_page()
	{
		// Set class property
		$this->options = get_option( 'fb_events_sc_options' );

		?>
		<div class="wrap">
			<h2>FB Events SC Settings</h2>
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'fb_events_sc_options' );
				do_settings_sections( 'fb-events-sc-admin' );
				submit_button();
				?>
			</form>
		</div>
	<?php
	}

	public function page_init()
	{
		register_setting(
			'fb_events_sc_options', // Option group
			'fb_events_sc_options', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'fb_events_sc', // ID
			'Facebook Application Details', // Title
			array( $this, 'print_section_info' ), // Callback
			'fb-events-sc-admin' // Page
		);

		add_settings_field(
			'app_id', // ID
			'App ID', // Title
			array( $this, 'app_id_callback' ), // Callback
			'fb-events-sc-admin', // Page
			'fb_events_sc' // Section
		);

		add_settings_field(
			'app_secret',
			'App Secret',
			array( $this, 'app_secret_callback' ),
			'fb-events-sc-admin',
			'fb_events_sc'
		);

		add_settings_field(
			'date_format',
			'Date Format',
			array( $this, 'date_format_callback' ),
			'fb-events-sc-admin',
			'fb_events_sc'
		);
	}

	/**
	 * @param array $input Contains all settings fields as array keys
	 * @return array
	 */
	public function sanitize( $input )
	{
		$new_input = array();
		if( isset( $input['app_id'] ) )
			$new_input['app_id'] = sanitize_text_field( $input['app_id'] );

		if( isset( $input['app_secret'] ) )
			$new_input['app_secret'] = sanitize_text_field( $input['app_secret'] );

		if ( isset( $input['date_format'] ) ) {
			$new_input['date_format'] = sanitize_text_field( $input['date_format'] );
		}

		return $new_input;
	}

	public function print_section_info()
	{
		print 'Enter your Facebook application details';
	}

	public function app_id_callback()
	{
		printf(
			'<input type="text" id="app_id" name="fb_events_sc_options[app_id]" value="%s" />',
			isset( $this->options['app_id'] ) ? esc_attr( $this->options['app_id']) : ''
		);
	}

	public function app_secret_callback()
	{
		printf(
			'<input type="text" id="title" name="fb_events_sc_options[app_secret]" value="%s" />',
			isset( $this->options['app_secret'] ) ? esc_attr( $this->options['app_secret']) : ''
		);
	}

	public function date_format_callback()
	{
		printf(
			'<input type="text" id="title" name="fb_events_sc_options[date_format]" value="%s" />',
			isset( $this->options['date_format'] ) ? esc_attr( $this->options['date_format']) : ''
		);
	}
}

if( is_admin() )
	$settings_page = new FB_Events_SC_Settings_Page();