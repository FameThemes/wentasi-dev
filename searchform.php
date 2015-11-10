<form role="search" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<h3><?php _e('Search on', 'ft'); ?> <?php bloginfo('name'); ?></h3>
	<input type="text" id="s" name="s" value="<?php _e('Type keyword and hit enter...', 'ft'); ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" />
	<input type="submit" id="searchsubmit" value="" />
</form>
