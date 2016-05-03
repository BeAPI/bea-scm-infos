<?php
namespace BEA\SCM;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Main {

	protected $settings_api;
	protected $opts;

	use Singleton;

	protected function init() {

		if ( ! class_exists( '\WeDevs_Settings_API' ) ) {
			return false;
		}

		$this->settings_api = new \WeDevs_Settings_API;
		$this->opts         = get_option( 'bea_scm' );

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Admin footer
	 * @author Julien Maury
	 * @return string
	 */
	public function admin_footer_text() {
		return self::get_basic_infos();
	}


	/**
	 * Get GIT infos
	 * Branch + Tag
	 *
	 * @author Julien Maury
	 * @return string
	 */
	public static function get_basic_infos(){
		$git = Git::get_instance();
		return sprintf(
			__( 'GIT is on %s - %s', 'bea-scm' ),
			$git->run_command( 'rev-parse --abbrev-ref HEAD' ),
			$git->run_command( 'describe --tags' )
		);
	}


	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'bea-scm-admin-bar', BEA_SCM_INFOS_URL . 'css/admin-bar.css', array(), BEA_SCM_INFOS_VERSION );
	}

	public function debug_infos() {

		if ( ! empty( $this->opts['which_tool'] ) && in_array( 'git', $this->opts['which_tool'] ) ) {

			Helpers::render( 'scm-infos', array(
				'options'  => $this->opts,
				'title'    => __( 'GIT', 'bea-scm' ),
				'instance' => Git::get_instance()
			) );
		} else {
			esc_html_e( 'There is no infos to display, please choose one of the versionning system please.', 'bea-scm' );
		}
	}

	/**
	 * Init things
	 */
	public function admin_init() {

		//set the settings
		$this->settings_api->set_sections( $this->get_settings_sections() );
		$this->settings_api->set_fields( $this->get_settings_fields() );
		//initialize settings
		$this->settings_api->admin_init();
	}

	/**
	 * Add option page
	 */
	public function admin_menu() {
		add_management_page(
			__( 'BEA SCM Infos', 'bea-scm' ),
			__( 'BEA SCM Infos', 'bea-scm' ),
			'manage_options', 'bea_scm_infos',
			array(
				$this,
				'plugin_page'
			)
		);
	}

	/**
	 * Register tabs
	 *
	 * @return array
	 */
	public function get_settings_sections() {
		$sections = array(
			array(
				'id'    => 'bea_scm',
				'title' => __( 'Options' ),
			),
		);

		return $sections;
	}

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */
	public function get_settings_fields() {
		$settings_fields = array(
			'bea_scm' => array(
				array(
					'name'    => 'which_tool',
					'label'   => __( 'Which system of versionning do you use ?', 'bea-scm' ),
					'type'    => 'multicheck',
					'options' => array(
						'git'       => __( 'GIT', 'bea-scm' ),
						//'svn'       => __( 'SVN (subversion)', 'bea-scm' ),
						//'mercurial' => __( 'Mercurial', 'bea-scm' ),
					),
				),
				array(
					'name'    => 'path',
					'label'   => __( 'Enter path', 'bea-scm' ),
					'desc'    => __( 'Default is ABSPATH (WordPress root)', 'bea-scm' ),
					'type'    => 'text',
					'default' => ABSPATH,
				),
			)
		);

		return $settings_fields;
	}

	public function plugin_page() {
		echo '<div class="wrap">';
		echo '<h1>' . __( 'BEA SCM Infos', 'bea-scm' ) . '</h1>';
		//$this->settings_api->show_navigation();
		$this->settings_api->show_forms();
		echo '<div class="postbox">';
		echo '<div class="inside">';
		$this->debug_infos();
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}

}