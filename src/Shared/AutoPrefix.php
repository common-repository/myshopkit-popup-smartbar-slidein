<?php

namespace MyShopKitPopupSmartBarSlideIn\Shared;

class AutoPrefix {
	public static function namePrefix( $name ) {
		return strpos( $name, MYSHOOKITPSS_PREFIX ) === 0 ? $name : MYSHOOKITPSS_PREFIX . $name;
	}

	public static function removePrefix( string $name ): string {
		if ( strpos( $name, MYSHOOKITPSS_PREFIX ) === 0 ) {
			$name = str_replace( MYSHOOKITPSS_PREFIX, '', $name );
		}

		return $name;
	}
}
