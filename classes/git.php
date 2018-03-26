<?php

namespace BEA\SCM;
use Git;

class Gitter implements Format {

	use Singleton;

	/**
	 * Run command
	 *
	 * @param $command
	 *
	 * @author Julien Maury
	 * @return \WP_Error|bool|string
	 */
	public function run_command( $command ) {


		$repo = Git::open(apply_filters( 'BEA/SCM/git_folder_path', ABSPATH ) );

		try {
			$message = $repo->run( $command );
		} catch ( \Exception $e ) {
			return new \WP_Error( 'error_command', $e->getMessage() );
		}

		return $message;
	}



	/**
	 * Prepare data for use
	 *
	 * @author Julien Maury
	 * @return array
	 */
	public function prepare_data() {

		$data = apply_filters( 'BEA/SCM/items_args',
			array(
				__( 'Remote infos', 'bea-scm' )   => 'remote -v',
				__( 'Current branch', 'bea-scm' ) => 'rev-parse --abbrev-ref HEAD',
				__( 'Describe tag', 'bea-scm' )   => 'describe --tags',
				__( 'Last commit', 'bea-scm' )    => 'log -1 --oneline'
			) );

		if ( empty( $data ) && ! is_array( $data ) ) {
			return array();
		}

		return $data;
	}

	/**
	 * Set cache for both cache and cache delete
	 *
	 * @author Julien Maury
	 * @return bool
	 */
	public function set_cache() {
		$data = $this->prepare_data();

		if ( empty( $data ) ) {
			return false;
		}

		return md5( implode( ' ', $data ) );
	}

	/**
	 * Get current infos GIT and put them in cache
	 *
	 * @author Julien Maury
	 * @return array|bool
	 */
	public function get_data() {

		if ( false === ( $output = get_site_transient( 'bea_scm_' . $this->set_cache() ) ) ) {

			foreach ( $this->prepare_data() as $name => $command ) {
				$run_command = self::run_command( $command );

				if ( is_wp_error( $run_command ) ) {
					return $run_command->get_error_message();
					continue;
				}

				$output[ $name ] = $run_command;
			}

			set_site_transient( 'bea_scm_' . $this->set_cache(), $output, (int) apply_filters( 'BEA/SCM/transient_expiration', MINUTE_IN_SECONDS ) );
		}

		return $output;
	}

	/**
	 * Delete cache
	 *
	 * @author Julien Maury
	 * @return bool
	 */
	public function delete_cache() {
		return delete_site_transient( $this->set_cache() );
	}
}