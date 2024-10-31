<?php

namespace MyShopKitPopupSmartBarSlideIn\PostTypeConverter\Controllers;


use MyShopKitPopupSmartBarSlideIn\Shared\AutoPrefix;

class PostTypeConverter
{
	public static function convertToNewPostTypes()
	{
		global $wpdb;

		$postTypes
			= "eyJ3b29raXRfc2xpZGVpbiI6Im1za3Bzc19zbGlkZWluIiwid29va2l0X3BvcHVwIjoibXNrcHNzX3BvcHVwIiwid29va2l0X3NtYXJ0YmFyIjoibXNrcHNzX3NtYXJ0YmFyIn0=";

		$aPostTypes = json_decode(base64_decode($postTypes), true);

		foreach ($aPostTypes as $postType => $newPostType) {
			$wpdb->update($wpdb->posts, ["post_type" => $newPostType], ["post_type" => $postType], ["%s"], ["%s"]);
		}

		$wpdb->query(
			'UPDATE '.$wpdb->postmeta.' SET meta_key = REPLACE(meta_key, "wookit_", "mskpss_")'
		);

		$wpdb->query(
			'UPDATE '.$wpdb->usermeta.' SET meta_key = REPLACE(meta_key, "wookit_", "mskpss_")'
		);

		$wpdb->query(
			'UPDATE '.$wpdb->options.' SET option_name = REPLACE(option_name, "wookit_", "mskpss_")'
		);
	}
}
