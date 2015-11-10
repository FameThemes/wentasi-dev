<?php $custom_gallery_id = get_post_meta( get_the_ID(), 'ft_custom_gallery_id', true); ?>
<?php $custom_gallery_columns = get_post_meta( get_the_ID(), 'ft_custom_gallery_columns', true); ?>

<div class="ft-fimg gallery"><?php echo do_shortcode('[gallery orderby="id" columns="' . $custom_gallery_columns . '" ids="' . $custom_gallery_id . '" link="file"]'); ?></div>