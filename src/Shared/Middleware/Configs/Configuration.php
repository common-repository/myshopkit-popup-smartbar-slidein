<?php
return apply_filters(MYSHOOKITPSS_HOOK_PREFIX . 'Filter\Shared\Middleware\Configs\MyShopKitMiddleware',
    [
        'IsUserLoggedIn'                    => 'MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares\IsUserLoggedIn',
        'IsShopLoggedInLowLevelCheck'       => 'MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares\IsShopLoggedInLowLevelCheckMiddleware',
        'IsShopLoggedInHighLevelCheck'      => 'MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares\IsShopLoggedInHighLevelCheckMiddleware',
        'IsValidEmail'                      => 'MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares\IsValidEmailMiddleware',
        'IsReachMaximumPlanAllow'           => 'MyShopKitPopupSmartBarSlideIn\Plans\Middlewares\IsReachMaximumPlanAllowMiddleware',
        'IsDisableMyShopKitBrandMiddleware' => 'MyShopKitPopupSmartBarSlideIn\Plans\Middlewares\IsDisableMyShopKitBrandMiddleware',
        'IsCampaignExist'                   => 'MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares\IsCampaignExistMiddleware',
        'IsCampaignTypeExist'               => 'MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares\IsCampaignTypeExistMiddleware',
        'IsWoocommerceActive'               => 'MyShopKitPopupSmartBarSlideIn\Shared\Middleware\Middlewares\IsWoocommerceActiveMiddleware',
    ]
);
