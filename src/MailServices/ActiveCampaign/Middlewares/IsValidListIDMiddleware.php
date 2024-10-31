<?php

namespace MyShopKitPopupSmartBarSlideIn\MailServices\ActiveCampaign\Middlewares;

use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares\IMiddleware;
use MyShopKitPopupSmartBarSlideIn\MailServices\ActiveCampaign\Shared\ActiveCampaignConnection;

class IsValidListIDMiddleware implements IMiddleware{

	public function validation( array $aAdditional = [] ): array {
		$apiKey    = $aAdditional['apiKey'] ?? '';
		$url       = $aAdditional['url'] ?? '';
		$listID    = $aAdditional['listID'] ?? '';
		$aResponse = ActiveCampaignConnection::connect($apiKey, $url)->getListInfo($listID);
		return MessageFactory::factory()->response($aResponse);
	}
}
