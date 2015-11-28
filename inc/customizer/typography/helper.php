<?php
/**
 * Plugin Name: Customizer Typography
 * Plugin URI:  https://github.com/justintadlock/customizer-typography
 * Author:      Justin Tadlock
 * Author URI:  http://themehybrid.com
 * Description: Proof-of-concept and testing tool for building typography controls in the customizer.
 * Version:	1.0.0-dev
 * License:     GNU General Public License v2.0 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

# Register our customizer panels, sections, settings, and controls.
$GLOBALS['wp_typography_auto_apply'] = array();

add_action( 'wp_head', 'wentasi_typography_print_styles', 99 );


/**
 * Automatic add Style to <head>.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function wentasi_typography_print_styles() {

    global $wp_typography_auto_apply;
    $google_fonts = array();
    $font_variants = array();
    $css = array();
    $scheme = is_ssl() ? 'https' : 'http';

    if ( ! function_exists( 'wentasi_typography_get_fonts' ) ) {
        include_once dirname( __FILE__ ).'/typography.php';
    }

    $fonts = wentasi_typography_get_google_fonts();

    if ( ! empty( $wp_typography_auto_apply ) ) {
        foreach( $wp_typography_auto_apply as $k => $settings ) {

            if ( isset( $settings['data_type'] ) && $settings['data_type'] == 'option' ) {
                $data = get_option( $k, false );
            }else {
                $data = get_theme_mod( $k, false );
            }

            $data =  json_decode( $data, true );

            //var_dump( $data ); die();

            if ( ( ! $data || empty( $data ) ) && $settings['default'] ) {
                $data =  $settings['default'];
            }

            if ( ! is_array( $data ) ) {
                continue;
            }

            $data = wp_parse_args( $data , array(
                'font-family'     => '',
                'color'           => '',
                'font-style'      => '',
                'font-weight'      => '',
                'font-size'       => '',
                'line-height'     => '',
                'letter-spacing'  => '',
                'text-transform'  => '',
                'text-decoration' => '',
            ) );

            $data  = array_filter( $data  );

            if( empty( $data ) && is_array( $settings['default']  ) ) {
                $data = array_merge( $settings['default'], $data );
            }

            //var_dump( $data );

            $font_id =  false;
            if ( isset( $data ) && is_array( $data ) ) {
                if ( isset ( $data['font-family'] ) ) {
                    $font_id = sanitize_title( $data['font-family'] );
                }
            }

            if ( $font_id != ''  && isset( $fonts[ $font_id ] ) && $fonts[ $font_id ]['font_type'] == 'google' ) {
                $google_fonts[ $font_id ] = $fonts[ $font_id ];

                if ( ! isset( $font_variants[ $font_id ] ) || ! is_array( $font_variants[ $font_id ] ) ) {
                    $font_variants[ $font_id ] = array();
                }

                $style = '';
                if ( $data['font-weight'] ) {
                    $style .= $data['font-weight'];
                }

                if ( $data['font-style'] !== '' && $data['font-style']!= 'normal' ) {
                    $style .= $data['font-style'];
                }

                if ( in_array( $style, $fonts[ $font_id ]['font_weights'] )  ) {
                    $font_variants[ $font_id ][ $style ] = $style ;
                }
            }

            $css[] = wentasi_typography_css( $data, $settings['css_selector'] );

        }
    }

    $_fonts = array();
    $_subsets = array();

    foreach( $google_fonts as $font_id => $font ){
        $name = str_replace( ' ', '+', $font['name'] );
        $variants = ( isset( $font_variants[ $font_id ] ) && ! empty( $font_variants[ $font_id ] ) ) ? $font_variants[ $font_id ] :  array( 'regular' );
        $_fonts[ $font_id ] = "{$name}:".join( $variants, ',' );

        if ( isset( $font['subsets'] ) ){
            $_subsets = array_merge( $_subsets, $font['subsets'] );
        }
    }

    if ( count( $_fonts ) ) {
        $url = $scheme."://fonts.googleapis.com/css?family=".join( $_fonts, '|' );
        if( ! empty( $_subsets ) ) {
            $_subsets = array_unique( $_subsets );
            $url .= '&subset='.join(',', $_subsets );
        }
        echo "<link id='wp-typo-google-font' href='".esc_url( $url )."' rel='stylesheet' type='text/css'>";
    }

    echo "\n";
    echo '<style class="wp-typography-print-styles" type="text/css">'."\n".join(" \n ", $css )."\n".'</style>';
    echo "\n";

}

/**
 * Create CSS code
 *
 * @param $css
 * @param array $selector
 * @return bool|string
 */
function wentasi_typography_css( $css, $selector = array() ){
    if ( ! is_array( $css ) || ! $selector ){
        return false;
    }

    if ( isset( $css['font-family'] ) ) {
        $css['font-family'] = '"'.$css['font-family'].'"';
    }

    $base_px = apply_filters( 'root_typography_css_base_px', 16 ) ; // 16px;

    $code = '';
    if ( is_array( $selector ) ) {
        $selector = array_unique( $selector );
        $code .= join( "\n", $selector );
    } else {
        $code .= $selector;
    }

    $code .= " { \n";
    //var_dump( $v );
    foreach( $css as $k => $v ){
        if ( $v && ! is_array( $v ) ) {

            $code .="\t{$k}: {$v};\n";
        }
    }

    if ( isset ( $css['font-size']  ) && $css['font-size'] != '' ) {
        $rem = intval( $css['font-size'] )/$base_px;
        $code .="\tfont-size: {$rem}rem;\n";
    }

    $code .= " }";
    return $code;
}

/**
 * Register settings for auto apply css to <head>
 *
 * @param $setting_key
 * @param string $css_selector
 * @param string $data_type
 */
/**
 * @param $setting_key
 * @param string $css_selector
 * @param null $default
 * @param string $data_type
 *
 *
 * @default array(
 *  'font-family'     => '',
 *  'color'           => '',
 *  'font-style'      => '',
 *  'font-weight'     => '',
 *  'font-size'       => '',
 *  'line-height'     => '',
 *  'letter-spacing'  => '',
 *  'text-transform'  => '',
 *  'text-decoration' => '',
 *)
 */
function wentasi_typography_helper_auto_apply( $setting_key, $css_selector = '',  $default = null ,  $data_type = 'theme_mod' ){
    global $wp_typography_auto_apply;
    $wp_typography_auto_apply[ $setting_key ] = array(
        'key' => $setting_key,
        'css_selector' => $css_selector,
        'data_type' => $data_type,
        'default' => $default
    );
}


