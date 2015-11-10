<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<link rel="profile" href="http://gmpg.org/xfn/11">	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php
$wentasi_topbar_sticky = get_theme_mod( 'wentasi_topbar_sticky' );
?>
<nav id="ft-topbar" class="ft-topbar<?php if ( $wentasi_topbar_sticky != 1 ) echo ' topbar-fixed'; ?>">

	<div class="wrapper clearfix">

		<div class="ft-menu span9">

			<div id="site-navigation">

			<a class="ft-jpbutton" href="#menu"><i class="icon-reorder"></i></a>

			<?php if ( has_nav_menu( 'primary_navigation' ) ): ?>
			<?php wp_nav_menu( array('theme_location' => 'primary_navigation','container' => false,'menu_class' => 'primenu','echo' => true,'before' => '','after' => '','link_before' => '','link_after' => '','depth' => 3,'items_wrap' => '<ul class="primenu">%3$s</ul>')); ?>
			<?php else: ?>
			<p class="ft-nomenus">Please Setup Your Menus : Dashboard -> Appearance -> Menus</p>
			<?php endif; ?>
			</div>
			
		</div>

		<div class="ft-icons span3 right">

			<ul>
				<?php
				$wentasi_social_rss       = get_theme_mod( 'wentasi_social_rss', get_bloginfo('rss2_url') );
				$wentasi_social_instagram = get_theme_mod( 'wentasi_social_instagram', '#' );
				$wentasi_social_facebook  = get_theme_mod( 'wentasi_social_facebook', '#' );
				$wentasi_social_twitter   = get_theme_mod( 'wentasi_social_twitter', '#' );
				$wentasi_social_google    = get_theme_mod( 'wentasi_social_google', '#' );
				?>
				<?php if ($wentasi_social_rss): ?><li><a target="_blank" href="<?php echo esc_url($wentasi_social_rss); ?>"><i class="icon-rss"></i></a></li><?php endif; ?>
				<?php if ($wentasi_social_twitter): ?><li><a target="_blank" href="<?php echo esc_url($wentasi_social_twitter) ?>"><i class="icon-twitter"></i></a></li><?php endif; ?>
				<?php if ($wentasi_social_facebook): ?><li><a target="_blank" href="<?php echo esc_url($wentasi_social_facebook); ?>"><i class="icon-facebook"></i></a></li><?php endif; ?>
				<?php if ($wentasi_social_google): ?><li><a target="_blank" href="<?php echo esc_url($wentasi_social_google); ?>"><i class="icon-google-plus"></i></a></li><?php endif; ?>
				<li><a class="search-bt" href="#"><i class="icon-search"></i></a></li>

			</ul>

			<div class="ft-search-hide"><?php get_search_form( true ); ?><div class="ft-shbg"></div></div>

		</div>

	</div>

</nav>
		

<section class="container clearfix">
	
<header id="header">

	<div class="row clearfix">
					
		<hgroup class="ft-logo">
			
			<?php
			$site_text_logo     = get_theme_mod( 'wentasi_site_text_logo', get_bloginfo('name') );
			$site_image_logo    = get_theme_mod( 'wentasi_site_image_logo' );
			$wentasi_logo_width = get_theme_mod( 'wentasi_logo_width', '105' );
			?>
			<?php if ( $site_image_logo ) : ?>
				<?php if(is_home() || is_front_page()) : ?>
						<h1 class="ct-logo">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
								<img<?php echo ($wentasi_logo_width != '') ? ' width="'.$wentasi_logo_width.'"' : ''; ?> src="<?php echo $site_image_logo; ?>" alt="<?php bloginfo('name'); ?>" />
								<span><?php echo $site_text_logo; ?></span>
							</a>
						</h1>
					<?php else: ?>
						<div class="ct-logo">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
								<img<?php echo ($wentasi_logo_width != '') ? ' width="'.$wentasi_logo_width.'"' : ''; ?> src="<?php echo $site_image_logo; ?>" alt="<?php bloginfo('name'); ?>" />
								<span><?php echo $site_text_logo; ?></span>
							</a>
						</div>
					<?php endif; ?>
			<?php else: ?>
					<?php if(is_home() || is_front_page()) : ?>
						<h1 class="ct-logo">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
								<?php $admin_email = get_bloginfo('admin_email'); 
								echo get_avatar( $admin_email, 105 ); ?>
								<span><?php echo $site_text_logo; ?></span>
							</a>
						</h1>
					<?php else: ?>
						<div class="ct-logo">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
								<?php $admin_email = get_bloginfo('admin_email'); 
								echo get_avatar( $admin_email, 105 ); ?>
								<span><?php echo $site_text_logo; ?></span>
							</a>
						</div>
					<?php endif; ?>
			<?php endif; ?>
						
		</hgroup>

	</div>
					
</header>








