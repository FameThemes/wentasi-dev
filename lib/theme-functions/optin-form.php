<?php if ( get_theme_mod( 'wentasi_optin_form' ) != 1 ) : ?>

<?php
$wentasi_optin_form_title = get_theme_mod( 'wentasi_optin_form_title', 'Free Ebooks and Course' );
$wentasi_optin_form_text = get_theme_mod( 'wentasi_optin_form_text', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.' );
$wentasi_optin_form_code = get_theme_mod( 'wentasi_optin_form_code', '<form action="http://famethemes.us7.list-manage.com/subscribe/post?u=69bf8334f702278832e7e224f&id=08f4359108" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate><input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required/><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"/></form>' );
?>

<div class="ft-optin clearfix">

	<?php if ( $wentasi_optin_form_title ) : ?><h5><?php echo $wentasi_optin_form_title; ?></h5><?php endif; ?>

	<?php if ( $wentasi_optin_form_text ): ?><p><?php echo $wentasi_optin_form_text; ?></p><?php endif; ?>

	<?php if ( $wentasi_optin_form_code ): ?><?php echo $wentasi_optin_form_code; ?><?php endif; ?>

</div>

<?php endif; ?>