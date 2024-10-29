<?php
/**
 * Select Field
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

<select
	id="<?php echo esc_attr( $args['label_for'] ); ?>"
	name="aite_options[<?php echo esc_attr( $args['label_for'] ); ?>]">

	<?php foreach ( $args['options'] as $item ) : ?>

	<option 
		value="<?php echo esc_attr( $item['value'] ); ?>" 
		<?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], $item['value'], false ) ) : ( '' ); ?> 
		<?php echo isset( $item['disabled'] ) ? 'disabled' : ''; ?> >
		<?php echo esc_textarea( $item['label'] ); ?>
	</option>

	<?php endforeach; ?>

</select>