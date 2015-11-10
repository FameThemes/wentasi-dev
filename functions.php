<?php

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/*-----------------------------------------------------------------------------------*/
/*	Theme Setup
/*-----------------------------------------------------------------------------------*/

function ft_setup() {
	// Add language supports.
	load_theme_textdomain('ft');

	// Add post-formats
	add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));
	add_post_type_support('post', 'post-formats');
	
	// Add post thumbnail supports. http://codex.wordpress.org/Post_Thumbnails
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_image_size( 'main', 1024, 450, true );
	add_image_size( 'related', 450, 300, true );
	add_theme_support( 'custom-background', apply_filters( 'wentasi_custom_background_args', array(
		'default-color'      => 'f6f6f6',
		'default-image' 	 => '',
	) ) );


	// Add menu supports. http://codex.wordpress.org/Function_Reference/register_nav_menus
	add_theme_support('menus');
	register_nav_menus(array(
		'primary_navigation' => __('Primary Navigation', 'ft')
	));	

}
add_action('after_setup_theme', 'ft_setup');


/*-----------------------------------------------------------------------------------*/
/*	Content Width
/*-----------------------------------------------------------------------------------*/

$content_width = 1024;

if ( is_page_template('page-full.php') ) $content_width = 1024;


/*-----------------------------------------------------------------------------------*/
/*	Theme Javascripts
/*-----------------------------------------------------------------------------------*/

function ft_theme_js() {

	if(!is_admin()) {
		wp_register_script( 'jpanel', get_template_directory_uri() . '/js/jpanelmenu.js', array('jquery'), '1.3.0', true );
		wp_register_script( 'fitvids', get_template_directory_uri() . '/js/fitvids.js', array('jquery'), '1.0.0', true );
		wp_register_script( 'custom', get_template_directory_uri() . '/js/custom.js', array('jquery'), '1.0.0', false );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jpanel' );
		wp_enqueue_script( 'fitvids' );
		wp_enqueue_script( 'custom' );
		
		if ( is_singular() ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

}
add_action('wp_enqueue_scripts', 'ft_theme_js');


/*-----------------------------------------------------------------------------------*/
/*	Theme Styles
/*-----------------------------------------------------------------------------------*/

function ft_theme_style()  {  

	wp_register_style( 'fontawesome-style', get_template_directory_uri() . '/css/font-awesome.css', array(), '3.2', 'all' );
	wp_register_style( 'typography-style', get_template_directory_uri() . '/css/typography.css', array(), '1.0', 'all' ); 

	// Load our main stylesheet.
	$wentasi_google_fonts = get_theme_mod( 'wentasi_google_fonts' );
	if ( $wentasi_google_fonts != 1 ) wp_enqueue_style( 'wentasi-fonts', wentasi_fonts_url(), array(), null );
	wp_enqueue_style( 'wentasi-style', get_stylesheet_uri() ); 	
	wp_enqueue_style( 'fontawesome-style' );  
	wp_enqueue_style( 'typography-style' );

	$wentasi_header_bg       = get_theme_mod( 'wentasi_header_bg', '222222' );
	$wentasi_primary_color   = get_theme_mod( 'wentasi_primary_color', 'f67156' );
	$wentasi_secondary_bg    = get_theme_mod( 'wentasi_secondary_bg', 'ff6' );
	$wentasi_secondary_color = get_theme_mod( 'wentasi_secondary_color', '444' );
	$wentasi_custom_css = "
		.ft-topbar { background-color: #{$wentasi_header_bg}; }
		a { color: #{$wentasi_primary_color}; }
		input[type=\"button\"],input[type=\"submit\"],input[type=\"button\"]:hover,input[type=\"submit\"]:hover,.hentry a.more-link,
		#post-nav .pagination span,#post-nav .pagination a,.ft-share ul li a:hover,.widget_calendar table#wp-calendar thead > tr > th,
		.widget_calendar table > tbody > tr td#today,a:hover.comment-reply-link { background-color: #{$wentasi_primary_color}; }
		.back-top span { background-color: #{$wentasi_primary_color}; }
		.hentry .ft-bmeta { background: #{$wentasi_secondary_bg}; }
		.hentry .ft-bmeta, .hentry .ft-bmeta a, .hentry .ft-bmeta a:hover { color: #{$wentasi_secondary_color}; }
	";
	wp_add_inline_style( 'wentasi-style', $wentasi_custom_css );

}  
add_action( 'wp_enqueue_scripts', 'ft_theme_style' ); 

function ft_admin_scripts() {
	wp_enqueue_style('ft_admincss', get_template_directory_uri() . '/css/theme-info.css');
}

add_action('admin_enqueue_scripts', 'ft_admin_scripts');

if ( ! function_exists( 'wentasi_fonts_url' ) ) :
/**
 * Register default Google fonts
 */
function wentasi_fonts_url() {
    $fonts_url = '';
 	
 	/* Translators: If there are characters in your language that are not
    * supported by merriweather, translate this to 'off'. Do not translate
    * into your own language.
    */
    $merriweather = _x( 'on', 'Open Sans font: on or off', 'ft' );

    /* Translators: If there are characters in your language that are not
    * supported by Raleway, translate this to 'off'. Do not translate
    * into your own language.
    */
    $raleway = _x( 'on', 'Raleway font: on or off', 'ft' );
 
    if ( 'off' !== $raleway || 'off' !== $merriweather ) {
        $font_families = array();
 
        if ( 'off' !== $raleway ) {
            $font_families[] = 'Raleway:400,500,700,800';
        }
 
        if ( 'off' !== $merriweather ) {
            $font_families[] = 'Merriweather';
        }
 
        $query_args = array(
            'family' => urlencode( implode( '|', $font_families ) ),
            'subset' => urlencode( 'latin,latin-ext' ),
        );
 
        $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
    }
 
    return esc_url_raw( $fonts_url );
}
endif;

// Custom CSS :
function wentasi_custom_css() {
	$custom_css = get_theme_mod( 'wentasi_custom_css' );
	if ( $custom_css != '' ) {
		echo '<style id=\'wentasi-customizer-css\' type=\'text/css\'>';
		echo $custom_css;
		echo '</style>';
	}
}
add_action( 'wp_head', 'wentasi_custom_css' );

/*-----------------------------------------------------------------------------------*/
/*	Widgets Register
/*-----------------------------------------------------------------------------------*/

if(function_exists('register_sidebar')) {
	register_sidebar(array(
		'id'            => 'footer_widgets',
		'name'          => 'Footer Widgets',
		'before_widget' => '<div id="%1$s" class="row widget %2$s"><div class="widget-section">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

}


/*-----------------------------------------------------------------------------------*/
/*	Browser Classes
/*-----------------------------------------------------------------------------------*/

add_filter('body_class','browser_body_class');
function browser_body_class($classes) {
    global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
	if($is_lynx) $classes[]       = 'lynx';
	elseif($is_gecko) $classes[]  = 'gecko';
	elseif($is_opera) $classes[]  = 'opera';
	elseif($is_NS4) $classes[]    = 'ns4';
	elseif($is_safari) $classes[] = 'safari';
	elseif($is_chrome) $classes[] = 'chrome';
	elseif($is_IE) $classes[]     = 'ie';
	else $classes[]               = 'unknown';
	if($is_iphone) $classes[]     = 'iphone';
    return $classes;
}

/*-----------------------------------------------------------------------------------*/
/* Pinterest
/*-----------------------------------------------------------------------------------*/

function catch_that_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if(empty($first_img)){ //Defines a default image
    $first_img = "/images/default.jpg";
  }
  return $first_img;
}

/*-----------------------------------------------------------------------------------*/
/* Auto Theme Update
/*-----------------------------------------------------------------------------------*/

// Includes the files needed for the theme updater
if ( !class_exists( 'EDD_Theme_Updater_Admin' ) ) {
	include( dirname( __FILE__ ) . '/inc/dashboard/dashboard.php' );
}

// Loads the updater classes
$updater = new EDD_Theme_Updater_Admin(

	// Config settings
	$config = array(
		'remote_api_url' => 'http://beta.famethemes.com', // Site where EDD is hosted
		'item_name'      => 'Wentasi', // Name of theme
		'theme_slug'     => 'wentasi', // Theme slug
		'version'        => '2.0.1', // The current version of this theme
		'author'         => 'FameThemes', // The author of this theme
		'download_id'    => '110', // Optional, used for generating a license renewal link
		'renew_url'      => '' // Optional, allows for a custom license renewal link
	),

	// Strings
	$strings = array(
		'theme-license'             => __( 'Theme License', 'edd-theme-updater' ),
		'enter-key'                 => __( 'Enter your theme license key.', 'edd-theme-updater' ),
		'license-key'               => __( 'License Key', 'edd-theme-updater' ),
		'license-action'            => __( 'License Action', 'edd-theme-updater' ),
		'deactivate-license'        => __( 'Deactivate License', 'edd-theme-updater' ),
		'activate-license'          => __( 'Activate License', 'edd-theme-updater' ),
		'status-unknown'            => __( 'License status is unknown.', 'edd-theme-updater' ),
		'license-unknown'           => __( 'License status is unknown.', 'edd-theme-updater' ),
		'renew'                     => __( 'Renew?', 'edd-theme-updater' ),
		'unlimited'                 => __( 'unlimited', 'edd-theme-updater' ),
		'license-key-is-active'     => __( 'License key is active.', 'edd-theme-updater' ),
		'expires%s'                 => __( 'Expires %s.', 'edd-theme-updater' ),
		'%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'edd-theme-updater' ),
		'license-key-expired-%s'    => __( 'License key expired %s.', 'edd-theme-updater' ),
		'license-key-expired'       => __( 'License key has expired.', 'edd-theme-updater' ),
		'license-keys-do-not-match' => __( 'License keys do not match.', 'edd-theme-updater' ),
		'license-is-inactive'       => __( 'License is inactive.', 'edd-theme-updater' ),
		'license-key-is-disabled'   => __( 'License key is disabled.', 'edd-theme-updater' ),
		'site-is-inactive'          => __( 'Site is inactive.', 'edd-theme-updater' ),
		'license-status-unknown'    => __( 'License status is unknown.', 'edd-theme-updater' ),
		'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'edd-theme-updater' ),
		'update-available'          => __('<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 'edd-theme-updater' )
	)

);

/*-----------------------------------------------------------------------------------*/
/* Theme Functions
/*-----------------------------------------------------------------------------------*/
include_once('lib/theme-functions/theme-pagination.php');
include_once('lib/theme-functions/theme-metaboxes.php');
include_once('lib/theme-functions/ft-metabox.php');
include_once('lib/theme-functions/breadcrumbs.php');

// Widgets

include_once('lib/theme-widgets/widget-flickrphotos.php');
include_once('lib/theme-widgets/widget-popularposts.php');
include_once('lib/theme-widgets/widget-recentposts.php');
include_once('lib/theme-widgets/widget-newsletter.php');





