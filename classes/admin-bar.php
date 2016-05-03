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

	/**
	 * Plug our bar
	 * @author Julien Maury
	 * @return bool|void
	 */
	public function admin_bar_menu() {

		if ( empty( $this->opts['which_tool'] ) ) {
			return false;
		}

		global $wp_admin_bar;

		$this->current_user_can( $wp_admin_bar );
		$this->git_admin_bar( $wp_admin_bar );

	}

	/**
	 * Check who has access to admin bar infos
	 *
	 * @param $object
	 * @author Julien Maury
	 * @return bool|void
	 */
	public function current_user_can($object){

		if ( apply_filters( 'BEA/SCM/show_admin_bar', ! is_super_admin()
		     || ! is_object( $object )
		     || ! function_exists( 'is_admin_bar_showing' )
		     || ! is_admin_bar_showing() ) ) {
			return false;
		}
	}

	/**
	 * Handle GIT part
	 * @author Julien Maury
	 * @param $wp_admin_bar
	 */
	public function git_admin_bar( $wp_admin_bar ) {

		$args = array(
			'id'    => 'bea_scm',
			'title' => Main::get_basic_infos(),
			'href'  => esc_url( add_query_arg( 'page', 'bea_scm_infos', admin_url( 'tools.php' ) ) ),
			'meta'  => array(
				'class' => 'bea-scm',
			)
		);

		if ( in_array( 'git', $this->opts['which_tool'] ) ) {
			$wp_admin_bar->add_menu( $args );
		}
	}


}