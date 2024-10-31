<?php

namespace MyShopKitPopupSmartBarSlideIn\MailServices\GetResponse\Middlewares;

use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares\IMiddleware;
use MyShopKitPopupSmartBarSlideIn\MailServices\GetResponse\Shared\GetResponseConnection;

class IsValidListIDMiddleware implements IMiddleware{

	public function validation( array $aAdditional = [] ): array {
		$listID = $aAdditional['listID'] ?? '';
		$apiKey = $aAdditional['apiKey'] ?? '';
		if( !empty($listID) && !empty($apiKey) ) {
			$connect = GetResponseConnection::connect($apiKey);
			if( $connect->ping() ) {
				return $connect->getListInfo($listID);
			}
			return MessageFactory::factory()->error('Look like your Api Key is invalid', 400);
		}
		return MessageFactory::factory()->error('Look like your Api Key is empty', 400);
	}
}
