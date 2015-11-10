<?php get_header(); ?>

<main id="main">

	<div id="content">

		<?php if (!have_posts()) : ?>
	
		<div class="ft-breadcrumbs hidden"><?php echo ft_breadcrumbs(); ?></div>

		<div class="ft-smptitle">

			<h1 class="ft-mptitle"><?php _e('Sorry, no results were found.', 'ft'); ?></h1>

		</div>

		<?php else: ?>

		<div class="ft-breadcrumbs hidden"><?php echo ft_breadcrumbs(); ?></div>

		<div class="ft-smptitle">

		<h1 class="ft-mptitle">

		<?php if (is_day()) : ?><?php printf(__('Daily Archives: %s', 'ft'), get_the_date()); ?>
		<?php elseif (is_month()) : ?><?php printf(__('Monthly Archives: %s', 'ft'), get_the_date('F Y')); ?>
		<?php elseif (is_year()) : ?><?php printf(__('Yearly Archives: %s', 'ft'), get_the_date('Y')); ?>
		<?php elseif (is_tag()) : ?><?php echo __('Posts Tagged', 'ft'); ?>: "<?php single_cat_title(); ?>"
		<?php elseif (is_author()) : ?>
		<?php if(get_query_var('author_name')) : $curauth = get_user_by('login', get_query_var('author_name')); else : $curauth = get_userdata(get_query_var('author')); endif; printf(__('Posts by: %s', 'ft'), $curauth->display_name); ?>
		<?php else : ?><?php _e('Category', 'ft'); ?> "<?php single_cat_title(); ?>"
		<?php endif; ?>

		</h1>

		</div>

		<?php if (is_day()) : ?>
		<?php elseif (is_month()) : ?>
		<?php elseif (is_year()) : ?>
		<?php elseif (is_tag()) : ?>
		<?php elseif (is_author()) : ?>

			<?php include get_template_directory() . '/lib/theme-functions/author-box-archive.php'; ?>
				
		<?php else : ?>
					
			<div class="ft-catdes"><?php echo category_description(); ?></div>

		<?php endif; ?>

		<?php get_template_part('lib/theme-loops/loop', 'archive'); ?>

		<?php endif; ?>
			
		<?php if ($wp_query->max_num_pages > 1) : ?><nav id="post-nav" class="post-nav-archives"><?php ft_pagination(); ?></nav>
				
		<?php endif; ?>

	</div>

</main>
		
<?php get_footer(); ?>