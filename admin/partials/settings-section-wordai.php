<?php
/**
 * WordAI Section Intro
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @package    AITE
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<p id="<?php echo esc_attr( $args['id'] ); ?>">
	<?php esc_html_e( 'Setup the WordAI API connection.', 'ai-text-enhancer' ); ?>
	<?php echo sprintf( 
    __( 'You can get an API Key <a href="%s">here</a>.', 'ai-text-enhancer' ), 
    esc_url( 'https://wordai.com' ) 
		); 
	?>
</p>