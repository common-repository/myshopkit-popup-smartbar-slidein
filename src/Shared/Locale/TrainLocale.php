<?php

namespace MyShopKitPopupSmartBarSlideIn\Shared\Locale;


use Locale;

trait TrainLocale
{
    public function convertCountryCodeToLocale($currency): string
    {
        $aLocales = include MYSHOOKITPSS_PATH . 'src/Shared/Configs/Locales.php';
        $countryCode = array_search($currency, (include MYSHOOKITPSS_PATH . 'src/Shared/Configs/CountryCodeAndCurrency.php'));
        switch ($currency) {
            case 'EUR':
                $locale = 'en-GB';
                break;
            case 'USD':
                $locale = 'en-US';
                break;
            default:
                foreach ($aLocales as $locale) {
                    $localeRegion = Locale::getRegion($locale);
                    $localeLanguage = Locale::getPrimaryLanguage($locale);
                    $aLocale = [
                        'language' => $localeLanguage,
                        'region'   => $localeRegion
                    ];
                    if (strtoupper($countryCode) == $localeRegion) {
                        $locale = locale_compose($aLocale);
                        break;
                    }
                }
                break;
        }

        return !empty($locale)?$locale:'en-EN';
    }
}
