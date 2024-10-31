<?php

namespace MyShopKitPopupSmartBarSlideIn\MailServices\GetResponse\Middlewares;

use MyShopKitPopupSmartBarSlideIn\Shared\AutoPrefix;
use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares\IMiddleware;

class IsGetResponseActiveMiddleware implements IMiddleware{

	public string $key = 'get_response_info';
	public string $mailService = 'getresponse';

	public function validation( array $aAdditional = [] ): array {
		if( !isset($aAdditional['postID']) || empty($aAdditional['postID']) ) {
			return MessageFactory::factory()->error('The campaign is disabled', 403);
		}

		$aPostMeta = get_post_meta($aAdditional['postID'], AutoPrefix::namePrefix($this->key), TRUE);

		if( isset($aPostMeta['status']) && $aPostMeta['status'] == 'active' ) {
			return MessageFactory::factory()->success('Passed');
		}

		return MessageFactory::factory()->error('The campaign is disabled', 403);
	}
}
