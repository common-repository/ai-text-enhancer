<?php
/**
 * Text Field
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

<?php
	$options = get_option( 'aite_options' );
?>

<input 
	type="text" 
	id="<?php echo esc_attr( $args['label_for'] ); ?>" 
	name="aite_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
	value="<?php echo ( isset( $options[ $args['label_for'] ] ) ) ? esc_attr( $options[ $args['label_for'] ] ) : ''; ?>"
/>