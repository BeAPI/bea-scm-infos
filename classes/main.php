<?php

namespace BEA\SCM;

if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

class Main {

	use Singleton;

	protected function init() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ) );
	}

	/**
	 * @param $text
	 * @author Julien Maury
	 * @return string
	 */
	public function admin_footer_text( $text ) {

		if ( ! $this->current_user_can_footer_message() ) {
			return $text;
		}

		return $text . ' | ' . self::get_basic_data();
	}

	public static function get_tool_page_url() {
		return esc_url( add_query_arg( 'page', 'bea_scm_infos', admin_url( 'tools.php' ) ) );
	}

	/**
	 * Check who has access to admin footer data
	 * @author Julien Maury
	 * @return bool
	 */
	public function current_user_can_footer_message() {

		if ( apply_filters( 'BEA/SCM/hide_admin_footer_text', ! is_super_admin() ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Get GIT infos
	 * Branch + Tag
	 *
	 * @author Julien Maury
	 * @return string| \WP_Error
	 */
	public static function get_basic_data() {
		$git = Gitter::get_instance();

		$branch = $git->run_command( 'rev-parse --abbrev-ref HEAD' );
		$tag    = $git->run_command( 'describe --tags' );

		if ( ! is_wp_error( $branch ) && ! is_wp_error( $tag ) ) {

			return sprintf(
				esc_html__( 'GIT is on %s - %s', 'bea-scm' ),
				$branch,
				$tag
			);

		} else {
			return new \WP_Error( 'git_broken', esc_html__( 'GIT error - click me to see what happened', 'bea-scm' ) );
		}
	}


	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'bea-scm-admin-bar', BEA_SCM_INFOS_URL . 'css/admin-bar.css', array(), BEA_SCM_INFOS_VERSION );
	}

	public function debug_infos() {

			Helpers::render( 'scm-infos', array(
				'title'    => __( 'GIT', 'bea-scm' ),
				'instance' => Gitter::get_instance()
			) );
	}

	/**
	 * Add option page
	 */
	public function admin_menu() {
		add_management_page(
			esc_html__( 'BEA SCM Infos', 'bea-scm' ),
			esc_html__( 'BEA SCM Infos', 'bea-scm' ),
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
				'title' => esc_html__( 'Options' ),
			),
		);

		return $sections;
	}

	public function plugin_page() {
		echo '<div class="wrap bea-scm">';
		echo '<h1>' . __( 'BEA SCM Infos', 'bea-scm' ) . '</h1>';
		echo '<p><em>' . sprintf( __( 'Debug infos are cached for %d seconds !', 'bea-scm' ), (int) apply_filters( 'BEA/SCM/transient_expiration', MINUTE_IN_SECONDS )  ) . '</em></p>';
		echo '<div class="postbox">';
		echo '<div class="inside">';
		$this->debug_infos();
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}

}