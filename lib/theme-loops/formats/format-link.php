<div class="ft-fimg link">

<?php $custom_link_text = get_post_meta( get_the_ID(), 'ft_custom_link_text', true); ?>
<?php $custom_link_url = get_post_meta( get_the_ID(), 'ft_custom_link_url', true); ?>

<h2><a target="_blank" href="<?php echo esc_url($custom_link_url); ?>"><?php echo esc_attr($custom_link_text); ?></a></h2>

</div>