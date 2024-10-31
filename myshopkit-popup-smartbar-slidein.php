<?php
/**
 * Plugin Name: WooCommerce Coupon Popup, SmartBar, Slide In | MyShopKit
 * Plugin URI: https://popup-smartbar-slidein.myshopkit.app
 * Description: The one kit to boost sales
 * Version: 1.0.9
 * Author: Wiloke
 * Author URI: https://woocommerce.myshopkit.app/
 * Text Domain: myshopkit-popup-smartbar-slidein
 */

add_action('admin_notices', function () {
	if (version_compare(PHP_VERSION, '7.4', '<')) {
		?>
        <div class="notice notice-error" style="padding: 20px; border-left:  4px solid #dc3232; color: red;">
			<?php esc_html_e('In order to use MyShopKit Popup SmartBar SlideIn plugin, you need to upgrade PHP version to 7.4. Please contact your hosting 
            provider to report this issue.', 'myshopkit-popup-smartbar-slidein'); ?>
        </div>
		<?php
		return false;
	}

	if (!function_exists('locale_get_region')) {
		?>
        <div class="notice notice-error" style="padding: 20px; border-left:  4px solid #dc3232; color: red;">
			<?php esc_html_e('Missing PHP intl extension. Please contact your hosting provider to report this issue',
				'myshopkit-popup-smartbar-slidein'); ?>
        </div>
		<?php
		return false;
	}
});


define('MYSHOOKITPSS_VERSION', '1.0.9');
define('MYSHOOKITPSS_NAMESPACE', 'mskpss');
define('MYSHOOKITPSS_HOOK_PREFIX', 'mskpss/');
define('MYSHOOKITPSS_PREFIX', 'mskpss_');
define('MYSHOOKITPSS_REST_VERSION', 'v1');
define('MYSHOOKITPSS_REST_BASE', MYSHOOKITPSS_NAMESPACE . '/' . MYSHOOKITPSS_REST_VERSION);
define('MYSHOOKITPSS_REST_NAMESPACE', 'mskpss');
define('MYSHOOKITPSS_DS', '/');

define('MYSHOOKITPSS_REST', MYSHOOKITPSS_REST_NAMESPACE . MYSHOOKITPSS_DS . MYSHOOKITPSS_REST_VERSION);
define('MYSHOOKITPSS_URL', plugin_dir_url(__FILE__));
define('MYSHOOKITPSS_PATH', plugin_dir_path(__FILE__));


use MyShopKitPopupSmartBarSlideIn\Dashboard\Controllers\AuthController;
use MyShopKitPopupSmartBarSlideIn\MailServices;
use MyShopKitPopupSmartBarSlideIn\Shared\App;
use MyShopKitPopupSmartBarSlideIn\Shared\AutoPrefix;

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

//Tao file ConfigTemplate
App::bind('TemplateMeta', require_once(MYSHOOKITPSS_PATH . 'src/Shared/Configs/TemplateMeta.php'));

require_once(MYSHOOKITPSS_PATH . 'src/MailServices/MailServices.php');
require_once(MYSHOOKITPSS_PATH . 'src/Insight/Insight.php');
require_once(MYSHOOKITPSS_PATH . 'src/SmartBar/smartbar.php');
require_once(MYSHOOKITPSS_PATH . 'src/Popup/popup.php');
require_once(MYSHOOKITPSS_PATH . 'src/Discount/Discount.php');
require_once(MYSHOOKITPSS_PATH . 'src/Dashboard/Dashboard.php');
require_once(MYSHOOKITPSS_PATH . 'src/Product/Product.php');
require_once(MYSHOOKITPSS_PATH . 'src/Page/Page.php');
require_once(MYSHOOKITPSS_PATH . 'src/General/General.php');
require_once(MYSHOOKITPSS_PATH . 'src/PostScript/PostScript.php');
require_once(MYSHOOKITPSS_PATH . 'src/Images/Images.php');
require_once(MYSHOOKITPSS_PATH . 'src/Slidein/Slidein.php');

register_activation_hook(__FILE__, function () {
	AuthController::generateAuth();
});

register_deactivation_hook(__FILE__, function () {
	AuthController::autoDeleteAuth();
});


add_action('plugins_loaded', 'myshopkitPopupSmartbarSlideinAfterPluginsLoaded');
function myshopkitPopupSmartbarSlideinAfterPluginsLoaded()
{
	load_plugin_textdomain('myshopkit-popup-smartbar-slidein', false, basename(dirname(__DIR__)) . '/languages');
}

register_activation_hook(__FILE__, ['\MyShopKitPopupSmartBarSlideIn\PostTypeConverter\Controllers\PostTypeConverter', 'convertToNewPostTypes']);
