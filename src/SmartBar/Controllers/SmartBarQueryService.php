<?php


namespace MyShopKitPopupSmartBarSlideIn\SmartBar\Controllers;


use MyShopKitPopupSmartBarSlideIn\Shared\AutoPrefix;
use MyShopKitPopupSmartBarSlideIn\Shared\Post\Query\IQueryPost;
use MyShopKitPopupSmartBarSlideIn\Shared\Post\Query\QueryPost;

class SmartBarQueryService extends QueryPost implements IQueryPost {
	public function getPostType(): string {
		$this->postType = AutoPrefix::namePrefix( 'smartbar' );

		return $this->postType;
	}

	public function parseArgs(): IQueryPost {
		$this->aArgs              = $this->commonParseArgs();
		$this->aArgs['post_type'] = $this->getPostType();

		return $this;
	}
}
