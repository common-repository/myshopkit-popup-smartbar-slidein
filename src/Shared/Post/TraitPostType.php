<?php


namespace MyShopKitPopupSmartBarSlideIn\Shared\Post;


use Exception;
use MyShopKitPopupSmartBarSlideIn\Shared\AutoPrefix;

trait TraitPostType
{

    /**
     * @throws Exception
     */
    public function isPostType($id, $postType): bool
    {
        if (get_post_field('post_type', $id) != $postType) {
            throw new Exception(sprintf(esc_html__('Unfortunately, this item is not a %s',
                'myshopkit-popup-smartbar-slidein'), $postType));
        }

        return true;
    }

    public function getPostType(string $configPath): string
    {
        $aConfig = include trailingslashit($configPath) . 'PostType.php';

        return AutoPrefix::namePrefix($aConfig['post_type']);
    }
}
