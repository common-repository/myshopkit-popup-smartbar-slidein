<?php

namespace MyShopKitPopupSmartBarSlideIn\Insight\Subscribers\Controllers;

use Exception;
use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use MyShopKitPopupSmartBarSlideIn\Insight\Interfaces\IInsightController;
use MyShopKitPopupSmartBarSlideIn\Insight\Shared\TraitCheckCustomDateInMonth;
use MyShopKitPopupSmartBarSlideIn\Insight\Shared\TraitReportStatistic;
use MyShopKitPopupSmartBarSlideIn\Insight\Shared\TraitUpdateDeleteCreateInsightValidation;
use MyShopKitPopupSmartBarSlideIn\Insight\Shared\TraitVerifyParamStatistic;
use MyShopKitPopupSmartBarSlideIn\Insight\Subscribers\Database\SubscriberStatisticTbl;
use MyShopKitPopupSmartBarSlideIn\Shared\AutoPrefix;
use WP_REST_Request;

class SubscriberAPIController implements IInsightController
{
    use TraitUpdateDeleteCreateInsightValidation;
    use TraitCheckCustomDateInMonth;
    use TraitReportStatistic;
    use TraitVerifyParamStatistic;

    private string $postType = '';

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'registerRouters']);
	    add_action('wp_ajax_' . MYSHOOKITPSS_PREFIX . 'getPopupSubscribers', [$this, 'ajaxGetSubscribers']);
	    add_action('wp_ajax_' . MYSHOOKITPSS_PREFIX . 'getSmartBarSubscribers', [$this, 'ajaxGetSubscribers']);
	    add_action('wp_ajax_' . MYSHOOKITPSS_PREFIX . 'getSlideInSubscribers', [$this, 'ajaxGetSubscribers']);
    }
	public function ajaxGetSubscribers()
	{
		$aParams = $_POST['params'] ?? [];
		$oRequest = new WP_REST_Request();
		if (!empty($aParams)) {
			foreach ($aParams as $key => $val) {
				$oRequest->set_param($key, $val);
			}
		}

		$oResponse = $this->reportSubscribers($oRequest);
		MessageFactory::factory('ajax')->success(
			$oResponse->get_data()['message'],
			$oResponse->get_data()['data']
		);
	}

    public function registerRouters()
    {
        register_rest_route(MYSHOOKITPSS_REST, 'insights/subscribers',
            [
                [
                    'methods'             => 'GET',
                    'callback'            => [$this, 'reportSubscribers'],
                    'permission_callback' => '__return_true'
                ]
            ]
        );
    }

    public function reportSubscribers(WP_REST_Request $oRequest)
    {
        $aAdditional = [
            'from' => $oRequest->get_param('from'),
            'to'   => $oRequest->get_param('to')
        ];
        $postType = $oRequest->get_param('postType');
        $filter = $oRequest->get_param('filter');
        $filter = empty($filter) ? 'today' : $filter;

        try {
            $this->verifyParamStatistic($postType, $filter, $aAdditional);
            $this->postType = AutoPrefix::namePrefix($postType);
            $aData = $this->getReport($filter, $aAdditional);
            return MessageFactory::factory('rest')->success(
                'success',
                [
                    'type'     => 'subscriber',
                    'summary'  => $aData['summary'],
                    'timeline' => $aData['timeline']
                ]
            );
        } catch (Exception $exception) {
            return MessageFactory::factory('rest')->error($exception->getMessage(), $exception->getCode());
        }
    }

    public function getTable(): string
    {
        return SubscriberStatisticTbl::getTblName();
    }

    public function getPostType(): string
    {
        return $this->postType;
    }

    public function generateResponseClass(string $queryFilter): string
    {
        $ucFirstFilter = ucfirst($queryFilter);
        return "MyShopKitPopupSmartBarSlideIn\Insight\Shared\\$ucFirstFilter\\$ucFirstFilter" . "Response";
    }

    public function generateQueryClass(string $queryFilter): string
    {
        $ucFirstFilter = ucfirst($queryFilter);
        return "MyShopKitPopupSmartBarSlideIn\Insight\Shared\\$ucFirstFilter\\$ucFirstFilter" . "Query";
    }

    public function getSummary(): string
    {
        return "Count(email) as summary";
    }
}
