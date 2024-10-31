<?php

namespace MyShopKitPopupSmartBarSlideIn\MailServices\MailChimp\Middleware;

use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use MyShopKitPopupSmartBarSlideIn\MailServices\MailChimp\Shared\MailChimpConnection;
use MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares\IMiddleware;
use MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares\TraitLocale;
use MyShopKitPopupSmartBarSlideIn\Shared\MultiLanguage\MultiLanguage;

class IsValidAPIKeyMiddleware implements IMiddleware {
	use TraitLocale;

	/**
	 * @throws \Exception
	 */
	public function validation( array $aAdditional = [] ): array {
		if ( ! isset( $aAdditional['apiKey'] ) || empty( $aAdditional['apiKey'] ) ) {
			return MessageFactory::factory()->error( MultiLanguage::setLanguage( $this->getMiddlewareLocale(
				$aAdditional ) )->getMessage( 'invalidAPIKey' ), 400 );
		}

		if ( MailChimpConnection::connect( $aAdditional['apiKey'] )->ping() ) {
			return MessageFactory::factory()->success( 'Passed' );
		}

		return MessageFactory::factory()->error( MultiLanguage::setLanguage( $this->getMiddlewareLocale(
			$aAdditional ) )->getMessage( 'invalidAPIKey' ), 400 );
	}
}
