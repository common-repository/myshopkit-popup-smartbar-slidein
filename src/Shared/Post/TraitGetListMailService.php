<?php


namespace MyShopKitPopupSmartBarSlideIn\Shared\Post;


trait TraitGetListMailService
{
    public function getListMailService(): array
    {
        return apply_filters(MYSHOOKITPSS_HOOK_PREFIX . 'Filter/Shared/Post/TraitGetListMailService', []);
    }
}
