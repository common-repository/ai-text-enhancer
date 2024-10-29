<?php
/**
 * Number Field
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

<div class="aite-slider">
	<p class="range-value"><?php echo esc_attr( $args['min'] ); ?></p>
	<input 
	type="range" 
	id="<?php echo esc_attr( $args['label_for'] ); ?>" 
	name="aite_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
	min="<?php echo esc_attr( $args['min'] ); ?>" 
	max="<?php echo esc_attr( $args['max'] ); ?>"
	value="<?php echo ( isset( $options[ $args['label_for'] ] ) ) ? esc_attr( $options[ $args['label_for'] ] ) : esc_attr( $args['default'] ); ?>" 
	>
	<p class="range-value"><?php echo esc_attr( $args['max'] ); ?></p>
</div> 