<?php /* Start loop */ ?>

<?php while (have_posts()) : the_post(); ?>

<?php $format = get_post_format(); if (false === $format) $format = 'standard'; ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					
	<?php if ($format == 'gallery') { ?>

		<?php get_template_part( 'lib/theme-loops/formats/format', 'gallery'); ?>

	<?php } elseif ($format == 'video') { ?>

		<?php get_template_part( 'lib/theme-loops/formats/format', 'video'); ?>

	<?php } elseif ($format == 'audio') { ?>

		<?php get_template_part( 'lib/theme-loops/formats/format', 'audio' ); ?>

	<?php } elseif ($format == 'link') { ?>

		<?php get_template_part( 'lib/theme-loops/formats/format', 'link' ); ?>

	<?php } else { ?>

		<div class="ft-fimg featured-image"><?php if ( has_post_thumbnail() ) : ?><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('main'); ?></a><?php endif; ?></div>

	<?php } ?>

	<?php if ($format == 'quote') { ?>

		<?php get_template_part( 'lib/theme-loops/formats/format', 'quote' ); ?>

	<?php } else { ?>

	<div class="ft-boxct clearfix">

		<header><h2 class="ft-ptitle entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2></header>

		<div class="ft-meta"><div class="ft-bmeta"><span class="ft-author vcard author"><span class="fn"><?php the_author_posts_link(); ?></span></span> - <span class="ft-time"><time class="updated" datetime="<?php the_time('F, jS Y'); ?>"><?php the_time('F j, Y'); ?></time></span> - <span class="ft-categories"><?php the_category(', '); ?></span></div></div>

		<section class="ft-entry"><?php the_content(__('Read More','ft')); ?></section>

		<?php if ($format == 'status') { ?><div class="ft-stmeta"><time class="updated" datetime="<?php the_time('F, jS Y'); ?>"><?php the_time('F j, Y'); ?></time></div><?php } ?>

	</div>

	<?php } ?>

</article>

<?php endwhile; // End the loop ?>