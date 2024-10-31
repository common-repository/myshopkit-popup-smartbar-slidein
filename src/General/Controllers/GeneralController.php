<?php

namespace MyShopKitPopupSmartBarSlideIn\General\Controllers;

use Exception;
use MyShopKitPopupSmartBarSlideIn\Dashboard\Shared\Option;
use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use MyShopKitPopupSmartBarSlideIn\Shared\Message\MessageDefinition;
use WP_REST_Request;

class GeneralController
{

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'registerRouter']);
	    add_action('wp_ajax_' . MYSHOOKITPSS_PREFIX . 'getCampaignStatus', [$this, 'ajaxGetCampaignStatus']);
	    add_action('wp_ajax_' . MYSHOOKITPSS_PREFIX . 'getShopInfo', [$this, 'ajaxGetShopInfo']);
    }
	public function ajaxGetCampaignStatus()
	{
		$aParams = $_POST['params'] ?? [];
		$oRequest = new WP_REST_Request();
		if (!empty($aParams)) {
			foreach ($aParams as $key => $val) {
				$oRequest->set_param($key, $val);
			}
		}

		$oResponse = $this->getCampaignsStatus($oRequest);
		MessageFactory::factory('ajax')->success(
			$oResponse->get_data()['message'],
			$oResponse->get_data()['data']
		);
	}

	public function ajaxGetShopInfo()
	{
		$aParams = $_POST['params'] ?? [];
		$oRequest = new WP_REST_Request();
		if (!empty($aParams)) {
			foreach ($aParams as $key => $val) {
				$oRequest->set_param($key, $val);
			}
		}

		$oResponse = $this->getShopInfo($oRequest);
		MessageFactory::factory('ajax')->success(
			$oResponse->get_data()['message'],
			$oResponse->get_data()['data']
		);
	}

    public function registerRouter()
    {
        register_rest_route(
            MYSHOOKITPSS_REST,
            'campaigns/status',
            [
                'methods'             => 'GET',
                'callback'            => [$this, 'getCampaignsStatus'],
                'permission_callback' => '__return_true'
            ]
        );
        register_rest_route(
            MYSHOOKITPSS_REST,
            'shop-info',
            [
                'methods'             => 'GET',
                'callback'            => [$this, 'getShopInfo'],
                'permission_callback' => '__return_true'
            ]
        );
    }

    public function getCampaignsStatus(WP_REST_Request $oRequest)
    {
        try {
	        if (!Option::isUserLoggedIn($oRequest->get_header('Authorization'))) {
                return MessageFactory::factory('rest')
                    ->error(MessageDefinition::youMustLogin(), 401);
            }
            $aResponse = apply_filters(MYSHOOKITPSS_HOOK_PREFIX .
                'Filter/General/Controllers/GeneralController/getCampaignsStatus',
                [
                    'hasPopup'    => false,
                    'hasSmartbar' => false,
                    'hasSlidein'  => false,

                ],
                [
                    'userID' => get_current_user_id()
                ]
            );
            return MessageFactory::factory('rest')->success('Campaigns Status',
                [
                    'hasPopup'    => $aResponse['hasPopup'],
                    'hasSmartbar' => $aResponse['hasSmartbar'],
                    'hasSlidein'  => $aResponse['hasSlidein'],
                ]
            );
        } catch (Exception $exception) {
            return MessageFactory::factory('rest')->error($exception->getMessage(), $exception->getCode());
        }
    }

    public function getShopInfo(WP_REST_Request $oRequest)
    {
        try {
            if (!Option::isUserLoggedIn($oRequest->get_header('Authorization'))) {
                return MessageFactory::factory('rest')
                    ->error(MessageDefinition::youMustLogin(), 401);
            }

            return MessageFactory::factory('rest')->success('Shop Info',
                [
                    'locale'       => str_replace('_', '-', get_locale()),
                    'currency'     => !function_exists('get_woocommerce_currency') ? 'USD' : get_woocommerce_currency(),
                    'site'         => get_bloginfo('name'),
                    'linkDiscount' => add_query_arg([
                        'post_type' => 'shop_coupon'
                    ], admin_url('post-new.php')),
                ]
            );
        } catch (Exception $exception) {
            return MessageFactory::factory('rest')->error($exception->getMessage(), $exception->getCode());
        }
    }
}
