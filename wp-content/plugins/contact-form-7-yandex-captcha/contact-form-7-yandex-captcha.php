<?php
/*
Plugin Name: Contact Form 7 Yandex Captcha
Description: Add Yandex captcha to Contact Form 7 using [cf7-yandex-captcha] shortcode
Version: 0.1
Author: towp.ru
*/

$cf7yac_site_key = get_option('cf7yac_site_key');
$cf7yac_server_key = get_option( 'cf7yac_server_key' );
if (!empty($cf7yac_site_key) && !empty($cf7yac_server_key) && !is_admin()) {
    function enqueue_cf7sr_script() {
        global $cf7yac;
        if (!$cf7yac) { return; }
        $cf7yac_site_key = get_option( 'cf7yac_site_key' );
        ?>
        <script src="https://captcha-api.yandex.ru/captcha.js" defer></script>
        <?php
    }
    add_action('wp_footer', 'enqueue_cf7sr_script');

    function cf7sr_wpcf7_form_elements($form) {
        $form = do_shortcode($form);
        return $form;
    }
    add_filter('wpcf7_form_elements', 'cf7sr_wpcf7_form_elements');

    function cf7sr_shortcode($atts) {
        global $cf7yac;
        $cf7yac = true;
        $cf7yac_site_key = get_option('cf7yac_site_key');
        return '<div id="captcha-container" class="smart-captcha" data-sitekey="'.esc_attr($cf7yac_site_key).'"></div>
        <span class="wpcf7-form-control-wrap cf7-yandex-captcha" data-name="cf7-yandex-captcha"><input type="hidden" name="cf7-yandex-captcha" value="" class="wpcf7-form-control"></span>';
    }
    add_shortcode('cf7-yandex-captcha', 'cf7sr_shortcode');



    function check_yandex_captcha($token) {
        $cf7yac_server_key = get_option('cf7yac_server_key');

        $ch = curl_init();
        $args = http_build_query([
            "secret" => $cf7yac_server_key,
            "token" => $token,
            "ip" => $_SERVER['REMOTE_ADDR'], // Нужно передать IP-адрес пользователя.
                                             // Способ получения IP-адреса пользователя зависит от вашего прокси.
        ]);
        curl_setopt($ch, CURLOPT_URL, "https://captcha-api.yandex.ru/validate?$args");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);

        $server_output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode !== 200) {
            echo "Allow access due to an error: code=$httpcode; message=$server_output\n";
            return true;
        }
        $resp = json_decode($server_output);
        return $resp->status === "ok";
    }



    function yandex_captcha_towp($result) {
    $vid = $_POST['_wpcf7'];

            if (! class_exists('WPCF7_Submission')) {
                return $result;
            }

            $_wpcf7 = ! empty($_POST['_wpcf7']) ? absint($_POST['_wpcf7']) : 0;
            if (empty($_wpcf7)) {
                return $result;
            }

            $submission = WPCF7_Submission::get_instance();
            $data = $submission->get_posted_data();


            if($cf7yac_errormessage = get_option('cf7yac_errormessage')) {} else {
              $cf7yac_errormessage = "captcha error";  
            }

            $token = $data['smart-token'];
            if (check_yandex_captcha($token)) {
                return $result;
            } else {
                $result->invalidate(array('type' => 'cf7-yandex-captcha', 'name' => 'cf7-yandex-captcha'), $cf7yac_errormessage);
                return $result;
            }

    }
    add_filter('wpcf7_validate', 'yandex_captcha_towp', 20, 2);

}


if (is_admin()) {
    function cf7yac_add_action_links($links) {
        array_unshift($links , '<a href="' . admin_url( 'options-general.php?page=cf7yac_options' ) . '">Настройки</a>');
        return $links;
    }
    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'cf7yac_add_action_links', 10, 2 );

    function cf7yac_adminoption() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        if (! class_exists('WPCF7_Submission')) {
            echo '<p>To use <strong>Contact Form 7 Captcha</strong> please update <strong>Contact Form 7</strong> plugin as current version is not supported.</p>';
            return;
        }
        if (
            ! empty ($_POST['update'])
            && ! empty($_POST['cf7yac_nonce'])
            && wp_verify_nonce($_POST['cf7yac_nonce'],'cf7yac_upd_settings' )
        ) {
            $cf7yac_site_key = ! empty ($_POST['cf7yac_site_key']) ? sanitize_text_field($_POST['cf7yac_site_key']) : '';
            update_option('cf7yac_site_key', $cf7yac_site_key);

            $cf7yac_server_key = ! empty ($_POST['cf7yac_server_key']) ? sanitize_text_field($_POST['cf7yac_server_key']) : '';
            update_option('cf7yac_server_key', $cf7yac_server_key);

            $cf7yac_errormessage = ! empty ($_POST['cf7yac_errormessage']) ? sanitize_text_field($_POST['cf7yac_errormessage']) : '';
            update_option('cf7yac_errormessage', $cf7yac_errormessage);

            $updated = 1;
        } else {
            $cf7yac_site_key = get_option('cf7yac_site_key');
            $cf7yac_server_key = get_option('cf7yac_server_key');
            $cf7yac_errormessage = get_option('cf7yac_errormessage');
        }
        ?>
        <div class="cf7sr-wrap" style="font-size: 15px; background: #fff; border: 1px solid #e5e5e5; margin-top: 20px; padding: 20px; margin-right: 20px;">
            <h2>
                Настройки яндекс капчи
            </h2>
            Плагин добавляет яндекс-капчу в формы <b>contact form 7</b><br><br>
            Шорткод <strong>[cf7-yandex-captcha]</strong><br>
            <form action="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>" method="POST">
                <input type="hidden" value="1" name="update">
                <?php wp_nonce_field( 'cf7yac_upd_settings', 'cf7yac_nonce' ); ?>
                <ul>
                    <li><input type="text" style="width: 300px;" value="<?php echo esc_attr($cf7yac_site_key); ?>" name="cf7yac_site_key"> ключ_клиента</li>
                    <li><input type="text" style="width: 300px;" value="<?php echo esc_attr($cf7yac_server_key); ?>" name="cf7yac_server_key"> ключ_сервера</li>
                    <li><input type="text" style="width: 300px;" value="<?php echo esc_attr($cf7yac_errormessage); ?>" name="cf7yac_errormessage"> Сообщение о неправильной капче</li>
                </ul>
                <input type="submit" class="button-primary" value="Сохранить настройки">
            </form><br>
            Получить ключ_клиента и ключ_сервера можно <b><a target="_blank" href="https://console.cloud.yandex.ru/">тут</a></b><br><br>
            <?php if (!empty($updated)): ?>
                <p>Settings were updated successfully!</p>
            <?php endif; ?>
        </div>

        <?php
    }

    function cf7yac_addmenu() {
        add_submenu_page (
            'options-general.php',
            'CF7 Yandex captcha',
            'CF7 Yandex captcha',
            'manage_options',
            'cf7yac_options',
            'cf7yac_adminoption'
        );
    }
    add_action('admin_menu', 'cf7yac_addmenu');
}
