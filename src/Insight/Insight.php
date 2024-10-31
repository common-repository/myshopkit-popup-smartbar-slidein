<?php
//subscriber
use MyShopKitPopupSmartBarSlideIn\Insight\Clicks\Controllers\ClickStatisticAPIController;
use MyShopKitPopupSmartBarSlideIn\Insight\Clicks\Database\ClickStatisticTbl;
use MyShopKitPopupSmartBarSlideIn\Insight\Subscribers\Controllers\SubscriberAPIController;
use MyShopKitPopupSmartBarSlideIn\Insight\Subscribers\Database\SubscriberStatisticTbl;
use MyShopKitPopupSmartBarSlideIn\Insight\Views\Controllers\ViewStatisticAPIController;
use MyShopKitPopupSmartBarSlideIn\Insight\Views\Database\ViewStatisticTbl;

new SubscriberStatisticTbl();
new SubscriberAPIController();
//click Statistic
new ClickStatisticAPIController();
new ClickStatisticTbl();
//view Statistic
new ViewStatisticAPIController();
new ViewStatisticTbl();
