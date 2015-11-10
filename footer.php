<?php 
$wentasi_btt = get_theme_mod( 'wentasi_btt' );
$wentasi_copyright_text = get_theme_mod( 'wentasi_copyright_text' );
?>

<div class="ft-shadow span12"></div>

<div class="ft-ftgle span12"><a id="ft-ftgbt" href="#"><i class="icon-reorder"></i></a></div>

<footer id="footer">

	<div id="ft-footerinside">

		<div class="ft-widgets clearfix">

			<?php dynamic_sidebar("footer_widgets"); ?>

		</div>

		<div class="ft-copyright clearfix">

			<div class="ft-cpborder"></div>

			<div class="ft-cpcenter">

				<div class="ft-cptext">
					<?php if ( $wentasi_copyright_text ): ?><?php echo wp_kses_post($wentasi_copyright_text); ?><?php else: ?>Copyright &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All Rights Reserved','ft') ?>.<?php endif; ?>
				</div>

				<?php
				$theme_author_copyright = '<div class="ft-cplogo">'. __('Designed by ','ft') .'<a href="http://www.famethemes.com/">FameThemes</a></div>';
				$theme_author_copyright = apply_filters( 'theme_author_copyright', $theme_author_copyright );
				echo $theme_author_copyright;
				?>

			</div>

		</div>

	</div>

</footer>

</section>

<?php if ( $wentasi_btt != 1 ) { ?>
<div class="ft-backtop">

	<div class="back-top">

		<a href="#top">
			<span></span>
		</a>
	
	</div>
	
</div>
<?php } ?>
				
<?php wp_footer(); ?>
</body>
</html>