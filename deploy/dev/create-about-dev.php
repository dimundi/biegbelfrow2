<?php
// Pipe to: docker compose exec -T wordpress wp eval-file - --allow-root
if (!defined('WP_CLI') || !WP_CLI) {
    exit;
}
if (!get_page_by_path('onas', OBJECT, 'page')) {
    $id = wp_insert_post(array(
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_title' => 'O nas',
        'post_name' => 'onas',
        'post_content' => '',
    ), true);
    if (is_wp_error($id)) {
        WP_CLI::error($id->get_error_message());
    }
    WP_CLI::success('Created O nas.');
} else {
    WP_CLI::success('O nas already exists; content preserved.');
}
flush_rewrite_rules(true);
