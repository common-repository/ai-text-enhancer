<?php

/**
 * Settings Page
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @package    AITE
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
?>

<div class="wrap">
	<div class="aite-notice-container">
		<h1><?php 
echo  esc_html( get_admin_page_title() ) ;
?></h1>
	</div>
	<div class="aite_settings_intro">
		<div class="content-text">
			<h1>
				AI Text Enhancer 
				<?php 
?>
			</h1>
			<p class="aite_p_big"><?php 
echo  esc_html( __( 'AI Text Enhancer is a powerful tool that improves your writing by fixing grammar, enriching vocabulary, and optimizing your style. With advanced AI technology, it transforms your text into a polished, engaging piece.', 'ai-text-enhancer' ) ) ;
?></p>
			<h2><?php 
echo  esc_html( __( 'Getting started:', 'ai-text-enhancer' ) ) ;
?></h2>
			<ol>
				<li><?php 
echo  esc_html( __( 'Select an AI provider (WordAI or ChatGPT) and save your API key here in the settings', 'ai-text-enhancer' ) ) ;
?></li>
				<li><?php 
echo  esc_html( __( 'Highlight the text block that should be improved and click the magic button ✨', 'ai-text-enhancer' ) ) ;
?></li>
			</ol>
		</div>
		<div class="content-video">
			<video poster="<?php 
echo  esc_url( plugin_dir_url( __FILE__ ) ) ;
?>../images/howto_poster.jpg" muted controls>
				<source src="<?php 
echo  esc_url( plugin_dir_url( __FILE__ ) ) ;
?>../images/howto.mp4" type="video/mp4">
			</video>
		</div>
	</div>

	<form class="aite-options-form" action="options.php" method="post">
		<?php 
settings_fields( 'ai-text-enhancer-settings' );
?>

		<div class="aite-options-wrapper">
			<div class="inside">
				<div class="panel">
					<div class="panel-header">
						<h3><?php 
echo  __( 'Settings', 'ai-text-enhancer' ) ;
?></h3>
					</div>
					<div class="panel-content">
						<?php 
// Output setting sections and their fields.
do_settings_sections( 'ai-text-enhancer-settings' );
?>

						<div class="row panel-footer">
							<?php 
// Output save settings button.
submit_button( 'Save Settings' );
?>
						</div>
						
					</div>
				</div>
			</div>
			<div class="upgrade-sidebar"></div>
		</div>

	</form>
</div>