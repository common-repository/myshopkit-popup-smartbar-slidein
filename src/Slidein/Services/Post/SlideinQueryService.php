<?php


namespace MyShopKitPopupSmartBarSlideIn\Slidein\Services\Post;


use MyShopKitPopupSmartBarSlideIn\Shared\AutoPrefix;
use MyShopKitPopupSmartBarSlideIn\Shared\Post\Query\IQueryPost;
use MyShopKitPopupSmartBarSlideIn\Shared\Post\Query\QueryPost;


class SlideinQueryService extends QueryPost implements IQueryPost
{

    public function parseArgs(): IQueryPost
    {
        $this->aArgs = $this->commonParseArgs();

        $this->aArgs['post_type'] = $this->getPostType();

        return $this;
    }

    public function getPostType(): string
    {
        $aConfig = include(plugin_dir_path(__FILE__) . '../../Configs/PostType.php');
        return AutoPrefix::namePrefix($aConfig['post_type']);
    }
}
