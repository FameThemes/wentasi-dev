<?php
/**
 * Theme Customizer.
 *
 * @package OnePress
 */


require get_template_directory() . '/inc/customizer/typography/helper.php';

/**
 * Auto add style for typography settings
 */
wp_typography_helper_auto_apply( 'test_typography' );
wp_typography_helper_auto_apply( 'test_heading_h1' );



// Register our customizer panels, sections, settings, and controls.
add_action( 'customize_register', 'wentasi_customize_register', 15 );

function wentasi_customize_register( $wp_customize ) {

	// Load custom controls
	require get_template_directory() . '/inc/customizer-controls.php';

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';


	/*------------------------------------------------------------------------*/
    /*  TEST REPEATABLE control
    /*------------------------------------------------------------------------*/

    /* === Testing === */


    //$wp_customize->add_panel( 'test_panel_repeatable', array( 'priority' => 5, 'title' => esc_html__( 'Repeatable Panel', 'ctypo' ) ) );
    $wp_customize->add_section(
        'test_section_repeatable',
        // array( 'panel' => 'test_panel_repeatable', 'title' => esc_html__( 'Repeatable Section', 'ctypo' ) )
        array(  'title' => esc_html__( 'Repeatable Section', 'ctypo' ), 'priority' => 2, )
    );

    // @todo Better sanitize_callback functions.
    $wp_customize->add_setting(
        'new_repeatable_id',
        array(
        'default' => json_encode(
            array(
                array(
                    'id_name_1' => 'Item 1',
                    'id_name_color' => '#333333',
                    'id_name_2'  => 'la la la',
                    'id_name_3'     => array(
                        'id'=>'2324324',
                        'url'=>'',
                    ),
                ),

                array(
                    'id_name_1' => 'Item 2',
                    'id_name_color' => '#333333',
                    'id_name_2'  => 'la la la',
                    'id_name_3'     => array(
                        'id'=>'2324324',
                        'url'=>'',
                    ),
                ),
            )
        ),
        //'sanitize_callback' => 'sanitize_repeatable_data_field',
        'sanitize_callback' => 'sanitize_repeatable_data_field',
        'transport' => 'postMessage', // refresh or postMessage
    ) );


    $wp_customize->add_control(
        new WP_Customize_Repeatable_Control(
            $wp_customize,
            'new_repeatable_id',
            array(
                'label' 		=> __('Repeatable Field', 'wentasi'),
                'description'   => 'dsadadasdasas',
                'section'       => 'test_section_repeatable',
                'live_title_id' => 'id_name_1', // apply for unput text and textarea only
                'title_format'  => __('Item: [live_title]', 'wentasi'),

                'fields'    => array(
                    'id_name_1' => array(
                        'title'=>'text title',
                        'type'=>'text',
                        //'default' =>'default_value',
                        'desc' =>'this is description text'
                    ),
                    'id_name_color' => array(
                        'title'=>'Color',
                        'type'=>'color',
                        //'default' =>'default_value',
                        'desc' =>'this is description text'
                    ),
                    'id_name_2'  => array(
                        'title'=>'textarea title',
                        'type'=>'textarea',
                        //'default' =>'default_value',
                        'desc' =>'this is description text'

                    ),
                    'id_name_3'     => array(
                        'title'=>'media title',
                        'type'=>'media',
                        'default'=> array(
                            'id'=>'',
                            'url'=>'',
                        ),
                        'desc' =>'this is description text',
                    ),
                    'id_name_4'    => array(
                        'title'=>'select title',
                        'type'=>'select',
                        'multiple'=> true, // false
                        'desc' =>'this is description text',
                        'options' => array(
                            'option_1' => 'label for option 1',
                            'option_2' => 'label for option 2',
                            'option_3' => 'label for option 3',
                        ),
                        //'default'=> 'option_1',
                    ),
                    'select_one'    => array(
                        'title'=>'select title',
                        'type'=>'select',
                        'multiple'=> false, // false
                        'desc' =>'this is description text',
                        'options' => array(
                            'option_1' => 'label for option 1',
                            'option_2' => 'label for option 2',
                            'option_3' => 'label for option 3',
                        ),
                        //'default'=> 'option_3',
                    ),
                    'id_name_5'     => array(
                        'title'=>'radio title',
                        'type'=>'radio',
                        'desc' =>'this is description text',
                        'options' => array(
                            'option_1' => 'label for option 1',
                            'option_2' => 'label for option 2',
                            'option_3' => 'label for option 3',
                        ),
                        //'default'=> 'option_1'
                    ),
                    'id_name_6'  => array(
                        'title'=>'checkbox title',
                        'desc' =>'this is description text',
                        'type'=>'checkbox',
                        //'value'=> 'option_1'
                    ),
                ),

            )
        )
    );


    /*------------------------------------------------------------------------*/
    /*  TEST Typo control
    /*------------------------------------------------------------------------*/

    // Register typography control JS template.
    $wp_customize->register_control_type( 'WP_Customize_Typography_Control' );

    // Load customizer typography control class.
    $wp_customize->add_section(
        'test_typography_section',
        array(  'title' => esc_html__( 'Test Paragraphs', 'ctypo' ), 'priority' => 5, )
    );

    // Add the `<p>` typography settings.
    // @todo Better sanitize_callback functions.
    $wp_customize->add_setting(
        'test_typography',
        array(
            'default' => json_encode(
                array(
                    'css' => array(
                        'font-family'     => '',
                        'font-color'      => '',
                        'font-style'      => '',
                        'font-size'       => '',
                        'line-height'     => '',
                        'letter-spacing'  => '',
                        'text-transform'  => '',
                        'text-decoration' => '',
                    ),
                    'css_selector'  => '',
                    'font_id'       => '',
                    'style'         => '',
                    'font_url'      => '',
               )
            ),
            'sanitize_callback' => 'sanitize_typography_field',
            'transport' => 'postMessage'
        )
    );

    $wp_customize->add_setting(
        'test_heading_h1',
        array(
            'sanitize_callback' => 'sanitize_typography_field',
            'transport' => 'postMessage'
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Typography_Control(
            $wp_customize,
            'test_heading_h1',
            array(
                'label'       => esc_html__( 'Heading h1', 'ctypo' ),
                'description' => __( 'Select how you want your paragraphs to appear.', 'ctypo' ),
                'section'       => 'test_typography_section',
                'css_selector'       => 'body .ft-boxct h1', // css selector
            )
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Typography_Control(
            $wp_customize,
            'test_typography',
            array(
                'label'       => esc_html__( 'Paragraph Typography', 'ctypo' ),
                'description' => __( 'Select how you want your paragraphs to appear.', 'ctypo' ),
                'section'       => 'test_typography_section',
                'css_selector'       => 'body .ft-entry', // css selector
            )
        )
    );


    /* === End Testing === */



    /*------------------------------------------------------------------------*/
    /*  Logo
    /*------------------------------------------------------------------------*/



    $wp_customize->add_section( 'wentasi_logos' , array('priority'    => 20, 'title' => __( 'Site Logo', 'ft' ), 'description' => '', ) );
    	$wp_customize->add_setting( 'wentasi_site_text_logo', array( 'sanitize_callback' => 'sanitize_text_field', 'default' => get_bloginfo('name'), 'transport' => 'postMessage' ) );
    	$wp_customize->add_control( 'wentasi_site_text_logo',
			array(
				'label' 		=> __('Site Text Logo', 'ft'),
				'section' 		=> 'wentasi_logos',
				'description'   => esc_html__('Your site text logo plus your gravatar avatar, use when image logo is empty.', 'ft'),
			)
		);
    	// Site logo image
		$wp_customize->add_setting( 'wentasi_site_image_logo', array( 'sanitize_callback' => 'wentasi_sanitize_file_url' ) );
    	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'wentasi_site_image_logo',
				array(
					'label' 		=> __('Site Image Logo', 'ft'),
					'section' 		=> 'wentasi_logos',
					'description'   => esc_html__('Your site image logo', 'ft'),
				)
			)
		);
		// Footer Social Title
		$wp_customize->add_setting( 'wentasi_logo_width', array( 'sanitize_callback' => 'wentasi_sanitize_number', 'default' => '105', ) );
		$wp_customize->add_control( 'wentasi_logo_width',
			array(
				'label'       => __('Image logo max width', 'ft'),
				'section'     => 'wentasi_logos',
				'description' => 'Logo image max width in px'
			)
		);

	/*------------------------------------------------------------------------*/
    /*  General
    /*------------------------------------------------------------------------*/
	$wp_customize->add_section( 'wentasi_general' , array('priority'    => 21, 'title' => __( 'General', 'ft' ), 'description' => '', ) );

		// Sticky Topbar
		$wp_customize->add_setting( 'wentasi_topbar_sticky',
            array(
                    'sanitize_callback' => 'wentasi_sanitize_checkbox',
                    'default' => '',
                ) );

		$wp_customize->add_control( 'wentasi_topbar_sticky',
			array(
				'type'        => 'checkbox',
				'label'       => __('Disable Sticky Topbar.', 'wentasi'),
				'section'     => 'wentasi_general',
				'description' => esc_html__('Turn off fixed topbar when scroll.', 'wentasi'),
			)
		);

		// Sticky Topbar
		$wp_customize->add_setting( 'wentasi_btt',
            array(
                'sanitize_callback' => 'wentasi_sanitize_checkbox',
                'default' => '',
                ) );
		$wp_customize->add_control( 'wentasi_btt',
			array(
				'type'        => 'checkbox',
				'label'       => __('Hide Back To Top.', 'wentasi'),
				'section'     => 'wentasi_general',
				'description' => esc_html__('Turn off back to top button.', 'wentasi'),
			)
		);

		// Google Fonts
		$wp_customize->add_setting( 'wentasi_google_fonts', array('sanitize_callback' => 'wentasi_sanitize_checkbox', 'default' => '', ) );
		$wp_customize->add_control( 'wentasi_google_fonts',
			array(
				'type'        => 'checkbox',
				'label'       => __('Disable Google Fonts.', 'wentasi'),
				'section'     => 'wentasi_general',
				'description' => esc_html__('In case you use custom fonts for your site, you can disable all google fonts loaded with the theme by default.', 'wentasi'),
			)
		);
		// Copyright
		$wp_customize->add_setting( 'wentasi_copyright_text', array('sanitize_callback' => 'wentasi_sanitize_text' ) );
		$wp_customize->add_control( new Wentasi_Textarea_Custom_Control( $wp_customize, 'wentasi_copyright_text',
				array(
					'label' 		=> __('Footer Copyright Text', 'wentasi'),
					'section' 		=> 'wentasi_general',
					'description'   => '',
				)
			)
		);

		// Copyright
		$wp_customize->add_setting( 'wentasi_custom_css', 
			array(
				'sanitize_callback' => 'wp_filter_nohtml_kses',
				'sanitize_js_callback' => 'wp_filter_nohtml_kses'
			) 
		);
		$wp_customize->add_control( new Wentasi_Textarea_Custom_Control( $wp_customize, 'wentasi_custom_css',
				array(
					'label' 		=> __('Custom CSS', 'wentasi'),
					'section' 		=> 'wentasi_general',
					'description'   => '',
				)
			)
		);

	/*------------------------------------------------------------------------*/
    /*  Styling
    /*------------------------------------------------------------------------*/
	$wp_customize->add_section( 'wentasi_styling' , array('priority'    => 21, 'title' => __( 'Styling', 'ft' ), 'description' => '', ) );
		// Header BG
		$wp_customize->add_setting( 'wentasi_header_bg', array('sanitize_callback' => 'sanitize_hex_color_no_hash', 'sanitize_js_callback' => 'maybe_hash_hex_color', 'default' => '#222222', 'transport' => 'postMessage' ) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wentasi_header_bg',
			array(
				'label'       => __('Header Background', 'ft'),
				'section'     => 'wentasi_styling',
				'description' => ''
			)
		));
		// Primary Color
		$wp_customize->add_setting( 'wentasi_primary_color', array('sanitize_callback' => 'sanitize_hex_color_no_hash', 'sanitize_js_callback' => 'maybe_hash_hex_color', 'default' => '#f67156' ) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wentasi_primary_color',
			array(
				'label'       => __('Primary Color', 'ft'),
				'section'     => 'wentasi_styling',
				'description' => ''
			)
		));

		$wp_customize->add_setting( 'wentasi_secondary_bg', array('sanitize_callback' => 'sanitize_hex_color_no_hash', 'sanitize_js_callback' => 'maybe_hash_hex_color', 'default' => '#ff6', 'transport' => 'postMessage' ) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wentasi_secondary_bg',
			array(
				'label'       => __('Secondary Background', 'ft'),
				'section'     => 'wentasi_styling',
				'description' => ''
			)
		));

		$wp_customize->add_setting( 'wentasi_secondary_color', array('sanitize_callback' => 'sanitize_hex_color_no_hash', 'sanitize_js_callback' => 'maybe_hash_hex_color', 'default' => '#444', 'transport' => 'postMessage' ) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wentasi_secondary_color',
			array(
				'label'       => __('Secondary Color', 'ft'),
				'section'     => 'wentasi_styling',
				'description' => ''
			)
		));


	/*------------------------------------------------------------------------*/
    /*  Social
    /*------------------------------------------------------------------------*/
	$wp_customize->add_section( 'wentasi_social' , array('priority'    => 23, 'title' => __( 'Social Profiles', 'ft' ), 'description' => '', ) );

		// Twitter
		$wp_customize->add_setting( 'wentasi_social_twitter', array('sanitize_callback' => 'esc_url', 'default' => '#', ) );
		$wp_customize->add_control( 'wentasi_social_twitter',
			array(
				'label'       => __('Twitter URL', 'ft'),
				'section'     => 'wentasi_social',
				'description' => ''
			)
		);
		// Facebook
		$wp_customize->add_setting( 'wentasi_social_facebook', array('sanitize_callback' => 'esc_url', 'default' => '#', ) );
		$wp_customize->add_control( 'wentasi_social_facebook',
			array(
				'label'       => __('Faecbook URL', 'ft'),
				'section'     => 'wentasi_social',
				'description' => ''
			)
		);
		// Facebook
		$wp_customize->add_setting( 'wentasi_social_google', array('sanitize_callback' => 'esc_url', 'default' => '#', ) );
		$wp_customize->add_control( 'wentasi_social_google',
			array(
				'label'       => __('Google Plus URL', 'ft'),
				'section'     => 'wentasi_social',
				'description' => ''
			)
		);
		// Instagram
		$wp_customize->add_setting( 'wentasi_social_instagram', array('sanitize_callback' => 'esc_url', 'default' => '#', ) );
		$wp_customize->add_control( 'wentasi_social_instagram',
			array(
				'label'       => __('Instagram URL', 'ft'),
				'section'     => 'wentasi_social',
				'description' => ''
			)
		);
		// RSS
		$wp_customize->add_setting( 'wentasi_social_rss', array('sanitize_callback' => 'esc_url', 'default' => get_bloginfo('rss2_url'), ) );
		$wp_customize->add_control( 'wentasi_social_rss',
			array(
				'label'       => __('RSS URL', 'ft'),
				'section'     => 'wentasi_social',
				'description' => ''
			)
		);


	/*------------------------------------------------------------------------*/
    /*  Single Post
    /*------------------------------------------------------------------------*/
	$wp_customize->add_section( 'wentasi_single_post' , array('priority'    => 26, 'title' => __( 'Single Post', 'ft' ), 'description' => '', ) );
		// Sticky Topbar
		$wp_customize->add_setting( 'wentasi_social_share', array('sanitize_callback' => 'wentasi_sanitize_checkbox', 'default' => '', ) );
		$wp_customize->add_control( 'wentasi_social_share',
			array(
				'type'        => 'checkbox',
				'label'       => __('Hide Social Share', 'wentasi'),
				'section'     => 'wentasi_single_post',
				'description' => esc_html__('Turn off social share section.', 'wentasi'),
			)
		);
		// Sticky Topbar
		$wp_customize->add_setting( 'wentasi_author_box', array('sanitize_callback' => 'wentasi_sanitize_checkbox', 'default' => '', ) );
		$wp_customize->add_control( 'wentasi_author_box',
			array(
				'type'        => 'checkbox',
				'label'       => __('Hide Author Box', 'wentasi'),
				'section'     => 'wentasi_single_post',
				'description' => esc_html__('Turn off Author Box.', 'wentasi'),
			)
		);
		// Sticky Topbar
		$wp_customize->add_setting( 'wentasi_related_post', array('sanitize_callback' => 'wentasi_sanitize_checkbox', 'default' => '', ) );
		$wp_customize->add_control( 'wentasi_related_post',
			array(
				'type'        => 'checkbox',
				'label'       => __('Hide Related Posts', 'wentasi'),
				'section'     => 'wentasi_single_post',
				'description' => esc_html__('Turn off Related Posts section.', 'wentasi'),
			)
		);

		// Sticky Topbar
		$wp_customize->add_setting( 'wentasi_optin_form', array('sanitize_callback' => 'wentasi_sanitize_checkbox', 'default' => '', ) );
		$wp_customize->add_control( 'wentasi_optin_form',
			array(
				'type'        => 'checkbox',
				'label'       => __('Hide Optin Form', 'wentasi'),
				'section'     => 'wentasi_single_post',
				'description' => esc_html__('Turn off Optin Form section.', 'wentasi'),
			)
		);
		// Optin Title
		$wp_customize->add_setting( 'wentasi_optin_form_title', array( 'sanitize_callback' => 'sanitize_text_field', 'default' => 'Free Ebooks and Course' ) );
    	$wp_customize->add_control( 'wentasi_optin_form_title',
			array(
				'label' 		=> __('Optin Form Title', 'ft'),
				'section' 		=> 'wentasi_single_post',
				'description'   => esc_html__('Your optin form title.', 'ft'),
			)
		);

		// Optin Text
		$wp_customize->add_setting( 'wentasi_optin_form_text', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.
Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.' ) );
		$wp_customize->add_control( new Wentasi_Textarea_Custom_Control( $wp_customize, 'wentasi_optin_form_text',
				array(
					'label' 		=> __('Optin Form Text', 'wentasi'),
					'section' 		=> 'wentasi_single_post',
					'description'   => '',
				)
			)
		);
		// Optin code
		$wp_customize->add_setting( 'wentasi_optin_form_code', array('sanitize_callback' => 'sanitize_text_field', 'default' => '<form action="http://famethemes.us7.list-manage.com/subscribe/post?u=69bf8334f702278832e7e224f&id=08f4359108" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate><input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required/><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"/>
</form>' ) );
		$wp_customize->add_control( new Wentasi_Textarea_Custom_Control( $wp_customize, 'wentasi_optin_form_code',
				array(
					'label' 		=> __('Optin Code', 'wentasi'),
					'section' 		=> 'wentasi_single_post',
					'description'   => 'Enter your optin code from newsletter service.',
				)
			)
		);


	/*------------------------------------------------------------------------*/
    /*  Tracking Code
    /*------------------------------------------------------------------------*/
	$wp_customize->add_section( 'wentasi_tracking_code' , array('priority'    => 82, 'title' => __( 'Tracking Codes (e.g: GA)', 'ft' ), 'description' => '', ) );
		// Message
		$wp_customize->add_setting( 'wentasi_tracking_code_message', array('sanitize_callback' => 'wentasi_sanitize_text') );
		$wp_customize->add_control( new Wentasi_Misc_Control( $wp_customize, 'wentasi_tracking_code_message',
			array(
				'section'     => 'wentasi_tracking_code',
				'type'        => 'custom_message',
				'description' => __( 'There is no tracking code option in the theme customizer and you should never add any custom tracking code into a theme option. Read this article for more detail : <a target="_blank" href="http://wptavern.com/why-you-should-never-add-analytics-code-to-your-wordpress-theme">http://wptavern.com/why-you-should-never-add-analytics-code-to-your-wordpress-theme</a>.', 'wentasi' )
			)
		));

	/*------------------------------------------------------------------------*/
    /*  Typography
    /*------------------------------------------------------------------------*/
    $wp_customize->add_section( 'wentasi_typography' , array('priority'    => 81, 'title' => __( 'Typography', 'ft' ), 'description' => '', ) );
		// Message
		$wp_customize->add_setting( 'wentasi_typography_message', array('sanitize_callback' => 'wentasi_sanitize_text') );
		$wp_customize->add_control( new Wentasi_Misc_Control( $wp_customize, 'wentasi_typography_message',
			array(
				'section'     => 'wentasi_typography',
				'type'        => 'custom_message',
				'description' => __( 'We are trying to make our theme codebase as clean and lightweight as possible. So for custom Typography (Google Fonts), this plugin will do better than us : <a target="_blank" href="https://wordpress.org/plugins/easy-google-fonts/">https://wordpress.org/plugins/easy-google-fonts/</a> ( Trusted by more than 500k users ). ', 'wentasi' )
			)
		));

}


/*------------------------------------------------------------------------*/
/*  Wentasi Sanitize Functions.
/*------------------------------------------------------------------------*/

function wentasi_sanitize_file_url( $file_url ) {
	$output = '';
	$filetype = wp_check_filetype( $file_url );
	if ( $filetype["ext"] ) {
		$output = esc_url( $file_url );
	}
	return $output;
}

function wentasi_hero_fullscreen_callback ( $control ) {
	if ( $control->manager->get_setting('wentasi_hero_fullscreen')->value() == '' ) {
        return true;
    } else {
        return false;
    }
}

function wentasi_sanitize_number( $input ) {
    return force_balance_tags( $input );
}

function wentasi_sanitize_hex_color( $color ) {
	if ( $color === '' ) {
		return '';
	}
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}
	return null;
}

function wentasi_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
		return 1;
    } else {
		return 0;
    }
}

function wentasi_sanitize_text( $string ) {
	return wp_kses_post( force_balance_tags( $string ) );
}

function wentasi_sanitize_html_input( $string ) {
	return wp_kses_allowed_html( $string );
}


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function wentasi_customize_preview_js() {
	wp_enqueue_script( 'wentasi_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'wentasi_customize_preview_js' );


