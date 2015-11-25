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

add_action( 'wp_head', 'wp_typography_print_styles' );


/**
 * Automatic add Style to <head>.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function wp_typography_print_styles() {

    global $wp_typography_auto_apply;
    $google_fonts = array();
    $font_variants = array();
    $css = array();
    $scheme = is_ssl() ? 'https' : 'http';

    if ( ! empty( $wp_typography_auto_apply ) ) {
        foreach( $wp_typography_auto_apply as $k => $settings ) {
            if ( isset( $settings['data_type'] ) && $settings['data_type'] == 'option' ) {
                $data = get_option( $k, false );
            }else {
                $data = get_theme_mod( $k, false );
            }

            $data =  json_decode( $data, true );
            if ( ! is_array( $data ) ) {
                continue;
            }

            if ( isset( $data['font_url'] ) && is_array( $data['font'] ) ) {

                $google_fonts[ $data['font_id'] ] = $data['font'];
                if ( ! isset( $font_variants[ $data['font_id' ] ] ) || ! is_array( $font_variants[ $data['font_id' ] ] ) ) {
                    $font_variants[ $data['font_id' ] ] = array();
                }

                if ( in_array( $data['style'], $data['font']['font_weights'] )  ) {
                    $font_variants[ $data['font_id'] ][ $data['style'] ] = $data['style'] ;
                }
            }

            $css[] = wp_typography_css( $data['css'], array( $data['css_selector'], $settings['css_selector'] ) );

        }
    }

    //var_dump( $font_variants ); die();

    foreach( $google_fonts as $font_id => $font ){
        $name = str_replace( ' ', '+', $font['name'] );

        $variants = ( isset( $font_variants[ $font_id ] ) && ! empty( $font_variants[ $font_id ] ) ) ? $font_variants[ $font_id ] :  array( 'regular' );

        $url = $scheme."://fonts.googleapis.com/css?family={$name}:".join( $variants, ',' );

        if ( isset( $font['subsets'] ) ){
            $url .= '&subset='.join(',', $font['subsets'] );
        }

        echo "<link id='google-font-".esc_attr( $font_id )."' href='".esc_url( $url )."' rel='stylesheet' type='text/css'>";
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
function wp_typography_css( $css, $selector = array() ){
    if ( ! is_array( $css ) || ! $selector ){
        return false;
    }

    if ( isset( $css['font-family'] ) ) {
        $css['font-family'] = '"'.$css['font-family'].'"';
    }
    $code = '';
    if ( is_array( $selector ) ) {
        $selector = array_unique( $selector );
        $code .= join( "\n", $selector );
    } else {
        $code .= $selector;
    }

    $code .= " { \n";
    foreach( $css as $k => $v ){
        if ( $v ) {
            $code .="\t{$k}: {$v};\n";
        }
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
function wp_typography_helper_auto_apply( $setting_key, $css_selector = '',  $data_type = 'theme_mod' ){
    global $wp_typography_auto_apply;
    $wp_typography_auto_apply[ $setting_key ] = array(
        'key' => $setting_key,
        'css_selector' => $css_selector,
        'data_type' => $data_type,
    );
}


