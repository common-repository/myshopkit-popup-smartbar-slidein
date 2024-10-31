<?php

namespace MyShopKitPopupSmartBarSlideIn\MailServices\iContact\src\Middlewares;

use MyShopKitPopupSmartBarSlideIn\Shared\AutoPrefix;
use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares\IMiddleware;

class IsIContactActiveMiddleware implements IMiddleware{
	public string $key = 'icontact_info';
	public string $mailService = 'icontact';
	public function validation( array $aAdditional = [] ): array {
		if ( ! isset( $aAdditional['postID'] ) || empty( $aAdditional['postID'] ) ) {
			return MessageFactory::factory()->error( 'The campaign is disabled', 403 );
		}

		$aPostMeta = get_post_meta( $aAdditional['postID'], AutoPrefix::namePrefix( $this->key), true );

		if ( isset( $aPostMeta['status'] ) && $aPostMeta['status'] == 'active' ) {
			return MessageFactory::factory()->success( 'Passed' );
		}

		return MessageFactory::factory()->error( 'The campaign is disabled', 403 );
	}
}
