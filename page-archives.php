<?php
/*
Template Name: Archives
*/
get_header(); ?>

<div id="content" role="main">
		
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<div class="ft-breadcrumbs hidden"><?php echo ft_breadcrumbs(); ?></div>

		<div class="ft-fimg featured-image"><?php if ( has_post_thumbnail() ) : ?><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('main'); ?></a><?php endif; ?></div>

		<div class="ft-boxct clearfix">

			<header><h1 class="ft-ptitle entry-title"><?php the_title(); ?></h1></header>

			<div class="ft-arlist">

				<div class="ft-arrcposts">

					<h3><?php _e('Recent Posts', 'ft'); ?></h3>
					<?php query_posts( 'showposts=20' ); ?><?php if ( have_posts() ) : ?>
					<ul>
					<?php while (have_posts()) : the_post(); ?>
						<li>

							<article id="post-<?php the_ID(); ?>-recent" class="widget-post">
	
								<header><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></header>
			
							</article>	

						</li>
					<?php endwhile; endif; ?><?php wp_reset_query(); ?> 
					</ul>

				</div>

				<div class="ft-arpposts">

					<h3><?php _e('Popular Posts', 'ft'); ?></h3>
					<?php $year = date('Y'); query_posts('post_type=post&posts_per_page=20&orderby=comment_count&order=DESC&year=' . $year . ''); ?><?php if ( have_posts() ) : ?>
					<ul>
					<?php while (have_posts()) : the_post(); ?>
						<li>

							<article id="post-<?php the_ID(); ?>-recent" class="widget-post">
	
								<header><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></header>
			
							</article>	

						</li>
					<?php endwhile; endif; ?><?php wp_reset_query(); ?> 
					</ul>

				</div>

				<div class="ft-arpages">

					<h3><?php _e('All Pages', 'ft'); ?></h3>

					<ul>
						<?php wp_list_pages('title_li='); ?>
					</ul>

				</div>

				<div class="ft-arcategories">

					<h3><?php _e('Archives by Categories', 'ft'); ?></h3>

					<ul class="ft-arcatlist">
						<?php
							$args = array (
							'echo' => 0,
							'show_option_all'    => '',
							'orderby'            => 'name',
							'show_count' => 0,
							'title_li' => '',
							'exclude'  => '',
							'depth' => 1
							);
							$variable = wp_list_categories($args);
							echo $variable;
						?>
					</ul>

				</div>

				<div class="ft-armonthly">

					<h3><?php _e('Monthly Archives', 'ft'); ?></h3>

					<ul class="ft-armonlist">
						<?php
							$args = array (
							'echo' => 0,
							'show_option_all'    => '',
							'show_post_count' => 0,
							'title_li' => ''
							);
							$variable = wp_get_archives($args);
							echo $variable;
						?>
					</ul>

				</div>

			</div>

		</div>

	</article>

</div>
		
<?php get_footer(); ?>

