<?php

namespace MyShopKitPopupSmartBarSlideIn\Page\Controllers;

use MyShopKitPopupSmartBarSlideIn\Dashboard\Shared\Option;
use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use WP_REST_Request;

class PageController
{
	private array $aListPostTypeRemove = ['post', 'page', 'attachment'];

	public function __construct()
	{
		add_action('rest_api_init', [$this, 'registerRouters']);
		add_action('wp_ajax_' . MYSHOOKITPSS_PREFIX . 'getPages', [$this, 'ajaxGetPages']);
	}

	public function ajaxGetPages()
	{
		$aParams = $_POST['params'] ?? [];
		$oRequest = new WP_REST_Request();
		if (!empty($aParams)) {
			foreach ($aParams as $key => $val) {
				$oRequest->set_param($key, $val);
			}
		}

		$oResponse = $this->getPages($oRequest);
		MessageFactory::factory('ajax')->success(
			$oResponse->get_data()['message'],
			$oResponse->get_data()['data']
		);
	}

	public function registerRouters()
	{
		register_rest_route(MYSHOOKITPSS_REST, 'pages',
			[
				[
					'methods'             => 'GET',
					'callback'            => [$this, 'getPages'],
					'permission_callback' => '__return_true'
				]
			]
		);
		register_rest_route(MYSHOOKITPSS_REST, 'pages/(?P<id>(\d+))',
			[
				[
					'methods'             => 'GET',
					'callback'            => [$this, 'getPage'],
					'permission_callback' => '__return_true'
				]
			]
		);
	}

	public function getPages(WP_REST_Request $oRequest)
	{
		$aItems = [];
		$limit = $oRequest->get_param('limit') ?: 200;
		if (!Option::isUserLoggedIn($oRequest->get_header('Authorization'))) {
			return MessageFactory::factory('rest')->error(
				esc_html__('Forbidden', 'myshopkit-popup-smartbar-slidein'),
				403
			);
		}
		$aArgs = [
			'number' => $limit
		];
		$aPostTypes = get_post_types(['public' => true], 'objects');
		foreach ($aPostTypes as $key => $oPostType) {
			if (in_array($key, $this->aListPostTypeRemove)) {
				continue;
			}
			$aItems[] = [
				'id'         => uniqid(),
				'title'      => $oPostType->label,
				'handle'     => '(/' . $oPostType->name . ')',
				'body_html'  => '',
				'author'     => '',
				'created_at' => '',
				'updated_at' => '',
				'link'       => ''
			];
		}
		$aGetTaxonomies = get_taxonomies(['public' => true], 'objects');
		foreach ($aGetTaxonomies as $key => $oGetTaxonomies) {
			$aItems[] = [
				'id'         => uniqid(),
				'title'      => $oGetTaxonomies->label,
				'handle'     => '(/' . $oGetTaxonomies->name . ')',
				'body_html'  => '',
				'author'     => '',
				'created_at' => '',
				'updated_at' => '',
				'link'       => ''
			];
		}
		$oPages = get_pages($aArgs);
		if (empty($oPages) || is_wp_error($oPages)) {
			return MessageFactory::factory('rest')->success(
				esc_html__('We found no page', 'myshopkit-popup-smartbar-slidein'),
				[
					'items' => []
				]
			);
		}
		foreach ($oPages as $aPage) {
			$handle = ($aPage->ID != get_option('page_on_front')) ? $aPage->post_name : '';
			$aItems[] = [
				'id'         => (string)$aPage->ID,
				'title'      => $aPage->post_title,
				'handle'     => '/' . $handle,
				'body_html'  => $aPage->post_excerpt,
				'author'     => $aPage->post_author,
				'created_at' => $aPage->post_date,
				'updated_at' => $aPage->post_modified,
				'link'       => $aPage->guid
			];
		}
		return MessageFactory::factory('rest')->success(
			sprintf(esc_html__('We found %s items', 'myshopkit-popup-smartbar-slidein'), count($aItems)),
			[
				'items' => $aItems
			]
		);
	}

	public function getPage(WP_REST_Request $oRequest)
	{
		$aItem = [];
		$pageID = $oRequest->get_param('id');
		if (!Option::isUserLoggedIn($oRequest->get_header('Authorization'))) {
			return MessageFactory::factory('rest')->error(
				esc_html__('Forbidden', 'myshopkit-popup-smartbar-slidein'),
				403
			);
		}
		$aArgs = [
			'include' => [$pageID]
		];
		$oPages = get_pages($aArgs);
		if (empty($oPages) || is_wp_error($oPages)) {
			return MessageFactory::factory('rest')->success(
				esc_html__('We found no page', 'myshopkit-popup-smartbar-slidein'),
				[
					'item' => $aItem
				]
			);
		}
		foreach ($oPages as $aPage) {
			$aItem = [
				'id'         => (string)$aPage->ID,
				'title'      => $aPage->post_title,
				'handle'     => $aPage->post_name,
				'body_html'  => $aPage->post_content,
				'author'     => $aPage->post_author,
				'created_at' => $aPage->post_date,
				'updated_at' => $aPage->post_modified,
				'link'       => $aPage->guid
			];
		}
		return MessageFactory::factory('rest')->success(
			esc_html__('We found items page', 'myshopkit-popup-smartbar-slidein'),
			[
				'item' => $aItem
			]
		);
	}
}
