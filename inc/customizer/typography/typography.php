<?php

function sanitize_typography_field( $value ){

    if( is_string( $value ) ) {
        $value = json_decode( $value, true );
    }

    if ( ! is_array( $value ) ) {
        return false;
    }

    $value = wp_parse_args( $value, array(
        'css'           => array(),
        'css_selector'  => '',
        'font_id'       => '',
        'font'          => array(),
        'style'         => '',
        'font_url'      => '',
    ) );

    if ( is_array( $value['css'] )  ) {
        foreach( $value['css'] as $k => $v ){
            $value['css'][ $k ] =  sanitize_text_field( $v );
        }
    }

    $value['font_id']        = sanitize_text_field( $value['font_id'] );
    $value['style']          = sanitize_text_field( $value['style'] );
    $value['font_url']       = sanitize_text_field( $value['font_url'] );
    $value['css_selector']   = sanitize_text_field( $value['css_selector'] );

    return json_encode( $value );
}


/**
 * Typography control class.
 *
 * @since  1.0.0
 * @access public
 */
class WP_Customize_Typography_Control extends WP_Customize_Control {

    /**
     * The type of customize control being rendered.
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    public $type = 'typography';

    /**
     * Array
     *
     * @since  1.0.0
     * @access public
     * @var    string
     */
    public $l10n = array();

    /**
     * CSS selector
     *
     * @var string
     */
    public $css_selector ='';

    /**
     * Set up our control.
     *
     * @since  1.0.0
     * @access public
     * @param  object  $manager
     * @param  string  $id
     * @param  array   $args
     * @return void
     */
    public function __construct( $manager, $id, $args = array() ) {

        // Let the parent class do its thing.
        parent::__construct( $manager, $id, $args );

        // Make sure we have labels.
        $this->l10n = wp_parse_args(
            $this->l10n,
            array(
                'family'      => esc_html__( 'Font Family', 'ctypo' ),
                'option_default'      => esc_html__( 'Default', 'ctypo' ),
                'size'        => esc_html__( 'Font Size',   'ctypo' ),
                'style'       => esc_html__( 'Font Weight/Style',  'ctypo' ),
                'line_height' => esc_html__( 'Line Height', 'ctypo' ),
                'text_decoration' => esc_html__( 'Text Decoration', 'ctypo' ),
                'letter_spacing' => esc_html__( 'Letter Spacing', 'ctypo' ),
                'text_transform' => esc_html__( 'Text Transform', 'ctypo' ),
                'color' => esc_html__( 'Color', 'ctypo' ),

            )
        );

        $this->css_selector = isset( $args['css_selector'] ) ? $args['css_selector'] : '';
    }


    /**
     * Get url of any dir
     *
     * @param string $file full path of current file in that dir
     * @return string
     */
    public static function get_url( $file = '' ){
        if ( ! $file ) {
            $file = __FILE__;
        }
        if ( 'WIN' === strtoupper( substr( PHP_OS, 0, 3 ) ) ) {
            // Windows
            $content_dir = str_replace( '/', DIRECTORY_SEPARATOR, WP_CONTENT_DIR );
            $content_url = str_replace( $content_dir, WP_CONTENT_URL, trailingslashit( dirname( $file  ) ) );
            $url = str_replace( DIRECTORY_SEPARATOR, '/', $content_url );
        } else {
            $url = str_replace(
                array( WP_CONTENT_DIR, WP_PLUGIN_DIR ),
                array( WP_CONTENT_URL, WP_PLUGIN_URL ),
                trailingslashit( dirname( $file  ) )
            );
        }
        return set_url_scheme( $url );
    }

    public static function get_default_fonts() {

        // Declare default font list
        $font_list = array(
            'Arial'               => array( 'weights' => array( '400', '400italic', '700', '700italic' ) ),
            'Century Gothic'      => array( 'weights' => array( '400', '400italic', '700', '700italic' ) ),
            'Courier New'         => array( 'weights' => array( '400', '400italic', '700', '700italic' ) ),
            'Georgia'             => array( 'weights' => array( '400', '400italic', '700', '700italic' ) ),
            'Helvetica'           => array( 'weights' => array( '400', '400italic', '700', '700italic' ) ),
            'Impact'              => array( 'weights' => array( '400', '400italic', '700', '700italic' ) ),
            'Lucida Console'      => array( 'weights' => array( '400', '400italic', '700', '700italic' ) ),
            'Lucida Sans Unicode' => array( 'weights' => array( '400', '400italic', '700', '700italic' ) ),
            'Palatino Linotype'   => array( 'weights' => array( '400', '400italic', '700', '700italic' ) ),
            'sans-serif'          => array( 'weights' => array( '400', '400italic', '700', '700italic' ) ),
            'serif'               => array( 'weights' => array( '400', '400italic', '700', '700italic' ) ),
            'Tahoma'              => array( 'weights' => array( '400', '400italic', '700', '700italic' ) ),
            'Trebuchet MS'        => array( 'weights' => array( '400', '400italic', '700', '700italic' ) ),
            'Verdana'             => array( 'weights' => array( '400', '400italic', '700', '700italic' ) ),
        );

        // Build font list to return
        $fonts = array();
        foreach ( $font_list as $font => $attributes ) {

            // Create a font array containing it's properties and add it to the $fonts array
            $atts = array(
                'name'         => $font,
                'font_type'    => 'default',
                'font_weights' => $attributes['weights'],
                'subsets'      => array(),
                'url'         => '',
            );

            // Add this font to all of the fonts
            $id           = strtolower( str_replace( ' ', '_', $font ) );
            $fonts[ $id ] = $atts;
        }

        // Filter to allow us to modify the fonts array before saving the transient

        return $fonts;

    }


    /**
     * Returns the available fonts.  Fonts should have available weights, styles, and subsets.
     *
     * @todo Integrate with Google fonts.
     *
     * @since  1.0.0
     * @access public
     * @return array
     */
    static function get_google_fonts(){
        /**
         * Pull in raw file from the WordPress subversion
         * repository as a last resort.
         *
         */
        $fonts_from_repo = wp_remote_fopen( self::get_url(). "google-fonts.json", array( 'sslverify' => false ) );
        $json            = $fonts_from_repo;

        $font_output = json_decode( $json, true );

        $fonts = array();

        $scheme = is_ssl() ? 'https' : 'http';

        foreach ( $font_output['items'] as $item ) {

            $name = str_replace( ' ', '+', $item['family'] );

            $url = $scheme."://fonts.googleapis.com/css?family={$name}:".join( $item['variants'], ',' );
            if ( isset( $item['subsets'] ) ){
                $url .= '&subset='.join(',', $item['subsets'] );
            }

            $atts = array(
                'name'         => $item['family'],
                'category'     => $item['category'],
                'font_type'    => 'google',
                'font_weights' => $item['variants'],
                'subsets'      => $item['subsets'],
                'url'          => $url
            );

            // Add this font to the fonts array
            $id           = sanitize_title( $item['family'] );
            $fonts[ $id ] = $atts;
        }

        return $fonts;
    }

    public static function get_fonts(){
        //delete_transient( 'wp_typography_fonts' ); // for debug
        if ( false === ( $fonts = get_transient( 'wp_typography_fonts' ) ) ) {
            $fonts = array_merge( self::get_default_fonts(), self::get_google_fonts() );
            set_transient( 'wp_typography_fonts', $fonts, 24 * HOUR_IN_SECONDS );
        }
        return $fonts;
    }

    public static function get_font_by_id( $id ){
        $id = sanitize_title( $id );
        if ( ! $id ) {
            return false;
        }
        $fonts = self::get_fonts();
        if ( isset( $fonts[ $id ] ) ) {
            return $fonts[ $id ];
        }
        return false;
    }


    /**
     * Enqueue scripts/styles.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function enqueue() {
        $uri = $this->get_url();

        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_style( 'wp-color-picker' );

        wp_localize_script('jquery', 'typographyWebfonts',  $this->get_fonts() );
        wp_localize_script('jquery', 'fontStyleLabels', array(
            '100' => __( 'Thin 100' ),
            '100italic' => __( 'Thin 100 Italic' ),
            '200' => __( 'Extra-Light 200' ),
            '200italic' => __( 'Extra-Light 200 Italic' ),
            '300' => __( 'Light 300' ),
            '300italic' => __( 'Light 300 Italic' ),
            '400' => __( 'Normal 400' ),
            '400italic' => __( 'Normal 400 Italic' ),
            'regular' => __( 'Normal' ),
            'italic' => __( 'Normal Italic' ),
            '500' => __( 'Medium 500' ),
            '500italic' => __( 'Medium 500 Italic' ),
            '600' => __( 'Semi-Bold 600' ),
            '600italic' => __( 'Semi-Bold 600 Italic' ),
            '700' => __( 'Bold 700' ),
            '700italic' => __( 'Bold 700 Italic' ),
            '800' => __( ' Extra-Bold 800' ),
            '800italic' => __( ' Extra-Bold 800 Italic' ),
            '700italic' => __( 'Bold 700 Italic' ),
            '900' => __( 'Ultra-Bold 900' ),
            '900italic' => __( 'Ultra-Bold 900 Italic' ),
        ) );

        wp_register_script( 'typography-customize-controls', esc_url( $uri . 'js/typography-controls.js' ), array( 'customize-controls' ) );
        wp_register_style( 'typography-customize-controls', esc_url( $uri . 'css/typography-controls.css' ) );
        wp_enqueue_script('typography-customize-controls');
        wp_enqueue_style('typography-customize-controls');

    }

    /**
     * Add custom parameters to pass to the JS via JSON.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function to_json() {
        parent::to_json();

        // Loop through each of the settings and set up the data for it.
        $this->json['value']         = is_array( $this->value() ) ?  json_encode( $this->value() ) :  $this->value() ;
        $this->json['labels']        = $this->l10n;
        $this->json['css_selector']      = $this->css_selector;

    }

    /**
     * Underscore JS template to handle the control's output.
     *
     * @since  1.0.0
     * @access public
     * @return void
     */
    public function content_template() {

        //  <# console.log( data ) #>
        ?>
        <div class="typography-wrap">

           <div class="typography-header">
               <# if ( data.label ) { #>
               <span class="customize-control-title">{{ data.label }}</span>
               <# } #>

               <# if ( data.description ) { #>
                   <span class="description customize-control-description">{{{ data.description }}}</span>
               <# } #>

           </div>

            <div class="typography-settings">



                <ul>
                    <li class="typography-font-family">
                        <span class="customize-control-title">{{ data.labels.family }}</span>
                        <select class="font-family select-typo-font-families"></select>
                    </li>

                    <li class="typography-font-style">
                        <# if ( data.labels.style ) { #>
                        <span class="customize-control-title">{{ data.labels.style }}</span>
                        <# } #>
                        <select class="font-style"></select>
                    </li>

                    <li class="typography-font-size has-unit">
                        <span class="customize-control-title">{{ data.labels.size  }}</span>
                        <input class="unit-value font-size" type="number" min="1" />
                        <select class="unit font-size-unit">
                            <option value="px">px</option>
                            <option value="em">em</option>
                            <option value="rem">rem</option>
                        </select>
                    </li>

                    <li class="typography-line-height has-unit">
                        <span class="customize-control-title">{{ data.labels.line_height }}</span>
                        <input class="unit-value line-height" type="number" min="1" />
                        <select class="unit line-height-unit">
                            <option value="px">px</option>
                            <option value="em">em</option>
                            <option value="rem">rem</option>
                        </select>
                    </li>


                    <li class="typography-letter-spacing has-unit">
                        <span class="customize-control-title">{{ data.labels.letter_spacing }}</span>
                        <input class="unit-value letter-spacing" type="number" />
                        <select class="unit letter-spacing-unit">
                            <option value="px">px</option>
                            <option value="em">em</option>
                            <option value="rem">rem</option>
                        </select>
                    </li>

                    <li class="typography-text-decoration">
                        <span class="customize-control-title">{{ data.labels.text_decoration }}</span>
                        <select class="text-decoration">
                            <option value=""><?php esc_attr_e( 'Default' ); ?></option>
                            <option value="none"><?php esc_attr_e( 'None' ); ?></option>
                            <option value="overline"><?php esc_attr_e( 'Overline' ); ?></option>
                            <option value="underline"><?php esc_attr_e( 'Underline' ); ?></option>
                            <option value="line-through"><?php esc_attr_e( 'Line through' ); ?></option>
                        </select>
                    </li>

                    <li class="typography-text-transform">
                        <span class="customize-control-title">{{ data.labels.text_transform }}</span>
                        <select class="text-transform" >
                            <option value=""><?php esc_attr_e( 'Default' ); ?></option>
                            <option value="none"><?php esc_attr_e( 'None' ); ?></option>
                            <option value="uppercase"><?php esc_attr_e( 'Uppercase' ); ?></option>
                            <option value="lowercase"><?php esc_attr_e( 'Lowercase' ); ?></option>
                            <option value="capitalize"><?php esc_attr_e( 'Capitalize' ); ?></option>
                        </select>
                    </li>

                    <li class="typography-text-transform typography-half-">
                        <span class="customize-control-title">{{ data.labels.color }}</span>
                        <input type="text" class="text-color" />
                    </li>

                </ul>
                <a href="#" class="typography-close"><?php _e( 'Close' ); ?></a>
            </div>
        </div>
    <?php
    }

}