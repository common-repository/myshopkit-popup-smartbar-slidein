<form method="POST" action="<?php use MyShopKitPopupSmartBarSlideIn\Shared\AutoPrefix;

echo admin_url('admin.php?page=' . $this->getAuthSlug()); ?>">
    <?php wp_nonce_field('auth-action', 'auth-field'); ?>
    <table class="form-table">
        <thead>
        <tr>
            <th><?php echo esc_html__('Auth Settings', 'myshopkit-popup-smartbar-slidein'); ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th>
                <label for="myshopkitpss-username"><?php echo esc_html__('Username', 'myshopkit-popup-smartbar-slidein'); ?></label>
            </th>
            <td>
                <input id="myshopkitpss-username" type="text" name="myshopkitAuth[username]"
                       value="<?php echo esc_attr($this->aOptions['username']); ?>" required class="regular-text"/>
            </td>
        </tr>
        <tr>
            <th><label for="myshopkit-app-password"><?php echo esc_html__('Application Password',
                        'myshopkit-popup-smartbar-slidein'); ?></label>
            </th>
            <td>
                <input id="myshopkit-app-password" type="password" name="myshopkitAuth[app_password]"
                       value="<?php echo esc_attr($this->aOptions['app_password']); ?>" required class="regular-text"/>
            </td>
        </tr>
        </tbody>
    </table>
    <button id="button-save" class="button button-primary" type="submit"><?php esc_html_e('Save Changes',
            'myshopkit-popup-smartbar-slidein'); ?></button>
</form>
<?php if (!empty(get_option(AutoPrefix::namePrefix('purchase_code')))): ?>
    <button id="btn-Revoke-Purchase-Code" class="button button-primary" style="margin-top: 20px;background-color:
    red"><?php esc_html_e
        ('Revoke Purchase Code',
            'myshopkit-popup-smartbar-slidein'); ?></button>
<?php endif; ?>
