<?php

namespace MyShopKitPopupSmartBarSlideIn\Slidein\Services\Post;



use MyShopKitPopupSmartBarSlideIn\Insight\Clicks\Models\ClickStatisticModel;
use MyShopKitPopupSmartBarSlideIn\Insight\Subscribers\Models\SubscriberStatisticModel;
use MyShopKitPopupSmartBarSlideIn\Insight\Views\Models\ViewStatisticModel;
use MyShopKitPopupSmartBarSlideIn\Shared\Post\Query\PostSkeleton;
use MyShopKitPopupSmartBarSlideIn\Shared\Post\TraitGetListMailService;
use MyShopKitPopupSmartBarSlideIn\Shared\Post\TraitHandleConversion;

class PostSkeletonService extends PostSkeleton
{
    use TraitHandleConversion,TraitGetListMailService;

    public function getConversion(): int
    {
        return $this->handlerConversion($this->getGoal(), [
            'getViews'       => $this->getViews(),
            'getClicks'      => $this->getClicks(),
            'getSubscribers' => $this->getSubscribers()
        ]);
    }

    public function getGoal(): string
    {
        $aConfig = $this->getConfig();

        return $aConfig['goal'] ?? '';
    }

    public function getViews(): int
    {
        $postID = (int)$this->oPost->ID;
        return ViewStatisticModel::getViewsWithPostID($postID);
    }

    public function getClicks(): int
    {
        $postID = (int)$this->oPost->ID;
        return ClickStatisticModel::getClicksWithPostID($postID);
    }

    public function getSubscribers(): int
    {
        $postID = (string)$this->oPost->ID;
        return SubscriberStatisticModel::countAllWithPostID($postID);
    }
}
