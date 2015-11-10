<?php if ( get_theme_mod( 'wentasi_social_share' ) ) : ?>

<div class="ft-share clearfix">
					
	<ul>

		<li class="ft-stwitter"><a target="_blank" href="https://twitter.com/intent/tweet?original_referer=<?php the_permalink(); ?>&text=<?php the_title_attribute(); ?>&tw_p=tweetbutton&url=<?php the_permalink(); ?>">Tweet It</a></li>
		<li class="ft-sfacebook"><a target="_blank" href="http://www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">Like It</a></li>
		<li class="ft-sgoogle"><a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">Google +</a></li>
		<li class="ft-slinkedin"><a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title_attribute(); ?>&source=<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">Linkedin</a></li>
		<li class="ft-spinterest"><a target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php if (has_post_thumbnail( $post->ID ) ): ?><?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); ?><?php echo $image[0]; ?><?php endif; ?>&description=<?php the_title_attribute(); ?>" onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">Pin it</a></li>
		<li class="ft-sstumbleupon"><a href="http://www.stumbleupon.com/submit?url=<?php the_permalink(); ?>&title=<?php the_title_attribute(); ?>" onclick="javascript:window.open(this.href,
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">Stumble</a></li>
						
	</ul>

</div>

<?php endif; ?>