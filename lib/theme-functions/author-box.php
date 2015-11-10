<?php if ( get_theme_mod( 'wentasi_author_box' != 1 ) ) : ?>

<div class="ft-bauthor clearfix">

	<div class="ft-atava span2">

		<?php echo get_avatar( get_the_author_meta('email'), '100' ); ?>

	</div>

	<div class="ft-atinfo span10 right">

		<div class="vcard author ft-attitle"><h4><span class="fn"><?php the_author_link(); ?></span></h4></div>

		<div class="ft-atdescr"><p><?php the_author_meta('description'); ?></p></div>

	</div>

</div>

<?php endif; ?>