<?php
if (!defined('ABSPATH')) exit;

/**
 * =====================================================
 * CONFIG
 * =====================================================
 */
define('DEZON_CLIENT_ID',     '019b4a35-3b37-72b2-86cc-4c5004378107');
define('DEZON_CLIENT_SECRET', '7yzOv5C1aPsPKgVePppJjdeP9dwTV4NDYtRaElVh');
define('DEZON_SSO_URL',       'https://account.dezon.vn');
define('DEZON_REDIRECT_URI',  home_url('/'));

define('DEZON_COOKIE_NAME',   'dezon_sso_token');
define('DEZON_COOKIE_TTL',    3600); // 1 giờ
define('DEZON_CACHE_TTL',     300);  // 5 phút cache user info

/**
 * =====================================================
 * INIT
 * =====================================================
 */
add_action('init', 'dezon_sso_init');

function dezon_sso_init() {

    /** ---------------------------------------------
     * 1. TRIGGER LOGIN
     * --------------------------------------------- */
    if (isset($_GET['trigger_sso'])) {
        if (!dezon_is_logged_in()) {
            wp_redirect(dezon_get_login_url());
        } else {
            wp_redirect(home_url());
        }
        exit;
    }

    /** ---------------------------------------------
     * 2. LOGOUT
     * --------------------------------------------- */
    if (isset($_GET['dezon_logout'])) {

        // clear cookie
        setcookie(
            DEZON_COOKIE_NAME,
            '',
            time() - 3600,
            '/',
            parse_url(home_url(), PHP_URL_HOST),
            true,
            true
        );

        // clear cache
        delete_transient('dezon_user');

        // redirect sang SSO logout
        $return = esc_url_raw(home_url('/'));
        $logout = DEZON_SSO_URL . '/logout?return=' . rawurlencode($return);
        wp_redirect($logout);
        exit;
    }

    /** ---------------------------------------------
     * 3. CALLBACK OAUTH
     * --------------------------------------------- */
    if (
        isset($_GET['code'], $_GET['state']) &&
        wp_verify_nonce($_GET['state'], 'dezon_login_action')
    ) {

        $token = dezon_exchange_code_for_token($_GET['code']);

        if ($token) {
            // lưu token vào cookie (KHÔNG dùng session)
            setcookie(
                DEZON_COOKIE_NAME,
                $token,
                time() + DEZON_COOKIE_TTL,
                '/',
                parse_url(home_url(), PHP_URL_HOST),
                true,
                true
            );
        }

        // clean URL
        wp_redirect(home_url());
        exit;
    }
}

/**
 * =====================================================
 * HELPERS
 * =====================================================
 */

function dezon_get_login_url() {
    $state = wp_create_nonce('dezon_login_action');
    return DEZON_SSO_URL . '/oauth/authorize?' . http_build_query([
        'client_id'     => DEZON_CLIENT_ID,
        'redirect_uri'  => DEZON_REDIRECT_URI,
        'response_type' => 'code',
        'scope'         => '',
        'state'         => $state,
    ]);
}

function dezon_exchange_code_for_token($code) {

    $res = wp_remote_post(DEZON_SSO_URL . '/oauth/token', [
        'timeout' => 10,
        'body' => [
            'grant_type'    => 'authorization_code',
            'client_id'     => DEZON_CLIENT_ID,
            'client_secret' => DEZON_CLIENT_SECRET,
            'redirect_uri'  => DEZON_REDIRECT_URI,
            'code'          => $code,
        ]
    ]);

    if (is_wp_error($res)) {
        error_log('[DEZON SSO] token error: ' . $res->get_error_message());
        return null;
    }

    $data = json_decode(wp_remote_retrieve_body($res), true);
    return $data['access_token'] ?? null;
}

/**
 * =====================================================
 * AUTH CHECK
 * =====================================================
 */

function dezon_is_logged_in() {
    return !empty($_COOKIE[DEZON_COOKIE_NAME]) && dezon_get_user();
}

function dezon_get_user() {

    // cache user info
    $cached = get_transient('dezon_user');
    if ($cached) return $cached;

    if (empty($_COOKIE[DEZON_COOKIE_NAME])) return null;

    $res = wp_remote_get(DEZON_SSO_URL . '/api/user', [
        'timeout' => 10,
        'headers' => [
            'Authorization' => 'Bearer ' . $_COOKIE[DEZON_COOKIE_NAME]
        ]
    ]);

    if (is_wp_error($res) || wp_remote_retrieve_response_code($res) !== 200) {
        return null;
    }

    $user = json_decode(wp_remote_retrieve_body($res), true);
    if (!$user) return null;

    set_transient('dezon_user', $user, DEZON_CACHE_TTL);
    return $user;
}
