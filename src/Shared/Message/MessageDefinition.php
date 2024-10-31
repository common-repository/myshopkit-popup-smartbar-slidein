<?php


namespace MyShopKitPopupSmartBarSlideIn\Shared\Message;


class MessageDefinition {
	public static function youMustLogin(): string {
		return esc_html__( 'You must be logged in before performing this function',
			'myshopkit-popup-smartbar-slidein' );
	}
}
