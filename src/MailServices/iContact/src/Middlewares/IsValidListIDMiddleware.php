<?php

namespace MyShopKitPopupSmartBarSlideIn\MailServices\iContact\src\Middlewares;

use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares\IMiddleware;
use MyShopKitPopupSmartBarSlideIn\MailServices\iContact\src\Shared\IcontactConnection;

class IsValidListIDMiddleware implements IMiddleware{

	public function validation( array $aAdditional = [] ): array {
		$listID      = $aAdditional['listID'] ?? '';
		$appID       = $aAdditional['appID'] ?? '';
		$appUsername = $aAdditional['appUsername'] ?? '';
		$appPassword = $aAdditional['appPassword'] ?? '';
		if( !empty($listID) ) {
			return MessageFactory::factory()
				->response(IcontactConnection::connect($appID, $appUsername, $appPassword)
					->getListInfo($listID)
				);
		}
		return MessageFactory::factory()->error(esc_html__('Oops! Look like your list ID is empty. Please check again.',
			'myshopkit-popup-smartbar-slidein'),
			400
		);
	}
}
