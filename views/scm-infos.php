<?php

if ( ! empty( $data['instance'] ) ) :
	$data = $data['instance']->get_data();
	$results = is_array( $data ) ? array_filter( $data ) : $data;

	?>
	<?php if ( ! empty( $data['title'] ) ) : ?>
    <h3><?php esc_html_e( $data['title'] ); ?></h3>
<?php endif; ?>
    <p><?php _e( 'Debug infos are cached for 1 minute !', 'bea-scm' ); ?></p>
    <ul>
		<?php foreach ( (array) $results as $command => $result ) {
			printf( '<li><strong>%s</strong> : %s</li>', $command, $result );
		}
		?>
    </ul>
<?php endif;
