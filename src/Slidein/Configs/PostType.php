<?php

use MyShopKitPopupSmartBarSlideIn\Shared\AutoPrefix;

$labels = [
	'name'           => esc_html__( 'Slide In', 'myshopkit-popup-smartbar-slidein' ),
	'singular_name'  => esc_html__( 'Slide In', 'myshopkit-popup-smartbar-slidein' ),
	'menu_name'      => esc_html__( 'Slide In', 'myshopkit-popup-smartbar-slidein' ),
	'name_admin_bar' => esc_html__( 'Slide In', 'myshopkit-popup-smartbar-slidein' )
];

return [
	'description'        => esc_html__( 'The setting for your Slide in', 'myshopkit-popup-smartbar-slidein' ),
	'labels'             => $labels,
	'public'             => true,
	'publicly_queryable' => true,
	'show_ui'            => true,
	'show_in_menu'       => true,
	'query_var'          => true,
	'rewrite'            => [ 'slug' => AutoPrefix::namePrefix( 'slidein' ) ],
	'post_type'          => AutoPrefix::namePrefix( 'slidein' ),
	'capability_type'    => 'post',
	'has_archive'        => true,
	'hierarchical'       => true,
	'menu_position'      => null,
	'supports'           => [ 'title', 'editor', 'thumbnail', 'author' ]
];
