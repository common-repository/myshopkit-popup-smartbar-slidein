<?php


namespace MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares;


use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use MyShopKitPopupSmartBarSlideIn\Shared\MultiLanguage\MultiLanguage;

class IsUserLoggedIn implements IMiddleware {
	use TraitLocale;

	public function validation( array $aAdditional = [] ): array {
		if ( !is_user_logged_in() ) {
			return MessageFactory::factory()->error(
				MultiLanguage::setLanguage( $this->getMiddlewareLocale( $aAdditional ) )->getMessage( 'loginRequired' ),
				400
			);
		}

		return MessageFactory::factory()->success( 'Passed' );
	}
}
