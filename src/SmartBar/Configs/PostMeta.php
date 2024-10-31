<?php


use MyShopKitPopupSmartBarSlideIn\Shared\App;
use MyShopKitPopupSmartBarSlideIn\Shared\AutoPrefix;

$aDefaultTemplate = [];
$aDefaultShowOnPageMode = '';

if (isset($_GET['post']) && ($_GET['action'] === 'edit')) {
    $aDefaultTemplate = get_post_meta($_GET['post'], AutoPrefix::namePrefix('showOnPage'));
    $aDefaultShowOnPageMode = get_post_meta($_GET['post'], AutoPrefix::namePrefix('showOnPageMode'), true);
}
$aOptionTemplates = App::get('TemplateMeta');
return [
    'smartbar_general_settings_section' => [
        'id'           => 'smartbar_general_settings_section',
        'title'        => esc_html__('Smart Bar Settings', 'myshopkit-popup-smartbar-slidein'),
        'object_types' => [AutoPrefix::namePrefix('smartbar')],
        'fields'       => [
            'config'         => [
                'name'       => esc_html__('Smart Bar Configuration', 'myshopkit-popup-smartbar-slidein'),
                'save_field' => false,
                'id'         => 'config',
                'type'       => 'textarea'
            ],
            'showOnPageMode' => [
                'name'             => esc_html__('Show On Page Mode', 'myshopkit-popup-smartbar-slidein'),
                'id'               => 'showOnPageMode',
                'type'             => 'select',
                'save_field'       => false,
                'default'          => $aDefaultShowOnPageMode,
                'show_option_none' => true,
                'options'          => [
                    'all'             => esc_html__('All Templates', 'myshopkit-popup-smartbar-slidein'),
                    'specified_pages' => esc_html__('Specified Pages', 'myshopkit-popup-smartbar-slidein'),
                ],
            ],
            'showOnPage'     => [
                'name'       => esc_html__('Display Templates', 'myshopkit-popup-smartbar-slidein'),
                'id'         => 'showOnPage',
                'type'       => 'multicheck',
                'save_field' => false,
                'multiple'   => true,
                'default'    => $aDefaultTemplate,
                'options'    => $aOptionTemplates
            ],
        ]
    ]
];
