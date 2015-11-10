<?php

	class FT_Meta_Box{
		
		protected $_metabox;
		
		function __construct( $metabox ) {
			if ( !is_admin() ) return;
	
			$this->_metabox = $metabox;
	
			add_action( 'admin_menu', array( &$this, 'add' ) );
			add_action( 'save_post', array( &$this, 'save' ) );
	
		}
		
		// Add metaboxes
		function add() {
			$this->_metabox['context'] = empty($this->_metabox['context']) ? 'normal' : $this->_metabox['context'];
			$this->_metabox['priority'] = empty($this->_metabox['priority']) ? 'high' : $this->_metabox['priority'];
			
			foreach ( $this->_metabox['pages'] as $page ) {
				add_meta_box( $this->_metabox['id'], $this->_metabox['title'], array(&$this, 'show'), $page, $this->_metabox['context'], $this->_metabox['priority']) ;
			}
		}
		
		// Show fields
		function show() {
			global $post;
			
			echo '<input type="hidden" name="wp_meta_box_nonce" value="', wp_create_nonce( basename(__FILE__) ), '" />';
			echo '<table class="form-table ft-metabox">';
			
			foreach ( $this->_metabox['fields'] as $field ) {
				
				if ( !isset( $field['name'] ) ) $field['name'] = '';
				if ( !isset( $field['desc'] ) ) $field['desc'] = '';
				if ( !isset( $field['std'] ) ) $field['std'] = '';
			
				// get value of this field if it exists for this post
				$meta = get_post_meta($post->ID, $field['id'], true);
				
				// Use standard value if empty
				$meta = ( '' === $meta || array() === $meta ) ? $field['std'] : $meta;
				
				// begin a table row with
				echo '<tr id="'.$field['id'].'_box">';
					echo '<th><label for="'.$field['id'].'">'.$field['label'].'</label></th>';
					
					echo '<td>';
					switch($field['type']) {
						
						// text
						case 'text':
							echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="64" />';
							echo '<br /><span style="margin-top: 10px; display: block;" class="description">'.$field['desc'].'</span>';
							break;
						
						// textarea
						case 'textarea':
							echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>';
							echo '<br /><span style="margin-top: 10px; display: block;" class="description">'.$field['desc'].'</span>';
							break;
						
						// checkbox
						case 'checkbox':
							echo '<input style="margin-right: 10px;" type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>';
							echo '<label for="'.$field['id'].'">'.$field['desc'].'</label>';
							break;
						
						// select
						case 'select':
							echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
							foreach ($field['options'] as $key => $val) {
								echo '<option', $meta == $key ? ' selected="selected"' : '', ' value="'.$key.'">'.$val.'</option>';
							}
							echo '</select><br /><span style="margin-top: 10px; display: block;" class="description">'.$field['desc'].'</span>';
							break;
						
						// radio
						case 'radio':
							foreach ( $field['options'] as $key => $val ) {
								echo '<input type="radio" name="'.$field['id'].'" id="'.$field['id'].'_'.$key.'" value="'.$key.'" ',$meta == $key ? ' checked="checked"' : '',' />';
								echo '<label for="'.$key.'">'.$val.'</label>';
							}
							break; 
						
						// repeated
						case 'rating_criteria':
							
							$rows = array();
							if($meta) $rows = $meta;
							$c = 0;
							if ( count( $rows ) > 0 ) {
								foreach( $rows as $row ) {
									if ( isset( $row['c_label'] ) || isset( $row['score'] ) ) {
										echo '
										<p>
										<label for="'.$field['id'].'['.$c.'][c_label]">Label :</label> 
										<input type="text" name="'.$field['id'].'['.$c.'][c_label]" value="'.$row['c_label'].'" />
										<label for="'.$field['id'].'['.$c.'][score]">Score :</label> 
										<input type="text" name="'.$field['id'].'['.$c.'][score]" value="'.$row['score'].'" />
										<a class="remove button-secondary">Remove</a>
										</p>';
										$c = $c + 1;
									}
								}
							}
							echo '<span id="criteria-placeholder"></span>';
							echo '<a class="add-criteria button-primary" href="#">Add Criteria</a>';
							echo '<br /><span style="margin-top: 10px; display: block;" class="description">'.$field['desc'].'</span>';
							?>
							<script>
								var $ = jQuery.noConflict();
								$(document).ready(function() {
									var count = <?php echo $c; ?>;
									$('.add-criteria').click(function() {
										count = count + 1;
								
										$('#criteria-placeholder').append('<p><label for="<?php echo $field['id']; ?>['+count+'][c_label]">Label :</label><input type="text" name="<?php echo $field['id']; ?>['+count+'][c_label]" value="" /><label for="<?php echo $field['id']; ?>['+count+'][score]">Score :</label><input type="text" name="<?php echo $field['id']; ?>['+count+'][score]" value="" /><a class="remove button-secondary">Remove</a></p>');
										return false;
									});
									
									$('.remove').live('click', function() {
										$(this).parent().remove();
									});
								});
							</script>
							<?php
						break;  
					}
					echo '</td>';
				echo '</tr>';
				
			}
			
			echo '</table>';
		}
		
		// Save data from metabox
		function save( $post_id)  {
			// verify nonce
			if ( ! isset( $_POST['wp_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['wp_meta_box_nonce'], basename(__FILE__) ) ) {
				return $post_id;
			}
			
			// check autosave
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
				return $post_id;
				
			// check permissions
			if ('page' == $_POST['post_type']) {
				if (!current_user_can('edit_page', $post_id))
					return $post_id;
				} elseif (!current_user_can('edit_post', $post_id)) {
					return $post_id;
			}
			
			// loop through fields and save the data
			foreach ( $this->_metabox['fields'] as $field ) {
				
				$old = get_post_meta($post_id, $field['id'], true);
				
				$new = isset( $_POST[$field['id']] ) ? $_POST[$field['id']] : null;
				
				if ($new && $new != $old) {
					update_post_meta($post_id, $field['id'], $new);
				} 
				elseif ('' == $new && $old) {
					delete_post_meta($post_id, $field['id'], $old);
				}
				
			} // end foreach
		}
	}
	


/*	Initialize Metabox
 *	--------------------------------------------------------- */
	function ft_init_metaboxes() {
		if ( class_exists( 'FT_Meta_Box' ) ) {
			require_once(TEMPLATEPATH. '/lib/theme-functions/theme-metaboxes.php');
			
			$metaboxes = array();
			$metaboxes = apply_filters ( 'ft_metaboxes' , $metaboxes );
			foreach ( $metaboxes as $metabox ) {
				$my_box = new FT_Meta_Box( $metabox );
			}
		}
	}
	
	add_action( 'init', 'ft_init_metaboxes', 9999 );
	