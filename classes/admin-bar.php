<?php

namespace BEA\SCM;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Admin_Bar {

	use Singleton;
	protected $opts;

	public function init() {

		$this->opts = get_option( 'bea_scm' );
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 999 );
	}

	/***
	 * Get data to display on admin bar
	 *
	 * @author Julien Maury
	 * @return array
	 */
	public function get_data() {

		$git_data = Main::get_basic_data();

		if ( ! is_wp_error( $git_data ) ) {
			$data = array( 'classname' => 'green', 'data' => $git_data );
		} else {
			$data = array( 'classname' => 'red', 'data' => $git_data->get_error_message() );
		}

		return $data;
	}

	/**
	 * Plug our bar
	 * @author Julien Maury
	 * @return bool
	 */
	public function admin_bar_menu() {

		if ( empty( $this->opts['which_tool'] ) ) {
			return false;
		}

		global $wp_admin_bar;

		if ( $this->current_user_can_admin_bar( $wp_admin_bar ) ) {
			$this->git_admin_bar( $wp_admin_bar );
		}

		return true;

	}

	/**
	 * Check who has access to admin bar data
	 *
	 * @param $object
	 *
	 * @author Julien Maury
	 * @return bool
	 */
	public function current_user_can_admin_bar( $object ) {

		if ( apply_filters( 'BEA/SCM/show_admin_bar', ! is_super_admin()
		                                              || ! is_object( $object )
		                                              || ! function_exists( 'is_admin_bar_showing' )
		                                              || ! is_admin_bar_showing() ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Handle GIT part
	 * @author Julien Maury
	 *
	 * @param $wp_admin_bar
	 */
	public function git_admin_bar( $wp_admin_bar ) {

		$data = $this->get_data();

		$args = array(
			'id'    => 'bea_scm',
			'title' => $data['data'],
			'href'  => Main::get_tool_page_url(),
			'meta'  => array(
				'class' => $data['classname'],
			)
		);

		if ( in_array( 'git', $this->opts['which_tool'], true ) ) {
			$wp_admin_bar->add_menu( $args );
		}
	}


}