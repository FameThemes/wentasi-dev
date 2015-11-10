<?php if ( get_theme_mod( 'wentasi_related_post' ) != 1 ) : ?>

<div class="ft-related clearfix">

	<h3><?php _e('Related Posts', 'ft'); ?></h3>

	<ul>

		<?php $orig_post = $post; global $post; $categories = get_the_category($post->ID); ?>
		<?php if ($categories) :?> 
		<?php $category_ids = array(); foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id; $args=array('category__in' => $category_ids,'post__not_in' => array($post->ID),'posts_per_page'=> 3,'orderby'=> 'rand','ignore_sticky_posts'=> 1); ?>
    	<?php $my_query = new wp_query( $args ); ?>
    	<?php if( $my_query->have_posts() ) : while( $my_query->have_posts() ) : $my_query->the_post();?>

    		<li><article class="ft-rlpost">
									
				<div class="ft-fimg"><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php if ( has_post_thumbnail() ) : ?><?php the_post_thumbnail('related'); ?><?php endif; ?></a></div>							
									
				<p class="related-article"><a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a></p>
									
			</article></li>

    	<?php $post = $orig_post; ?><?php endwhile; endif; ?><?php endif; ?><?php wp_reset_query(); ?>

	</ul>

</div>

<?php endif; ?>