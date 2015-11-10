<?php get_header(); ?>

<main id="main">

	<div id="content" role="main">

	<?php

    echo '<pre>';
    print_r( json_decode( get_theme_mod( 'new_repeatable_id' ), true ) );
    echo '</pre>';


    if (!have_posts()) : ?>

	<div class="ft-smptitle">

		<h2 class="ft-mptitle"><?php _e('Sorry, no results were found.', 'ft'); ?></h2>

	</div>

	<?php else: ?>

	<?php get_template_part('lib/theme-loops/loop', 'index'); ?>

	<?php endif; ?>

	<?php if ($wp_query->max_num_pages > 1) : ?><nav id="post-nav" class="clearfix"><?php ft_pagination(); ?></nav><?php endif; ?>

	</div>

</main>
		
<?php get_footer(); ?>