<?php

add_filter( 'ft_metaboxes', 'ft_metaboxes' );
	
function ft_metaboxes( array $metaboxes ) {
		
		$prefix = 'ft_';

		$metaboxes[] = array(
			'id'		 => $prefix . 'custom_post_formats',
			'title'      => __('Custom For Post Formats', 'uxde'),
			'pages'      => array('post'), // Post type
			'context'    => 'normal',
			'priority'   => 'high',
			'fields' => array(
				array(
					'label' => __('Gallery IDs', 'ft'),
					'desc'	=> __('Enter the gallery ids that you created. Example: 13, 25', 'ft'),
					'id'	=> $prefix . 'custom_gallery_id',
					'type' => 'text',
					'std' => ''
				),
				array(
					'label' => __('Gallery Columns', 'ft'),
					'desc'	=> __('Number of gallery columns.', 'ft'),
					'id'	=> $prefix . 'custom_gallery_columns',
					'type'	=> 'select',
					'options' => array(
						'3' => '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
					),
					'std'	=> '5'
				),
				array(
					'label' => __('Video Embed Code', 'ft'),
					'desc'	=> __('Enter the video embed code ( Youtube, Vimeo and Dailymotion with size: 1024x575 ).', 'ft'),
					'id'	=> $prefix . 'custom_video_code',
					'type'	=> 'textarea',
					'std' => ''
				),
				array(
					'label' => __('Audio Embed Code', 'ft'),
					'desc'	=> __('Enter the video embed code from SoundCloud.', 'ft'),
					'id'	=> $prefix . 'custom_audio_code',
					'type'	=> 'textarea',
					'std' => ''
				),
				array(
					'label' => __('Link Text', 'ft'),
					'desc'	=> __('Link text for link post format.', 'ft'),
					'id'	=> $prefix . 'custom_link_text',
					'type'	=> 'text',
					'std' => ''
				),
				array(
					'label' => __('Link URL', 'ft'),
					'desc'	=> __('Link url for link post format.', 'ft'),
					'id'	=> $prefix . 'custom_link_url',
					'type'	=> 'text',
					'std' => ''
				),
				array(
					'label' => __('Quote Source Title', 'ft'),
					'desc'	=> __('Quote source title for quote post format.', 'ft'),
					'id'	=> $prefix . 'custom_quote_text',
					'type'	=> 'text',
					'std' => ''
				),
				array(
					'label' => __('Quote Source URL', 'ft'),
					'desc'	=> __('Quote source url for quote post format.', 'ft'),
					'id'	=> $prefix . 'custom_quote_url',
					'type'	=> 'text',
					'std' => ''
				),
			)
		);

		
	return $metaboxes;
}

