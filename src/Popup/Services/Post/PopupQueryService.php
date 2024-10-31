<?php


namespace MyShopKitPopupSmartBarSlideIn\Popup\Services\Post;


use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use MyShopKitPopupSmartBarSlideIn\Shared\AutoPrefix;
use MyShopKitPopupSmartBarSlideIn\Shared\Post\Query\IQueryPost;
use MyShopKitPopupSmartBarSlideIn\Shared\Post\Query\PostSkeleton;
use MyShopKitPopupSmartBarSlideIn\Shared\Post\Query\QueryPost;

class PopupQueryService extends QueryPost implements IQueryPost {
	public function getPostType(): string {
		$this->postType = AutoPrefix::namePrefix( 'popup' );

		return $this->postType;
	}

	public function parseArgs(): IQueryPost {
		$this->aArgs              = $this->commonParseArgs();
		$this->aArgs['post_type'] = $this->getPostType();

		return $this;
	}
}
