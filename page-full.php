<?php
/*
Template Name: Full Width
*/
get_header(); ?>

<main id="main">

	<div id="content" role="main" style="background:none!important;">
	
		<div class="post-box">

			<?php get_template_part('lib/theme-loops/loop', 'page'); ?>

		</div>

	</div>

</main>
		
<?php get_footer(); ?>