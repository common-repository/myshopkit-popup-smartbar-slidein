<?php

namespace MyShopKitPopupSmartBarSlideIn\MailServices\Shared;

use MyShopKitPopupSmartBarSlideIn\Dashboard\Shared\Option;
use MyShopKitPopupSmartBarSlideIn\Illuminate\Message\MessageFactory;
use WP_REST_Request;

trait TraitMailServicesValidation
{
	use TraitMailServicesConfiguration;

	/**
	 * @param string|null $email
	 *
	 * @return array
	 */
	protected function checkIsEmailValid(?string $email): array
	{
		if (!empty($email)) {
			if (is_email($email)) {
				return MessageFactory::factory()->success('OK');
			}

			return MessageFactory::factory()->error(esc_html__('Oops! The email is invalid, please re-check it.',
				'myshopkit'), 400);
		}

		return MessageFactory::factory()
			->error(esc_html__('Oops! The email field is empty, please re-check it.'), 400);
	}

	/**
	 * @return array
	 */

	protected function checkIsUserLoggedIn(WP_REST_Request $oRequest = null): array
	{
		if (empty($oRequest)) {
			if (is_user_logged_in()) {
				return MessageFactory::factory()->success('OK');
			}
		} else {
			if (Option::isUserLoggedIn($oRequest->get_header('Authorization'))) {
				return MessageFactory::factory()->success('OK');
			}
		}

		return MessageFactory::factory()->error(esc_html__('Please log in first.', 'myshopkit'), 401);
	}

	protected function checkIsCampaignIDValid($campaignID): array
	{
		if (get_post_status($campaignID)) {
			return MessageFactory::factory()->success('OK');
		}

		return MessageFactory::factory()
			->error(esc_html__('Oops! Look Like this campaign is invalid, please re-check it!',
				'myshopkit'), 400);
	}

}
