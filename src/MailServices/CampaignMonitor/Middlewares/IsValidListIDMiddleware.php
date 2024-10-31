<?php

namespace MyShopKitPopupSmartBarSlideIn\MailServices\CampaignMonitor\Middlewares;

use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares\IMiddleware;
use MyShopKitPopupSmartBarSlideIn\MailServices\CampaignMonitor\Shared\CampaignMonitorConnection;

class IsValidListIDMiddleware implements IMiddleware{

	public function validation( array $aAdditional = [] ): array {
		$apiKey   = $aAdditional['apiKey'] ?? '';
		$clientID = $aAdditional['clientID'] ?? '';
		$listID   = $aAdditional['listID'] ?? '';
		if( !empty($listID) ) {
			$aResponse = CampaignMonitorConnection::connect($apiKey, $clientID)->getListInfo($listID);
			return MessageFactory::factory()->response($aResponse);
		}
		return MessageFactory::factory()
			->error(
				esc_html__('Oops! Look like your required fields has been left empty. Please re-check it!.',
					'myshopkit-popup-smartbar-slidein'), 400
			);
	}
}
