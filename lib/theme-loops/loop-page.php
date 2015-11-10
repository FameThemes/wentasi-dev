<?php /* Start loop */ ?>

<?php while (have_posts()) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single'); ?>>

	<div class="ft-fimg featured-image"><?php if ( has_post_thumbnail() ) : ?><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('main'); ?></a><?php endif; ?></div>

	<div class="ft-boxct clearfix">

		<header><h1 class="ft-ptitle entry-title"><?php the_title(); ?></h1></header>

		<div class="ft-meta"><div class="ft-bmeta"><span class="ft-author vcard author"><span class="fn"><?php the_author_posts_link(); ?></span></span> - <span class="ft-time"><time class="updated" datetime="<?php the_time('F, jS Y'); ?>"><?php the_time('F j, Y'); ?></time></span></div></div>
			
		<section class="ft-entry"><?php the_content(); ?></section>

		<div class="ft-breadcrumbs hidden"><?php echo ft_breadcrumbs(); ?></div>

	</div>

</article>
	
<?php endwhile; // End the loop ?>