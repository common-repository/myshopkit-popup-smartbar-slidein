<?php

namespace MyShopKitPopupSmartBarSlideIn\Dashboard\Controllers;

use Exception;
use MyShopKitPopupSmartBarSlideIn\Dashboard\Shared\GeneralHelper;
use MyShopKitPopupSmartBarSlideIn\Dashboard\Shared\Option;
use MyShopKitPopupSmartBarSlideIn\Dashboard\Shared\TraitSSLDetector;
use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use MyShopKitPopupSmartBarSlideIn\Shared\AutoPrefix;
use WP_Application_Passwords;
use WP_REST_Request;
use WP_User;

class AuthController
{
	use GeneralHelper;
	use TraitSSLDetector;

	const WP_AJAX_GET_CODE_APP_PASS        = 'wp_ajax_' . MYSHOOKITPSS_PREFIX . 'getCodeAuth';
	const WP_AJAX_NOPRIV_GET_CODE_APP_PASS = 'wp_ajax_nopriv_' . MYSHOOKITPSS_PREFIX . 'getCodeAuth';
	const WP_AJAX_REVOKE_PURCHASE_CODE     = 'wp_ajax_' . MYSHOOKITPSS_PREFIX . 'revokePurchaseCode';
	public array $aOptions = [];

	public function __construct()
	{
		add_action(self::WP_AJAX_GET_CODE_APP_PASS, [$this, 'getCodeAuth']);
		add_action(self::WP_AJAX_NOPRIV_GET_CODE_APP_PASS, [$this, 'getCodeAuth']);
		add_action(self::WP_AJAX_REVOKE_PURCHASE_CODE, [$this, 'revokePurchaseCode']);
		//add_action('admin_menu', [$this, 'registerMenu']);
		add_action('rest_api_init', [$this, 'registerRouter']);
		add_filter('determine_current_user', [$this, 'determineCurrentUser']);

		add_action('wp_ajax_mks_popup_purchase_code', [$this, 'ajaxSavePurchaseCode']);
		add_filter('wp_is_application_passwords_available', function () {
			return $this->isSSL();
		}, 9999);
	}


	private function getHeaders(): array
	{
		if (!is_array($_SERVER)) {
			return [];
		}

		$headers = [];
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) == 'HTTP_') {
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
	}

	public function determineCurrentUser($userId)
	{
		if ($this->isSSL()) {
			return $userId;
		}

		$aHeaders = $this->getHeaders();

		if (isset($aHeaders['Authorization']) && !empty($aHeaders['Authorization'])) {
			if (Option::isMatchedNonSSLCode($aHeaders['Authorization'])) {
				$aSuperAdmin = get_super_admins();
				$admin = $aSuperAdmin[0];
				$oUser = get_user_by('login', $admin);
				if (!is_wp_error($oUser)) {
					return $oUser->ID;
				}
			}
		}

		return $userId;
	}

	public static function autoDeleteAuth()
	{
		if (!current_user_can('administrator')) {
			return false;
		}

		if (!class_exists('WP_Application_Passwords')) {
			return false;
		}

		$aOptions = Option::getAuthSettings();
		if (!empty($aOptions['app_password'])) {
			WP_Application_Passwords::delete_application_password(get_current_user_id(), $aOptions['uuid']);
		}

		Option::deleteAuthSettings();
	}

	public static function generateAuth()
	{
		if (!current_user_can('administrator')) {
			return false;
		}

		if (!class_exists('WP_Application_Passwords')) {
			return false;
		}

		self::performGenerateAuth();
	}

	private static function performGenerateAuth()
	{
		$aOptions = Option::getAuthSettings();
		if (!empty($aOptions['app_password'])) {
			WP_Application_Passwords::delete_application_password(get_current_user_id(), $aOptions['uuid']);
		}

		$aResponse = WP_Application_Passwords::create_new_application_password(
			get_current_user_id(),
			[
				'name' => 'myshopkit-popup-smartbar-slidein'
			]
		);

		if (!is_wp_error($aResponse)) {
			Option::saveAuthSettings([
				'username'     => (new WP_User(get_current_user_id()))->user_login,
				'app_password' => $aResponse[0],
				'uuid'         => $aResponse[1]['uuid']
			]);
		}
	}

	public function registerRouter()
	{
		register_rest_route(
			MYSHOOKITPSS_REST,
			'auth',
			[
				[
					'methods'             => 'POST',
					'callback'            => [$this, 'checkFieldsAuth'],
					'permission_callback' => '__return_true'
				]
			]
		);

		register_rest_route(
			MYSHOOKITPSS_REST,
			'purchase-code',
			[
				[
					'methods'             => 'POST',
					'callback'            => [$this, 'savePurchaseCode'],
					'permission_callback' => '__return_true'
				],
				[
					'methods'             => 'GET',
					'callback'            => [$this, 'checkPurchaseCode'],
					'permission_callback' => '__return_true'
				]
			]
		);
	}

	public function checkPurchaseCode(WP_REST_Request $oRequest)
	{
		return MessageFactory::factory('rest')->success('success',
			[
				'hasPurchaseCode' => !empty($this->getToken())
			]
		);
	}

	public function ajaxSavePurchaseCode()
	{
		if (!current_user_can('administrator')) {
			wp_send_json_error();
		}

		if (isset($_POST['params']) && !empty($_POST['params']) && isset($_POST['params']['purchase_code'])) {
			update_option(AutoPrefix::namePrefix('purchase_code'), sanitize_text_field($_POST['params']['purchase_code']));
		}
		wp_send_json_success();
	}

	public function savePurchaseCode(WP_REST_Request $oRequest)
	{
		if (!Option::isUserLoggedIn($oRequest->get_header('Authorization')) ||
			!Option::currentUserCan('administrator', $oRequest->get_header('Authorization'))) {
			return MessageFactory::factory('rest')->error('You must log into the site to use this feature', 403);
		}

		if (empty($oRequest->get_param('purchase_code'))) {
			return MessageFactory::factory('rest')->error('Purchase Code is required', 400);
		}

		update_option(AutoPrefix::namePrefix('purchase_code'), $oRequest->get_param('purchase_code'));
		return MessageFactory::factory('rest')->success('Oke');
	}

	public function checkFieldsAuth(WP_REST_Request $oRequest)
	{
		$username = $oRequest->get_param('username');
		$appPassword = $oRequest->get_param('appPassword');
		try {
			if (empty($username)) {
				throw new Exception(esc_html__('Sorry, the username is required',
					'myshopkit-popup-smartbar-slidein'));
			}
			if (empty($appPassword)) {
				throw new Exception(esc_html__('Sorry, the application password is required',
					'myshopkit-popup-smartbar-slidein'));
			}

			$oUser = wp_authenticate_application_password(null, $username, $appPassword);
			if (empty($oUser) || is_wp_error($oUser)) {
				throw new Exception(esc_html__($oUser->get_error_message(),
					'myshopkit-popup-smartbar-slidein'), 400);
			}

			if (!in_array('administrator', $oUser->roles)) {
				throw new Exception(esc_html__('The application must belong to an Administrator account',
					'myshopkit-popup-smartbar-slidein'));
			}

			Option::saveAuthSettings([
				'username'     => $username,
				'app_password' => $appPassword,
			]);
			return MessageFactory::factory('rest')->success('Passed',
				[
					'hasPassed' => true
				]);
		}
		catch (Exception $exception) {
			return MessageFactory::factory('rest')->error($exception->getMessage(), $exception->getCode());
		}
	}

	public function renderSettings()
	{
		$this->saveOption();
		$this->aOptions = Option::getAuthSettings();

		include plugin_dir_path(__FILE__) . '../Views/AuthSettings.php';
	}

	public function saveOption()
	{
		$aValues = [];
		if (isset($_POST['auth-field']) && !empty($_POST['auth-field'])) {
			if (wp_verify_nonce($_POST['auth-field'], 'auth-action')) {
				if (isset($_POST['myshopkitAuth']) && !empty($_POST['myshopkitAuth'])) {
					foreach ($_POST['myshopkitAuth'] as $key => $val) {
						$aValues[sanitize_text_field($key)] = sanitize_text_field(trim($val));
					}
				}
				Option::saveAuthSettings($aValues);
			}
		}
	}

	public function getCodeAuth()
	{
		try {
			if (!$this->isSSL()) {
				return MessageFactory::factory('ajax')->success('Success', [
					'code' => base64_encode(Option::generateNonSSLCode())
				]);
			}

			$username = Option::getUsername();
			$appPassword = Option::getApplicationPassword();

			if (empty($username) || empty($appPassword)) {
				$aResponse = WP_Application_Passwords::create_new_application_password(
					get_current_user_id(),
					[
						'name' => 'myshopkit-popup-smartbar-slidein'
					]
				);

				if (!is_wp_error($aResponse)) {
					Option::saveAuthSettings([
						'username'     => (new WP_User(get_current_user_id()))->user_login,
						'app_password' => $aResponse[0],
						'uuid'         => $aResponse[1]['uuid']
					]);
				}
				return MessageFactory::factory('ajax')->success('Success', [
					'code' => base64_encode(Option::getUsername() . ':' . Option::getApplicationPassword())
				]);
			}

			add_filter('application_password_is_api_request', '__return_true');
			$oAuthenticated = wp_authenticate_application_password(null, $username, $appPassword);

			if (!$oAuthenticated instanceof WP_User) {
				throw new Exception(
					esc_html__('Invalid Application Username or Token', 'myshopkit-popup-smartbar-slidein'), 400
				);
			}

			if (!in_array('administrator', $oAuthenticated->roles)) {
				throw new Exception(esc_html__('The application must belong to an Administrator account.',
					'myshopkit-popup-smartbar-slidein'), 400);
			}

			self::performGenerateAuth();

			return MessageFactory::factory('ajax')->success('Success', [
				'code' => base64_encode(Option::getUsername() . ':' . Option::getApplicationPassword())
			]);
		}
		catch (Exception $exception) {
			return MessageFactory::factory('ajax')->error($exception->getMessage(), $exception->getCode());
		}
	}

	public function revokePurchaseCode()
	{
		try {
			if (!is_user_logged_in() || !current_user_can('administrator')) {
				throw new Exception(esc_html__('The application must belong to an Administrator account.',
					'myshopkit-popup-smartbar-slidein'), 400);
			}
			$aResult = wp_remote_post('https://wookit.myshopkit.app/wp-json/ev/v1/verifications', [
					'method'      => 'DELETE',
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => [
						'Content-Type: application/json'
					],
					'body'        => [
						'purchaseCode' => $_POST['purchaseCode'],
						'productName'  => 'myshopkit-popup-smartbar-slidein'
					]
				]
			);
			if (is_wp_error($aResult)) {
				throw new Exception($aResult->get_error_message(), $aResult->get_error_code());
			}
			$aResponse = json_decode(wp_remote_retrieve_body($aResult), true);
			if ($aResponse['status'] == 'error') {
				throw new Exception($aResponse['message'], $aResponse['code']);
			}
			update_option(AutoPrefix::namePrefix('purchase_code'), 'free');
			return MessageFactory::factory('ajax')->success($aResponse['message'], $aResponse['code']);
		}
		catch (Exception $exception) {
			return MessageFactory::factory('ajax')->error($exception->getMessage(), $exception->getCode());
		}
	}

	public function registerMenu()
	{
		add_submenu_page(
			$this->getDashboardSlug(),
			esc_html__('Auth Settings', 'myshopkit-popup-smartbar-slidein'),
			esc_html__('Auth Settings', 'myshopkit-popup-smartbar-slidein'),
			'administrator',
			$this->getAuthSlug(),
			[$this, 'renderSettings']
		);
	}
}
