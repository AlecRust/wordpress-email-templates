<?php

/**
 * All customizer aspects will go in here
 *
 * @link       https://wp.timersys.com
 * @since      1.0.0
 *
 * @package    Mailtpl
 * @subpackage Mailtpl/includes
 * @author     Damian Logghe <info@timersys.com>
 */
class Mailtpl_Customizer {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->defaults = Mailtpl::defaults();

	}

	/**
	 * Add all panels to customizer
	 * @param $wp_customize
	 */
	public function register_customize_sections( $wp_customize ){

		$wp_customize->add_panel( 'mailtpl', array(
			'title' => __( 'Email Templates', $this->plugin_name ),
		) );

		$this->template_section( $wp_customize );
		$this->header_section( $wp_customize );
		$this->footer_section( $wp_customize );

		do_action('mailtpl/customize_sections', $wp_customize );

	}

	/**
	 * Remover other panels and sections
	 * @param $active
	 * @param $section
	 *
	 * @return bool
	 */
	public function remove_other_sections( $active, $section ) {
		if ( isset( $_GET['mailtpl_display'] ) ) {
			if (
				in_array( $section->id,
					apply_filters( 'mailtpl/customizer_sections',
							array(  'section_mailtpl_footer',
									'section_mailtpl_template',
									'section_mailtpl_header'
							)
					)
				)
			) {
				return true;
			}
			return false;
		}
		return true;
	}

	/**
	 * Here we capture the page and show template acordingly
	 * @param $template
	 *
	 * @return string
	 */
	public function capture_customizer_page( $template ){

		if( is_customize_preview() && isset( $_GET['mailtpl_display'] ) && 'true' == $_GET['mailtpl_display'] ){
			return MAILTPL_PLUGIN_DIR . "/admin/templates/simple.php";
		}
		return $template;
	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'mailtpl-js', MAILTPL_PLUGIN_URL . '/admin/js/mailtpl-admin.js', '', $this->version, false );
		wp_localize_script( 'mailtpl-js', 'mailtpl',
			array(
				'focus' => 'mailtpl_template', // id de un control
			)
		);
	}

	/**
	 * Enqueue scripts for preview area
	 * @since 1.0.0
	 */
	public function enqueue_template_scripts(){
		wp_enqueue_script( 'mailtpl-front-js', MAILTPL_PLUGIN_URL . '/admin/js/mailtpl-public.js', array(  'jquery', 'customize-preview' ), $this->version, true );
	}

	/**
	 * Template Section
	 * @param $wp_customize WP_Customize_Manager
	 */
	private function template_section($wp_customize) {
		$wp_customize->add_section( 'section_mailtpl_template', array(
			'title' => __( 'Template', $this->plugin_name ),
			'panel' => 'mailtpl',
		) );
		$wp_customize->add_setting( 'mailtpl_opts[template]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['template'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => '',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize,
			'mailtpl_template', array(
				'label'         => __( 'Choose one', $this->plugin_name ),
				'type'          => 'select',
				'section'       => 'section_mailtpl_template',
				'settings'      => 'mailtpl_opts[template]',
				'choices'       => array(
					'simple'    => 'Simple Theme',
					'fullwidth' => 'Fullwidth'
				),
				'description'   => ''
			)
		) );

		$wp_customize->add_setting( 'mailtpl_opts[body_bg]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['body_bg'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => '',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'mailtpl_body_bg', array(
				'label'         => __( 'Background Color', $this->plugin_name ),
				'section'       => 'section_mailtpl_template',
				'settings'      => 'mailtpl_opts[body_bg]',
				'description'   => __( 'Choose email background color', $this->plugin_name )
			)
		) );
	}

	/**
	 * Footer section
	 *
	 * @param $wp_customize WP_Customize_Manager
	 */
	private function footer_section($wp_customize) {
		$wp_customize->add_section( 'section_mailtpl_footer', array(
			'title' => __( 'Footer', $this->plugin_name ),
			'panel' => 'mailtpl',
		) );
		$wp_customize->add_setting( 'mailtpl_opts[footer_text]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['footer_text'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => '',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize,
			'mailtpl_footer', array(
				'label'     => __( 'Footer text', $this->plugin_name ),
				'type'      => 'text',
				'section'   => 'section_mailtpl_footer',
				'settings'  => 'mailtpl_opts[footer_text]',
				'description'   => __('Change the email footer here', $this->plugin_name )
			)
		) );

		// footer alignment
		$wp_customize->add_setting( 'mailtpl_opts[footer_aligment]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['footer_aligment'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => '',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize,
			'mailtpl_footer_aligment', array(
				'label'         => __( 'Aligment', $this->plugin_name ),
				'type'          => 'select',
				'default'       => 'center',
				'choices'       => array(
					'left'  => 'Left',
					'center'=> 'Center',
					'right' => 'Right'
				),
				'section'       => 'section_mailtpl_footer',
				'settings'      => 'mailtpl_opts[footer_aligment]',
				'description'   => __( 'Choose alignment for footer', $this->plugin_name )
			)
		) );

		// background color
		$wp_customize->add_setting( 'mailtpl_opts[footer_bg]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['footer_bg'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => '',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'mailtpl_footer_bg', array(
				'label'         => __( 'Background Color', $this->plugin_name ),
				'section'       => 'section_mailtpl_footer',
				'settings'      => 'mailtpl_opts[footer_bg]',
				'description'   => __( 'Choose footer background color', $this->plugin_name )
			)
		) );
		// text color
		$wp_customize->add_setting( 'mailtpl_opts[footer_text_color]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['footer_text_color'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => '',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'mailtpl_footer_text_color', array(
				'label'         => __( 'Text Color', $this->plugin_name ),
				'section'       => 'section_mailtpl_footer',
				'settings'      => 'mailtpl_opts[footer_text_color]',
				'description'   => __( 'Choose header background color', $this->plugin_name )
			)
		) );
	}

	/**
	 * Header section
	 * @param $wp_customize WP_Customize_Manager
	 */
	private function header_section( $wp_customize ) {

		$wp_customize->add_section( 'section_mailtpl_header', array(
			'title' => __( 'Email Header', $this->plugin_name ),
			'panel' => 'mailtpl',
		) );

		// image logo
		$wp_customize->add_setting( 'mailtpl_opts[header_logo]', array(
			'type'                  => 'option',
			'default'               => '',
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => '',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize,
			'mailtpl_header', array(
				'label'         => __( 'Logo', $this->plugin_name ),
				'type'          => 'image',
				'section'       => 'section_mailtpl_header',
				'settings'      => 'mailtpl_opts[header_logo]',
				'description'   => __( 'Add an image to use in header. Leave empty to use text instead', $this->plugin_name )
			)
		) );

		// image logo
		$wp_customize->add_setting( 'mailtpl_opts[header_logo_text]', array(
			'type'                  => 'option',
			'default'               => '',
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => '',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize,
			'mailtpl_header_logo_text', array(
				'label'         => __( 'Logo', $this->plugin_name ),
				'type'          => 'text',
				'section'       => 'section_mailtpl_header',
				'settings'      => 'mailtpl_opts[header_logo_text]',
				'description'   => __( 'Add text to your mail header', $this->plugin_name )
			)
		) );
		// header alignment
		$wp_customize->add_setting( 'mailtpl_opts[header_aligment]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['header_aligment'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => '',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize,
			'mailtpl_aligment', array(
				'label'         => __( 'Aligment', $this->plugin_name ),
				'type'          => 'select',
				'default'       => 'center',
				'choices'       => array(
					'left'  => 'Left',
					'center'=> 'Center',
					'right' => 'Right'
				),
				'section'       => 'section_mailtpl_header',
				'settings'      => 'mailtpl_opts[header_aligment]',
				'description'   => __( 'Choose alignment for header', $this->plugin_name )
			)
		) );

		// background color
		$wp_customize->add_setting( 'mailtpl_opts[header_bg]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['header_bg'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => '',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'mailtpl_header_bg', array(
				'label'         => __( 'Background Color', $this->plugin_name ),
				'section'       => 'section_mailtpl_header',
				'settings'      => 'mailtpl_opts[header_bg]',
				'description'   => __( 'Choose header background color', $this->plugin_name )
			)
		) );
		// text color
		$wp_customize->add_setting( 'mailtpl_opts[header_text_color]', array(
			'type'                  => 'option',
			'default'               => $this->defaults['header_text_color'],
			'transport'             => 'postMessage',
			'capability'            => 'edit_theme_options',
			'sanitize_callback'     => '',
			'sanitize_js_callback'  => '',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize,
			'mailtpl_header_text_color', array(
				'label'         => __( 'Text Color', $this->plugin_name ),
				'section'       => 'section_mailtpl_header',
				'settings'      => 'mailtpl_opts[header_text_color]',
				'description'   => __( 'Choose header background color', $this->plugin_name )
			)
		) );

	}

}