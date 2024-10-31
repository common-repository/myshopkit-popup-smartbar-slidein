<?php

namespace MyShopKitPopupSmartBarSlideIn\Dashboard\Shared;

use MyShopKitPopupSmartBarSlideIn\Shared\AutoPrefix;

class Option
{
	private static string $optionKey = 'auth';

	private static array $aDataOptions = [];

	public static function saveAuthSettings(array $aValues)
	{
		update_option(AutoPrefix::namePrefix(self::$optionKey), $aValues);
	}

	public static function getUniqueCode()
	{
		return get_option('msk_unique_code');
	}


	public static function setUniqueueCode()
	{
		update_option('msk_unique_code', uniqid(''));
	}

	public static function isUserLoggedIn($authCode) {
		if (is_user_logged_in()) {
			return true;
		}

		if (!is_ssl()){
			return self::isMatchedNonSSLCode($authCode);
		}

		return false;
	}

	private static function cleanAuthcode($authCode)
	{
		return str_replace(["Basic ", "basic "], ["", ""], $authCode);
	}

	public static function currentUserCan($role, $authCode = "")
	{
		if (current_user_can($role)) {
			return true;
		}

		if (!empty($authCode)) {
			if (self::isMatchedNonSSLCode($authCode)) {
				return true;
			}
		}

		return false;
	}

	public static function getCurrentUserId($authCode): int
	{
		if (is_user_logged_in()) {
			return get_current_user_id();
		}

		if (!is_ssl()){
			if (self::isMatchedNonSSLCode($authCode)) {
				$authCode = self::cleanAuthcode($authCode);
				$aExplore = explode("-", base64_decode($authCode));
				$userId = end($aExplore);
				return absint($userId);
			}
		}

		return 0;
	}

	public static function generateNonSSLCode()
	{
		$website = base64_encode(home_url('/'));
		$time = time();
		$code = $website."-".$time.self::getUniqueCode()."-".get_current_user_id();
		update_option('mks_nonssl_token', $code);

		return $code;
	}


	public static function isMatchedNonSSLCode($authCode)
	{
		$currentCode = get_option('mks_nonssl_token');
		if (empty($currentCode)) {
			return false;
		}


		$authCode = self::cleanAuthcode($authCode);
		return base64_encode($currentCode) == $authCode;
	}

	public static function deleteAuthSettings()
	{
		delete_option(AutoPrefix::namePrefix(self::$optionKey));
	}

	public static function getUsername()
	{
		return self::getAuthField('username', '');
	}

	public static function getAuthField($field, $default = '')
	{
		self::getAuthSettings();
		return self::$aDataOptions[$field] ?? $default;
	}

	public static function getAuthSettings()
	{
		self::$aDataOptions = get_option(AutoPrefix::namePrefix(self::$optionKey)) ?: [];
		if (empty(self::$aDataOptions)) {
			self::$aDataOptions = ['username' => '', 'app_password' => '', 'uuid' => ''];
		}
		return self::$aDataOptions;
	}

	public static function getApplicationPassword()
	{
		return self::getAuthField('app_password', '');
	}
}
