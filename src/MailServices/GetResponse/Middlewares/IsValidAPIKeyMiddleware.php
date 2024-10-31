<?php

namespace MyShopKitPopupSmartBarSlideIn\MailServices\GetResponse\Middlewares;

use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares\IMiddleware;
use MyShopKitPopupSmartBarSlideIn\MailServices\GetResponse\Shared\GetResponseConnection;

class IsValidAPIKeyMiddleware implements IMiddleware{

	public function validation( array $aAdditional = [] ): array {
		$apiKey = $aAdditional['apiKey'] ?? '';
		if( !empty($apiKey) ) {
			if( GetResponseConnection::connect($apiKey)->ping() ) {
				return MessageFactory::factory()->success('OK');
			}
			return MessageFactory::factory()
			->error(
				esc_html__('Invalid API Key',
					'myshopkit-popup-smartbar-slidein'),
				400
			);
		}
		return MessageFactory::factory()
			->error(
				esc_html__('Invalid API Key',
					'myshopkit-popup-smartbar-slidein'),
				400
			);
	}
}
