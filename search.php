<?php get_header(); ?>

<main id="main">

	<div id="content" role="main"> 

		<?php if (!have_posts()) : ?>
	
		<div class="ft-breadcrumbs hidden"><?php echo ft_breadcrumbs(); ?></div>

		<div class="ft-smptitle">

			<h1 class="ft-mptitle"><?php _e('Search Results for', 'ft'); ?> "<?php echo get_search_query(); ?>"</h1>

			<p><?php _e('Sorry, no results were found. Please try again with another keyword.', 'ft'); ?></p>

		</div>

		<?php else: ?>
			
		<div class="ft-breadcrumbs hidden"><?php echo ft_breadcrumbs(); ?></div>

		<div class="ft-smptitle">

			<h1 class="ft-mptitle"><?php _e('Search Results for', 'ft'); ?> "<?php echo get_search_query(); ?>"</h1>

		</div>

		<?php get_template_part('lib/theme-loops/loop', 'search'); ?>

		<?php endif; ?>
			
		<?php if ($wp_query->max_num_pages > 1) : ?><nav id="post-nav" class="post-nav-archives"><?php ft_pagination(); ?></nav>
				
		<?php endif; ?>

	</div>

</main>

<?php get_footer(); ?>