<?php

/**
 * Sanitize typography fields
 *
 * @param $value
 * @return bool|mixed|string|void
 */
function wentasi_sanitize_typography_field( $value ){

    if( is_string( $value ) ) {
        $value = json_decode( $value, true );
    }

    if ( ! is_array( $value ) ) {
        return false;
    }

    foreach( $value as $k => $v ){
        $value[ strtolower( $k ) ] =  sanitize_text_field( $v );
    }

    $value =  array_filter( $value );
    return json_encode( $value );
}



function wentasi_typography_get_default_fonts() {

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
            'name'          => $font,
            'font_type'     => 'default',
            'font_weights'  => $attributes['weights'],
            'subsets'       => array(),
            'url'           => '',
        );

        // Add this font to all of the fonts
        $id           = sanitize_title( $font );
        $fonts[ $id ] = $atts;
    }

    // Filter to allow us to modify the fonts array before saving the transient

    return apply_filters( 'wentasi_typography_get_default_fonts', $fonts );

}


function wentasi_typography_get_google_fonts(){
    /**
     * Pull in raw file from the WordPress subversion
     * repository as a last resort.
     *
     */
    $font_output  = include dirname( __FILE__ ).'/google-fonts.php';

    $fonts = array();

    $scheme = is_ssl() ? 'https' : 'http';
    if ( is_array( $font_output ) ) {
        foreach ( $font_output['items'] as $item ) {

            $name = str_replace(' ', '+', $item['family']);

            $url = $scheme . "://fonts.googleapis.com/css?family={$name}:" . join($item['variants'], ',');
            if ( isset( $item['subsets'] ) ) {
                $url .= '&subset=' . join(',', $item['subsets']);
            }

            $atts = array(
                'name'          => $item['family'],
                'category'      => $item['category'],
                'font_type'     => 'google',
                'font_weights'  => $item['variants'],
                'subsets'       => $item['subsets'],
                'url'           => $url
            );

            // Add this font to the fonts array
            $id = sanitize_title( $item['family'] );
            $fonts[$id] = $atts;
        }
    }

    return apply_filters( 'wentasi_typography_get_google_fonts', $fonts );
}

function wentasi_typography_get_fonts(){
    delete_transient( 'wp_typography_fonts' ); // for debug
    if ( false === ( $fonts = get_transient( 'wp_typography_fonts' ) ) ) {
        $fonts = array_merge( wentasi_typography_get_default_fonts(), wentasi_typography_get_google_fonts() );
        set_transient( 'wp_typography_fonts', $fonts, 24 * HOUR_IN_SECONDS );
    }
    return $fonts;
}



if ( class_exists( 'WP_Customize_Control' ) ) {

    /**
     * Typography control class.
     *
     * @since  1.0.0
     * @access public
     */
    class Wentasi_Customize_Typography_Control extends WP_Customize_Control {

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
         * Settings fields
         * @var array
         */
        public $fields = array();

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
                    'family'            => esc_html__( 'Font Family', 'ft' ),
                    'option_default'    => esc_html__( 'Default', 'ft' ),
                    'size'              => esc_html__( 'Font Size (px)',   'ft' ),
                    'style'             => esc_html__( 'Font Weight/Style',  'ft' ),
                    'line_height'       => esc_html__( 'Line Height (px)', 'ft' ),
                    'text_decoration'   => esc_html__( 'Text Decoration', 'ft' ),
                    'letter_spacing'    => esc_html__( 'Letter Spacing (px)', 'ft' ),
                    'text_transform'    => esc_html__( 'Text Transform', 'ft' ),
                    'color'             => esc_html__( 'Color', 'ft' ),
                )
            );

            $this->css_selector = isset( $args['css_selector'] ) ? $args['css_selector'] : '';
            if ( ! isset( $args['fields'] ) ) {
                $args['fields'] = array();
            }

            $this->fields = $args['fields'];

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

            $value = $this->value();

            $fields = array();
            foreach( $this->fields as $k => $value ){
                $fields[ str_replace( '-', '_', $k ) ] = true;
            }

            // Default value
            if ( ! is_array( $value ) ){
                $value = $this->fields;
            }

            $fields = wp_parse_args( $fields, array(
                'font_family'     => false,
                'color'           => false,
                'font_style'      => false,
                'font_size'       => false,
                'line_height'     => false,
                'letter_spacing'  => false,
                'text_transform'  => false,
                'text_decoration' => false,
            ) );

            // Loop through each of the settings and set up the data for it.
            //$this->json['value']         = is_array( $this->value() ) ?  json_encode( $this->value() ) :  $this->value() ;
            $this->json['value']         = json_encode( $value ) ;
            $this->json['labels']        = $this->l10n;
            $this->json['css_selector']  = $this->css_selector;
            $this->json['fields']        = $fields;

        }


        /**
         * Get url of any dir
         *
         * @param string $file full path of current file in that dir
         * @return string
         */
        public static function get_url( ){
            return  get_template_directory_uri().'/inc/customizer/typography/';
        }

        public static function get_default_fonts() {
            return wentasi_typography_get_default_fonts();
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
            return wentasi_typography_get_google_fonts();
        }

        public static function get_fonts(){
            return wentasi_typography_get_fonts();
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
                '100'       => __( 'Thin 100', 'ft' ),
                '100italic' => __( 'Thin 100 Italic', 'ft' ),
                '200'       => __( 'Extra-Light 200' ),
                '200italic' => __( 'Extra-Light 200 Italic', 'ft' ),
                '300'       => __( 'Light 300' ),
                '300italic' => __( 'Light 300 Italic', 'ft' ),
                '400'       => __( 'Normal 400' ),
                '400italic' => __( 'Normal 400 Italic', 'ft' ),
                'regular'   => __( 'Normal' ),
                'italic'    => __( 'Normal Italic', 'ft' ),
                '500'       => __( 'Medium 500' ),
                '500italic' => __( 'Medium 500 Italic', 'ft' ),
                '600'       => __( 'Semi-Bold 600' ),
                '600italic' => __( 'Semi-Bold 600 Italic', 'ft' ),
                '700'       => __( 'Bold 700', 'ft' ),
                '700italic' => __( 'Bold 700 Italic', 'ft' ),
                '800'       => __( ' Extra-Bold 800' ),
                '800italic' => __( ' Extra-Bold 800 Italic', 'ft' ),
                '900'       => __( 'Ultra-Bold 900', 'ft' ),
                '900italic' => __( 'Ultra-Bold 900 Italic', 'ft' ),
            ) );

            wp_register_script( 'typography-customize-controls', esc_url( $uri . 'js/typography-controls.js' ), array( 'customize-controls' ) );
            wp_register_style( 'typography-customize-controls', esc_url( $uri . 'css/typography-controls.css' ) );
            wp_enqueue_script('typography-customize-controls');
            wp_enqueue_style('typography-customize-controls');

        }


        /**
         * Underscore JS template to handle the control's output.
         *
         * @since  1.0.0
         * @access public
         * @return void
         */
        public function content_template() {

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
                        <# if ( data.fields.font_family ) { #>
                        <li class="typography-font-family">
                            <span class="customize-control-title">{{ data.labels.family }}</span>
                            <select class="font-family select-typo-font-families"></select>
                        </li>
                        <# } #>

                        <# if ( data.fields.font_family && data.fields.font_style ) { #>
                        <li class="typography-font-style typography-half">
                            <span class="customize-control-title">{{ data.labels.style }}</span>
                            <select class="font-style"></select>
                        </li>
                        <# } #>

                        <# if ( data.fields.font_style ) { #>
                        <li class="typography-font-size typography-half right">
                            <span class="customize-control-title">{{ data.labels.size  }}</span>
                            <input class="unit-value font-size" placeholder="<?php esc_attr_e( 'Default', 'ft' ); ?>" type="number" min="1" />
                        </li>
                        <# } #>

                        <# if ( data.fields.line_height ) { #>
                        <li class="typography-line-height first typography-half">
                            <span class="customize-control-title">{{ data.labels.line_height }}</span>
                            <input class="unit-value line-height" placeholder="<?php esc_attr_e( 'Default', 'ft' ); ?>" type="number" min="1" />
                        </li>
                        <# } #>

                        <# if ( data.fields.letter_spacing ) { #>
                        <li class="typography-letter-spacing typography-half right">
                            <span class="customize-control-title">{{ data.labels.letter_spacing }}</span>
                            <input class="unit-value letter-spacing" placeholder="<?php esc_attr_e( 'Default', 'ft' ); ?>" type="number" />
                        </li>
                        <# } #>

                        <# if ( data.fields.text_decoration ) { #>
                        <li class="typography-text-decoration clr">
                            <span class="customize-control-title">{{ data.labels.text_decoration }}</span>
                            <select class="text-decoration">
                                <option value=""><?php esc_attr_e( 'Default', 'ft' ); ?></option>
                                <option value="none"><?php esc_attr_e( 'None', 'ft' ); ?></option>
                                <option value="overline"><?php esc_attr_e( 'Overline', 'ft' ); ?></option>
                                <option value="underline"><?php esc_attr_e( 'Underline', 'ft' ); ?></option>
                                <option value="line-through"><?php esc_attr_e( 'Line through', 'ft' ); ?></option>
                            </select>
                        </li>
                        <# } #>

                        <# if ( data.fields.text_transform ) { #>
                        <li class="typography-text-transform clr">
                            <span class="customize-control-title">{{ data.labels.text_transform }}</span>
                            <select class="text-transform" >
                                <option value=""><?php esc_attr_e( 'Default', 'ft' ); ?></option>
                                <option value="none"><?php esc_attr_e( 'None', 'ft' ); ?></option>
                                <option value="uppercase"><?php esc_attr_e( 'Uppercase', 'ft' ); ?></option>
                                <option value="lowercase"><?php esc_attr_e( 'Lowercase', 'ft' ); ?></option>
                                <option value="capitalize"><?php esc_attr_e( 'Capitalize', 'ft' ); ?></option>
                            </select>
                        </li>
                        <# } #>

                        <# if ( data.fields.color ) { #>
                        <li class="typography-text-transform clr">
                            <span class="customize-control-title">{{ data.labels.color }}</span>
                            <input type="text" class="text-color" />
                        </li>
                        <# } #>

                    </ul>
                </div>
            </div>
        <?php
        }

    }
}
