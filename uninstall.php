<?php

// If cheating exit
if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option( 'bea_scm' );

global $wpdb;
$wpdb->query(
	"
		DELETE
		FROM {$wpdb->options}
		WHERE meta_key LIKE 'bea_scm_%'
		"
);