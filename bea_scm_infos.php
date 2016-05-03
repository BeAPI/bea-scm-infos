<?php
/**
 Plugin Name: BEA SCM Infos
 Version: 0.19
 Version Boilerplate: 2.1.2
 Plugin URI: http://www.beapi.fr
 Description: Your plugin description
 Author: BE API Technical team
 Author URI: http://www.beapi.fr
 Domain Path: languages
 Text Domain: bea-scm
*/

define( 'BEA_SCM_INFOS_VERSION', '0.19' );
define( 'BEA_SCM_INFOS_DIR', plugin_dir_path( __FILE__ )  );
define( 'BEA_SCM_INFOS_URL', plugin_dir_url( __FILE__ )  );
define( 'BEA_SCM_INFOS_VIEWS_FOLDER_NAME', 'bea-scm' );

if ( ! file_exists( BEA_SCM_INFOS_DIR . 'vendor/autoload.php' ) ) {
	return false;
}

require_once( BEA_SCM_INFOS_DIR . 'vendor/autoload.php' );

add_action( 'plugins_loaded', 'bea_scm_infos_init' );
function bea_scm_infos_init(){

	if ( is_admin() ) {
		\BEA\SCM\Main::get_instance();
		\BEA\SCM\Admin_Bar::get_instance();
	}
}


add_action( 'init', 'bea_scm_run_on_init' );
function bea_scm_run_on_init(){
	load_plugin_textdomain( 'bea-scm', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}


register_activation_hook( __FILE__, 'bea_scm_on_activation' );
function bea_scm_on_activation(){
	$opts = get_option( 'bea_scm' );
	if ( ! is_array( $opts ) ) {
		update_option(
			'bea_scm',
			array(
				'path'       => ABSPATH,
				'which_tool' => array( 'git' => 'git' )
			)
		);
	}
}
