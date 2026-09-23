<?php
// From deploy/dev: pipe this file to docker compose exec -T wordpress wp eval-file - --allow-root.
if (!defined('WP_CLI') || !WP_CLI) {
    exit;
}
$page = get_page_by_path('edycje', OBJECT, 'page');
if (!$page) {
    $id = wp_insert_post(array(
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_title' => 'Historia',
        'post_name' => 'edycje',
        'post_content' => '',
    ), true);
    if (is_wp_error($id)) {
        WP_CLI::error($id->get_error_message());
    }
    WP_CLI::success('Utworzono stronę Historia.');
} else {
    WP_CLI::success('Strona Historia już istnieje; zachowano jej treść.');
}
if (!get_option('permalink_structure')) {
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%postname%/');
}
flush_rewrite_rules(true);
