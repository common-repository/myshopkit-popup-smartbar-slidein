<?php
use MyShopKitPopupSmartBarSlideIn\MailServices\ActiveCampaign\Controllers\ActiveCampaignController;
use MyShopKitPopupSmartBarSlideIn\MailServices\CampaignMonitor\Controllers\CampaignMonitorController;
use MyShopKitPopupSmartBarSlideIn\MailServices\General\Controllers\GeneralMailServicesController;
use MyShopKitPopupSmartBarSlideIn\MailServices\GetResponse\Controllers\GetResponseController;
use MyShopKitPopupSmartBarSlideIn\MailServices\iContact\src\Controllers\iContactController;
use MyShopKitPopupSmartBarSlideIn\MailServices\Klaviyo\Controllers\KlaviyoController;
use MyShopKitPopupSmartBarSlideIn\MailServices\MailChimp\Controllers\MailChimpController;

include (plugin_dir_path(__FILE__). 'iContact/src/icontact-api-php-master/lib/iContactApi.php');
define('MYSHOPKIT_LINK', 'https://doc.myshopkit.app'. MYSHOOKITPSS_DS);

new GeneralMailServicesController();
new ActiveCampaignController();
new CampaignMonitorController();
new GetResponseController();
new iContactController();
new MailChimpController();
new KlaviyoController();


