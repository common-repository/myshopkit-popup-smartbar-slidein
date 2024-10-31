<?php


namespace MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares;


use Exception;
use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;

class IsCampaignTypeExistMiddleware implements IMiddleware
{
    private array $aPostType
        = [
		    MYSHOOKITPSS_PREFIX . 'popup',
		    MYSHOOKITPSS_PREFIX . 'smartbar',
		    MYSHOOKITPSS_PREFIX . 'slidein',
        ];

    /**
     * @throws Exception
     */
    public function validation(array $aAdditional = []): array
    {
        $postType = $aAdditional['postType'] ?? '';
        if (empty($postType)) {
            throw new Exception('Sorry, the param postType is require', 400);
        }
        if (!in_array($postType, $this->aPostType)) {
            throw new Exception('Sorry, the item is no longer available', 400);
        }
        return MessageFactory::factory()->success('Passed', true);
    }
}
