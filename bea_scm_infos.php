<?php
/*
 Plugin Name: BEA SCM Infos
 Version: 0.19
 Version Boilerplate: 2.1.2
 Plugin URI: http://www.beapi.fr
 Description: Get infos from versionning system you use
 Author: BE API Technical team
 Author URI: http://www.beapi.fr
 Domain Path: languages
 Text Domain: bea-scm
 
 ----
 Copyright 2016 BE API Technical team (human@beapi.fr)
 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
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
