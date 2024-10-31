<?php


namespace MyShopKitPopupSmartBarSlideIn\Shared;


use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;

trait TraitHelper
{
    public function checkPostAndShopExists($postID)
    {
        if (!is_user_logged_in()) {
            return MessageFactory::factory()
                ->error(esc_html__('You must be logged in before performing this function',
                    'myshopkit-popup-smartbar-slidein'), 401);
        }

        if (get_post_status($postID) !== 'publish') {
            return MessageFactory::factory()
                ->error(esc_html__('Sorry, the Popup doest not exist at the moment', 'myshopkit-popup-smartbar-slidein'),
                    401);
        }

        return MessageFactory::factory()->success("Success");
    }
}
