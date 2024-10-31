<?php


namespace MyShopKitPopupSmartBarSlideIn\Shared\Post;


use MyShopKitPopupSmartBarSlideIn\Shared\AutoPrefix;

trait TraitHandleGetShowOnPageCampaign
{
    public function getShowOnPageCampaign($postID)
    {
        if (get_post_meta($postID, AutoPrefix::namePrefix('showOnPageMode'), true) == 'all') {
            $showOnPage = 'all';
        } else {
            $showOnPage = get_post_meta($postID, AutoPrefix::namePrefix('showOnPage'));
        }
        return $showOnPage;
    }
}
