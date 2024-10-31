<?php

namespace MyShopKitPopupSmartBarSlideIn\Dashboard\Controllers;

use Automattic\WooCommerce\Blocks\RestApi;
use MyShopKitPopupSmartBarSlideIn\Dashboard\Shared\GeneralHelper;
use MyShopKitPopupSmartBarSlideIn\Dashboard\Shared\TraitSSLDetector;
use MyShopKitPopupSmartBarSlideIn\Shared\AutoPrefix;

class DashboardController
{
	use GeneralHelper;
	use TraitSSLDetector;

	const MYSHOOKITPSS_GLOBAL = 'MYSHOOKITPSS_GLOBAL';
	private string $myshopkitpssEditor = 'https://wookit.netlify.app/';

	public function __construct()
	{
		add_action('admin_menu', [$this, 'registerMenu']);
		add_action('admin_enqueue_scripts', [$this, 'enqueueScriptsToDashboard']);
	}

	public function enqueueScriptsToDashboard($hook): bool
	{
		wp_localize_script('jquery', self::MYSHOOKITPSS_GLOBAL, [
			'url'                => admin_url('admin-ajax.php'),
			'purchaseCodeAction' => 'mks_popup_purchase_code',
			'purchaseCodeUrl'    => add_query_arg([
				'action' => 'mks_popup_purchase_code'
			], admin_url('admin-ajax.php')),
			'restBase'           => trailingslashit(rest_url(MYSHOOKITPSS_REST_BASE)),
			'email'              => get_option('admin_email'),
			'clientSite'         => home_url('/'),
			'purchaseCode'       => $this->getToken(),
			'purchaseCodeLink'   => 'https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code',
			'tidio'              => 'bdzedo8yftsclnwmwmbcqcsyscbk4rtl'
		]);

		if ((strpos($hook, $this->getDashboardSlug()) !== false) || (strpos($hook, $this->getAuthSlug()) !== false)) {
			// enqueue script
			wp_enqueue_script(
				AutoPrefix::namePrefix('dashboard-script'),
				plugin_dir_url(__FILE__) . '../Assets/Js/Script.js',
				['jquery'],
				MYSHOOKITPSS_VERSION,
				true
			);


			wp_enqueue_style(
				AutoPrefix::namePrefix('dashboard-style'),
				plugin_dir_url(__FILE__) . '../Assets/Css/Style.css',
				[],
				MYSHOOKITPSS_VERSION,
				'media'
			);
		}
		return false;
	}

	public function registerMenu()
	{
		add_menu_page(
			esc_html__('MyShopKit Popup SmartBar SlideIn Dashboard', 'myshopkit-popup-smartbar-slidein'),
			esc_html__('MyShopKit Popup SmartBar SlideIn Dashboard', 'myshopkit-popup-smartbar-slidein'),
			'manage_options',
			$this->getDashboardSlug(),
			[$this, 'renderSettings'],
			'dashicons-admin-network'
		);
	}

	public function renderSettings()
	{
		?>
		<div id="myshopkitpss-dashboard">
			<iframe id="shopkit-iframe" src="<?php echo esc_url($this->getIframe()); ?>" width="1500"
			        height="750"></iframe>
		</div>
		<?php
	}

	private function getIframe(): string
	{
		return $this->myshopkitpssEditor;
	}
}
