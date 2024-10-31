<?php

namespace MyShopKitPopupSmartBarSlideIn\Slidein\Controllers;

use MyShopKitPopupSmartBarSlideIn\Shared\Post\TraitPostType;

class PostTypeRegistration {
	use TraitPostType;

	public function __construct() {
		add_action( 'init', [ $this, 'registerPostType' ] );
	}

	public function registerPostType() {
		$aConfig = include plugin_dir_path( __FILE__ ) . '../Configs/PostType.php';

		register_post_type( $this->getPostType( plugin_dir_path( __FILE__ ) . '../Configs/' ), $aConfig );
	}
}
