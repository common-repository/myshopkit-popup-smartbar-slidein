<?php

namespace MyShopKitPopupSmartBarSlideIn\PostScript\Controllers;

use MyShopKitPopupSmartBarSlideIn\Shared\AutoPrefix;
use MyShopKitPopupSmartBarSlideIn\Shared\Locale\TrainLocale;

class PostScriptController
{
    use TrainLocale;

    const MYSHOOKITPSS_GLOBAL = 'MYSHOOKITPSS_GLOBAL';

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    public function enqueueScripts(): bool
    {
        $aDataCampaigns = apply_filters(MYSHOOKITPSS_HOOK_PREFIX .
            'Filter/PostScript/Controllers/PostScriptController/getCampaignsActive', [
            'popup'    => [],
            'smartbar' => [],
            'slidein'  => [],
        ]);

        $currency = !function_exists('get_woocommerce_currency') ? 'USD' : get_woocommerce_currency();
        wp_localize_script('jquery', self::MYSHOOKITPSS_GLOBAL,
            array_merge([
                'restBase' => rest_url(MYSHOOKITPSS_REST_BASE),
                'currency' => $currency,
                'locale'   => $this->convertCountryCodeToLocale($currency),
            ], $aDataCampaigns));
        wp_enqueue_script(
            AutoPrefix::namePrefix('post-script'),
            'https://popup-smartbar-slidein-client.netlify.app/main.js',
            ['jquery'],
            MYSHOOKITPSS_VERSION,
            true
        );
        return true;
    }
}
