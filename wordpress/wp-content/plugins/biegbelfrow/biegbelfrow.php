<?php
/**
 * Plugin Name: Bieg Belfrów
 * Description: Panel uczestnika i funkcje Biegu Belfrów.
 * Version: 0.1.0
 */
defined('ABSPATH') || exit;

add_filter('show_admin_bar', function ($show) {
    return current_user_can('edit_posts') ? $show : false;
});

function bb_panel_url($view = '') {
    return $view ? add_query_arg('widok', $view, home_url('/mojbb/')) : home_url('/mojbb/');
}
register_activation_hook(__FILE__, function () {
    if (!get_page_by_path('mojbb', OBJECT, 'page')) {
        wp_insert_post(array('post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Mój BB', 'post_name' => 'mojbb'));
    }
    flush_rewrite_rules();
});
add_action('template_redirect', function () {
    if (!is_page('mojbb')) {
        return;
    }
    if (!defined('DONOTCACHEPAGE')) {
        define('DONOTCACHEPAGE', true);
    }
    nocache_headers();
    if (!is_user_logged_in()) {
        auth_redirect();
        exit;
    }
});
add_filter('wp_robots', function ($robots) {
    if (is_page('mojbb')) {
        $robots['noindex'] = true;
        $robots['nofollow'] = true;
    }
    return $robots;
});
add_filter('login_redirect', function ($redirect, $requested, $user) {
    if ($user instanceof WP_User && !$requested && !user_can($user, 'manage_options')) {
        return bb_panel_url();
    }
    return $redirect;
}, 10, 3);
add_action('admin_post_bb_save_profile', function () {
    if (!is_user_logged_in()) {
        auth_redirect();
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        wp_die('Niedozwolona metoda.', '', array('response' => 405));
    }
    check_admin_referer('bb_save_profile', 'bb_profile_nonce');
    $fields = array();
    foreach (array('first_name', 'last_name', 'display_name') as $key) {
        if (!isset($_POST[$key]) || !is_string($_POST[$key])) {
            wp_safe_redirect(add_query_arg('status', 'invalid', bb_panel_url('dane')));
            exit;
        }
        $fields[$key] = sanitize_text_field(wp_unslash($_POST[$key]));
        if (mb_strlen($fields[$key]) > 100) {
            wp_safe_redirect(add_query_arg('status', 'invalid', bb_panel_url('dane')));
            exit;
        }
    }
    if (!$fields['display_name']) {
        wp_safe_redirect(add_query_arg('status', 'invalid', bb_panel_url('dane')));
        exit;
    }
    // The account is always the authenticated user, never an ID from the form.
    $fields['ID'] = get_current_user_id();
    $result = wp_update_user($fields);
    wp_safe_redirect(add_query_arg('status', is_wp_error($result) ? 'error' : 'saved', bb_panel_url('dane')));
    exit;
});
