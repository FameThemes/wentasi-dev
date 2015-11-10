<?php

function ft_load_newsletter_widget()
{
	register_widget('FT_Newsletter_Widget');
}
	add_action('widgets_init', 'ft_load_newsletter_widget');


/* ==  Widget ==============================*/
class FT_Newsletter_Widget extends WP_Widget {

/* ==  Widget Setup ==============================*/
	function __construct() {

		$widget_ops = array('classname' => 'ft_newsletter_widget', 'description' => __('A widget that displays a newsletter form.', 'ft'));
		
		$control_ops = array('id_base' => 'ft_newsletter_widget');
		
		parent::__construct('ft_newsletter_widget', __('WFT - Newsletter', 'ft'), $widget_ops, $control_ops);
	}
	
/* ==  Display Widget ==============================*/

	function widget($args, $instance)
	{
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$description = apply_filters('widget_text', $instance['description']);
		$optin_code = $instance['optin_code'];
		$single = isset($instance['single']) ? $instance['single'] : false;
		$spacing = isset($instance['spacing']) ? $instance['spacing'] : false;

		if($single)
		{
			if(is_single())
			{
				echo $before_widget;

				if($spacing)
					echo '<div class="optin_outer no-spacing">';
				else
					echo '<div class="optin_outer">';

				echo '<div class="optin_wrapper">';
				if($title)
					echo $before_title . $title . $after_title;
		
				if($description)
					echo wpautop($description);

				if($optin_code)
					echo $optin_code;

				echo '</div></div>';
				echo $after_widget;
			}
		}
		else
		{
			echo $before_widget;

			if($spacing)
				echo '<div class="optin_outer no-spacing">';
			else
				echo '<div class="optin_outer">';

			echo '<div class="optin_wrapper">';

			if($title)
				echo $before_title . $title . $after_title;
		
			if($description)
				echo wpautop($description);

			if($optin_code)
				echo $optin_code;

			echo '</div></div>';
			echo $after_widget;
		}
	}

/* ==  Update Widget ==============================*/

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['description'] = $new_instance['description'];
		$instance['optin_code'] = $new_instance['optin_code'];
		$instance['single'] = $new_instance['single'];
		$instance['spacing'] = $new_instance['spacing'];

		return $instance;
	}

/* ==  Widget Settings ==============================*/

	function form($instance)
	{
		$defaults = array(
			'title' => __('Optin Headline', 'ft'), 
			'description' => __('Explain to people why people should signup to your newsletter. Be as concise as possible, and <em>very</em> convincing!', 'ft'),
			'optin_code' => __('Paste your optin code here (read documentation if you need help finding your optin code)', 'ft'),
			'single' => 0,
			'spacing' => 0
		);
		$instance = wp_parse_args((array) $instance, $defaults); ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'ft'); ?></label>
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description:', 'ft'); ?></label>
			<textarea id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" class="widefat" rows="4" cols="20"><?php echo $instance['description']; ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('optin_code'); ?>"><?php _e('Optin Code:', 'ft'); ?></label>
			<textarea id="<?php echo $this->get_field_id('optin_code'); ?>" name="<?php echo $this->get_field_name('optin_code'); ?>" class="widefat" rows="7" cols="20"><?php echo $instance['optin_code']; ?></textarea>
		</p>
		
		<p>
	    	<input class="checkbox" type="checkbox" <?php checked((bool) $instance['single'], true); ?> id="<?php echo $this->get_field_id('single'); ?>" name="<?php echo $this->get_field_name('single'); ?>" />
	    	<label for="<?php echo $this->get_field_id('single'); ?>"><?php _e('Show on single post only', 'ft'); ?></label>
		</p>

		<p>
	    	<input class="checkbox" type="checkbox" <?php checked((bool) $instance['spacing'], true); ?> id="<?php echo $this->get_field_id('spacing'); ?>" name="<?php echo $this->get_field_name('spacing'); ?>" />
	    	<label for="<?php echo $this->get_field_id('spacing'); ?>"><?php _e('Don\'t wrap to edge of content area', 'ft'); ?></label>
		</p>
	<?php
	}
}