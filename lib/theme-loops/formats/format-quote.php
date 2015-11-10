<?php $custom_quote_text = get_post_meta( get_the_ID(), 'ft_custom_quote_text', true); ?>
<?php $custom_quote_url = get_post_meta( get_the_ID(), 'ft_custom_quote_url', true); ?>

<div class="ft-boxct clearfix">

	<section class="ft-entry quote-entry"><a href="<?php the_permalink(); ?>"><?php the_content(); ?></a></section>
	<div class="ft-qsource"><a target="_blank" href="<?php echo esc_url($custom_quote_url); ?>"><?php echo esc_attr($custom_quote_text); ?></a></div>

</div>