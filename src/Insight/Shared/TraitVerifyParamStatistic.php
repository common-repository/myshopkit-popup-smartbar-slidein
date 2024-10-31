<?php


namespace MyShopKitPopupSmartBarSlideIn\Insight\Shared;


use Exception;

trait TraitVerifyParamStatistic
{
    /**
     * @throws Exception
     */
    public function verifyParamStatistic($postType, $filter = '', $aAdditional = []): bool
    {
        if (empty($postType) || !in_array($postType, ['smartbar', 'popup', 'slidein'])) {
            throw new Exception(esc_html__('The post type is required',
                'myshopkit-popup-smartbar-slidein'), 400);
        }
        if ($filter == 'custom') {
            if (empty($aAdditional['from'])) {
                throw new Exception(esc_html__('The start is required',
                    'myshopkit-popup-smartbar-slidein'), 400);
            }
            if (empty($aAdditional['to'])) {
                throw new Exception(esc_html__('The end is required',
                    'myshopkit-popup-smartbar-slidein'), 400);
            }
        }
        return true;
    }
}
