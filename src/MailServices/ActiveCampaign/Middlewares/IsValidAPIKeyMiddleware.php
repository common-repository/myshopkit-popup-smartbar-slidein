<?php

namespace MyShopKitPopupSmartBarSlideIn\MailServices\ActiveCampaign\Middlewares;

use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares\IMiddleware;
use MyShopKitPopupSmartBarSlideIn\MailServices\ActiveCampaign\Shared\ActiveCampaignConnection;

class IsValidAPIKeyMiddleware implements IMiddleware{

	public function validation( array $aAdditional = [] ): array {
		$apiKey = $aAdditional['apiKey'] ?? '';
		$url    = $aAdditional['url'] ?? '';

		if( ActiveCampaignConnection::connect($apiKey, $url)->ping() ) {
			return MessageFactory::factory()
				->success(
					esc_html__('OK',
						'myshopkit'),
					[
						'apiKey' => $apiKey,
						'url'    => $url,
					]
				);
		}
		return MessageFactory::factory()
			->error(
				esc_html__('Invalid API Key',
					'myshopkit'),
				400
			);
	}
}
