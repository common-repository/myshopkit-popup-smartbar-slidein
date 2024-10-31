<?php

namespace MyShopKitPopupSmartBarSlideIn\Dashboard\Shared;

trait TraitSSLDetector
{
	private function isSSL(): bool
	{
		$aWhiteListed = [
			'127.0.0.1',
			'::1'
		];

		if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'localhost') {
			return true;
		}

		if(in_array($_SERVER['REMOTE_ADDR'], $aWhiteListed)){
			return true;
		}

		return is_ssl();
	}
}
