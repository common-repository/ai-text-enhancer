<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    AITE
 */
/**
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 */
class AITE_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $ai_text_enhancer    The ID of this plugin.
     */
    private  $ai_text_enhancer ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $ai_text_enhancer       The name of this plugin.
     * @param      string $version    The version of this plugin.
     */
    public function __construct( $ai_text_enhancer, $version )
    {
        $this->ai_text_enhancer = $ai_text_enhancer;
        $this->version = $version;
    }
    
    /**
     * BLOCK EDITOR
     */
    public function enqueue_editor_scripts()
    {
        wp_register_script(
            'ai-text-enhancer-editor',
            plugin_dir_url( __FILE__ ) . '../build/index.js',
            array(
            'wp-blocks',
            'wp-dom',
            'wp-dom-ready',
            'wp-edit-post'
        ),
            $this->version,
            false
        );
        wp_enqueue_script( 'ai-text-enhancer-editor' );
        wp_localize_script( 'ai-text-enhancer-editor', 'phpvars', array(
            'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            'nonce'     => wp_create_nonce( 'ai-text-enhancer-nonce' ),
            'pluginurl' => plugin_dir_url( __FILE__ ),
        ) );
    }
    
    /**
     * AI REQUEST
     */
    public function ai_request()
    {
        $options = get_option( 'aite_options' );
        
        if ( !$options ) {
            echo  '{"status": "Failure", "error": "AI Text Enhancer: Please setup the AI api connection in the settings first."}' ;
            die;
        }
        
        switch ( $options['aite_ai_provider'] ) {
            case 'wordai':
                $this->ai_request_wordai();
                break;
            case 'chatgpt':
                $this->ai_request_chatgpt();
                break;
            default:
                break;
        }
    }
    
    /**
     * WordAI API Request
     */
    public function ai_request_wordai()
    {
        $options = get_option( 'aite_options' );
        $email = $options['wordai_email'];
        $api_key = $options['wordai_apikey'];
        $url = 'https://wai.wordai.com/api/rewrite';
        $text = ( isset( $_REQUEST['text'] ) ? sanitize_textarea_field( wp_unslash( $_REQUEST['text'] ) ) : '' );
        $return_rewrites = 'true';
        $rewrite_num = $options['wordai_rewrite_num'];
        $uniqueness = $options['wordai_uniqueness'];
        // POST request with WP HTTP API.
        $body = array(
            'email'           => $email,
            'key'             => $api_key,
            'input'           => $text,
            'rewrite_num'     => $rewrite_num,
            'return_rewrites' => $return_rewrites,
            'uniqueness'      => $uniqueness,
        );
        $args = array(
            'body'    => $body,
            'timeout' => '15',
        );
        $response = wp_remote_post( $url, $args );
        echo  wp_kses_post( $response['body'] ) ;
        die;
    }
    
    public function ai_request_chatgpt()
    {
    }
    
    /**
     * Validate settings fields
     *
     * @param   array $inputs The field values.
     */
    public function aite_field_validation( $inputs )
    {
        $valid_inputs = array();
        foreach ( $inputs as $option => $input ) {
            switch ( $option ) {
                case 'wordai_email':
                    $input = sanitize_email( $input );
                    
                    if ( is_email( $input ) ) {
                        $valid_inputs[$option] = $input;
                    } else {
                        add_settings_error(
                            'aite_messages',
                            'aite_message',
                            __( 'Email is not correct', 'ai-text-enhancer' ),
                            'error'
                        );
                    }
                    
                    break;
                case 'wordai_apikey':
                    $valid_inputs[$option] = sanitize_text_field( $input );
                    break;
                case 'chatgpt_apikey':
                    $valid_inputs[$option] = sanitize_text_field( $input );
                    break;
                default:
                    $valid_inputs[$option] = sanitize_text_field( $input );
                    break;
            }
        }
        return $valid_inputs;
    }
    
    /**
     * SETTINGS
     */
    public function settings_init()
    {
        register_setting( 'ai-text-enhancer-settings', 'aite_options', array( $this, 'aite_field_validation' ) );
        /* SECTION: General */
        add_settings_section(
            'aite_section_general',
            __( 'Select an AI provider', 'ai-text-enhancer' ),
            array( $this, 'aite_section_general_html' ),
            'ai-text-enhancer-settings',
            array(
            'before_section' => '<div class="%s">',
            'after_section'  => '</div>',
            'section_class'  => 'row',
        )
        );
        if ( !aite_fs()->is_plan_or_trial( 'pro' ) || !aite_fs()->is_premium() ) {
            add_settings_field(
                'aite_ai_provider',
                __( 'AI Provider', 'ai-text-enhancer' ),
                array( $this, 'aite_field_select_html' ),
                'ai-text-enhancer-settings',
                'aite_section_general',
                array(
                'label_for' => 'aite_ai_provider',
                'class'     => 'aite_row',
                'options'   => array( array(
                'value' => 'wordai',
                'label' => __( 'WordAI 🇬🇧', 'ai-text-enhancer' ),
            ), array(
                'value'    => 'chatgpt',
                'label'    => __( 'ChatGPT-3.5 (Multilingual) [PRO]', 'ai-text-enhancer' ),
                'disabled' => true,
            ) ),
            )
            );
        }
        /* SECTION: WordAI */
        add_settings_section(
            'aite_section_wordai',
            __( 'WordAI', 'ai-text-enhancer' ),
            array( $this, 'aite_section_wordai_html' ),
            'ai-text-enhancer-settings',
            array(
            'before_section' => '<div class="%s">',
            'after_section'  => '</div>',
            'section_class'  => 'row row-wordai',
        )
        );
        add_settings_field(
            'wordai_email',
            __( 'Email', 'ai-text-enhancer' ),
            array( $this, 'aite_field_text_html' ),
            'ai-text-enhancer-settings',
            'aite_section_wordai',
            array(
            'label_for' => 'wordai_email',
            'class'     => 'aite_row',
        )
        );
        add_settings_field(
            'wordai_apikey',
            __( 'API Key', 'ai-text-enhancer' ),
            array( $this, 'aite_field_text_html' ),
            'ai-text-enhancer-settings',
            'aite_section_wordai',
            array(
            'label_for' => 'wordai_apikey',
            'class'     => 'aite_row',
        )
        );
        add_settings_field(
            'wordai_rewrite_num',
            __( 'The number of rewrites you\'d like WordAI to create for your original text', 'ai-text-enhancer' ),
            array( $this, 'aite_field_number_html' ),
            'ai-text-enhancer-settings',
            'aite_section_wordai',
            array(
            'label_for' => 'wordai_rewrite_num',
            'class'     => 'aite_row',
            'min'       => '1',
            'max'       => '10',
            'default'   => '1',
        )
        );
        add_settings_field(
            'wordai_uniqueness',
            __( 'Uniqueness', 'ai-text-enhancer' ),
            array( $this, 'aite_field_number_html' ),
            'ai-text-enhancer-settings',
            'aite_section_wordai',
            array(
            'label_for' => 'wordai_uniqueness',
            'class'     => 'aite_row',
            'min'       => '1',
            'max'       => '3',
            'default'   => '2',
        )
        );
    }
    
    /**
     * Add the Settings page.
     */
    public function settings_page()
    {
        add_menu_page(
            'AI Text Enhancer',
            'AI Text Enhancer',
            'manage_options',
            'ai-text-enhancer-settings',
            array( $this, 'settings_page_html' )
        );
    }
    
    /**
     * Top level menu callback function
     */
    public function settings_page_html()
    {
        // Check user capabilities.
        if ( !current_user_can( 'manage_options' ) ) {
            return;
        }
        // Check if the user have submitted the settings.
        if ( isset( $_GET['settings-updated'] ) ) {
            // Add settings saved message with the class of "updated".
            add_settings_error(
                'aite_messages',
                'aite_message',
                __( 'Settings Saved', 'ai-text-enhancer' ),
                'updated'
            );
        }
        // Show error/update messages.
        settings_errors( 'aite_messages' );
        require 'partials/settings-page.php';
    }
    
    /**
     * Section: General
     *
     * @param array $args  The settings array, defining title, id, callback.
     */
    public function aite_section_general_html( $args )
    {
        require 'partials/settings-section-general.php';
    }
    
    /**
     * Section: WordAI
     *
     * @param array $args  The settings array, defining title, id, callback.
     */
    public function aite_section_wordai_html( $args )
    {
        require 'partials/settings-section-wordai.php';
    }
    
    /**
     * Section: ChatGPT
     *
     * @param array $args  The settings array, defining title, id, callback.
     */
    public function aite_section_chatgpt_html( $args )
    {
        require 'partials/settings-section-chatgpt.php';
    }
    
    /**
     * Field Type: Text
     *
     * @param array $args The field values.
     */
    public function aite_field_text_html( $args )
    {
        require 'partials/settings-field-text.php';
    }
    
    /**
     * Field Type: Number
     *
     * @param array $args The field values.
     */
    public function aite_field_number_html( $args )
    {
        require 'partials/settings-field-number.php';
    }
    
    /**
     * Field Type: Select
     *
     * @param array $args The field values.
     */
    public function aite_field_select_html( $args )
    {
        require 'partials/settings-field-select.php';
    }
    
    /**
     * Global Admin styles
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
            'ai-text-enhancer-admin-styles',
            plugin_dir_url( __FILE__ ) . 'css/ai-text-enhancer-admin.css',
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Global Admin JS
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script(
            'ai-text-enhancer-admin-script',
            plugin_dir_url( __FILE__ ) . 'js/ai-text-enhancer-admin.js',
            array( 'jquery' ),
            $this->version,
            false
        );
    }

}