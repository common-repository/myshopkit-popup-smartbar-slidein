<?php

namespace MyShopKitPopupSmartBarSlideIn\Discount\Controllers;

use MyShopKitPopupSmartBarSlideIn\Dashboard\Shared\Option;
use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use WP_Post;
use WP_Query;
use WP_REST_Request;
use WP_REST_Response;

class DiscountCodeController
{
	public function __construct()
	{
		add_action('rest_api_init', [$this, 'registerRouters']);
		add_action('wp_ajax_' . MYSHOOKITPSS_PREFIX . 'getDiscounts', [$this, 'ajaxGetDiscounts']);
	}

	public function ajaxGetDiscounts()
	{
		$aParams = $_POST['params'] ?? [];
		$oRequest = new WP_REST_Request();
		if (!empty($aParams)) {
			foreach ($aParams as $key => $val) {
				$oRequest->set_param($key, $val);
			}
		}

		$oResponse = $this->getCouponCodes($oRequest);
		MessageFactory::factory('ajax')->success(
			$oResponse->get_data()['message'],
			$oResponse->get_data()['data']
		);
	}

	public function registerRouters()
	{
		register_rest_route(MYSHOOKITPSS_REST, 'coupons',
			[
				[
					'methods'             => 'GET',
					'callback'            => [$this, 'getCouponCodes'],
					'permission_callback' => '__return_true'
				]
			]
		);
	}

	public function getCouponCodes(WP_REST_Request $oRequest): WP_REST_Response
	{
		$aItems = [];
		if (!Option::isUserLoggedIn($oRequest->get_header('Authorization'))) {
			return MessageFactory::factory('rest')->error(
				esc_html__('Forbidden', 'myshopkit-popup-smartbar-slidein'),
				403
			);
		}
		$aArgs = [
			'posts_per_page' => $oRequest->get_param('limit') ?: 50,
			'paged'          => $oRequest->get_param('paged') ?: 1,
			'orderby'        => $oRequest->get_param('orderby') ?: 'post_date',
			'order'          => $oRequest->get_param('order') ?: 'asc',
			'post_type'      => 'shop_coupon',
			'post_status'    => 'publish',
		];

		$oQuery = new WP_Query($aArgs);
		if ($oQuery->have_posts()) {

			while ($oQuery->have_posts()) {
				$oQuery->the_post();
				$dateExpires = (int)get_post_meta(get_the_ID(), 'date_expires', true);
				$conditionDateExpires=empty($dateExpires) || ($dateExpires >= current_time('timestamp', 1));
				$endsAt = empty($dateExpires) ? "" : date('Y-m-d H:i:s', $dateExpires);
				$aItems[] = [
					'code'        => get_the_title(),
					'description' => $conditionDateExpires?get_the_excerpt():
						'This coupon is expired. Please click the link below to create the new or extend the coupon.'
					,
					'endsAt'      => $endsAt,
					'id'          => get_the_ID(),
					'startsAt'    => get_the_date(),
					'status'      =>$conditionDateExpires ? 'ACTIVE' : 'EXPIRES'
				];
			}
			wp_reset_postdata();
		} else {
			return MessageFactory::factory('rest')->success(
				esc_html__('You haven\'t create any coupon yet. Please click here to create a new coupon.',
					'myshopkit-popup-smartbar-slidein'),
				[
					'items' => []
				]
			);
		}
		return MessageFactory::factory('rest')->success(
			sprintf(esc_html__('We found %s items', 'myshopkit-popup-smartbar-slidein'), count($aItems)),
			[
				'items'   => $aItems,
				'paged'   => $aArgs['paged'],
				'maxPage' => $oQuery->max_num_pages
			]
		);
	}
}
