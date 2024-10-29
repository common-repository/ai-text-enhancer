<?php

/**
 * Improve the quality of your text with AI. Just press the magic button and get your text optimized.
 *
 * @link              https://www.ai-text-enhancer.com
 * @since             1.0.0
 * @package           AITE
 *
 * @wordpress-plugin
 * Plugin Name:       AI Text Enhancer
 * Description:       Improve the quality of your text with AI. Just press the magic button and get your text optimized.
 * Version:           1.1.0
 * Author:            100plugins
 * Author URI:        https://100plugins.com/
 * License:           GPL-3.0+
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       ai-text-enhancer
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * Currently plugin version.
 * 
 */
define( 'AITE_VERSION', '1.1.0' );

if ( function_exists( 'aite_fs' ) ) {
    aite_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    
    if ( !function_exists( 'aite_fs' ) ) {
        // Create a helper function for easy SDK access.
        function aite_fs()
        {
            global  $aite_fs ;
            
            if ( !isset( $aite_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $aite_fs = fs_dynamic_init( array(
                    'id'             => '14232',
                    'slug'           => 'ai-text-enhancer',
                    'premium_slug'   => 'ai-text-enhancer-pro',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_e6f81ae15b24c0233817f82d3ccdf',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                    'slug'       => 'ai-text-enhancer-settings',
                    'first-path' => 'admin.php?page=ai-text-enhancer-settings',
                    'support'    => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $aite_fs;
        }
        
        // Init Freemius.
        aite_fs();
        // Signal that SDK was initiated.
        do_action( 'aite_fs_loaded' );
    }
    
    // ... Your plugin's main file logic ...
    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-aite-activator.php
     */
    function aite_activate_ai_text_enhancer()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-aite-activator.php';
        AITE_Activator::activate();
    }
    
    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-aite-deactivator.php
     */
    function aite_deactivate_ai_text_enhancer()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-aite-deactivator.php';
        AITE_Deactivator::deactivate();
    }
    
    register_activation_hook( __FILE__, 'aite_activate_ai_text_enhancer' );
    register_deactivation_hook( __FILE__, 'aite_deactivate_ai_text_enhancer' );
    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path( __FILE__ ) . 'includes/class-aite-main.php';
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    function aite_run_ai_text_enhancer()
    {
        $plugin = new AITE_Main();
        $plugin->run();
    }
    
    aite_run_ai_text_enhancer();
}
