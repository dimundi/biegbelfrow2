<?php
defined('ABSPATH') || exit;

// Keep WordPress authentication, password recovery and redirects; style every login screen.
add_action('login_enqueue_scripts', function () {
    wp_enqueue_style('bb-login-fonts', bb_asset('fonts/fonts.css'), array(), filemtime(get_template_directory() . '/assets/fonts/fonts.css'));
    wp_enqueue_style('bb-login', bb_asset('login.css'), array('bb-login-fonts'), filemtime(get_template_directory() . '/assets/login.css'));
    $css = ':root{--bb-primary:' . (sanitize_hex_color(bb_setting('primary')) ?: '#d93400') . ';--bb-accent:' . (sanitize_hex_color(bb_setting('accent')) ?: '#ffcf00') . ';}';
    $css .= 'body.login h1 a{background-image:url(' . wp_json_encode(esc_url_raw(bb_image('logo', 'BB7_logo_small.png')), JSON_HEX_TAG | JSON_HEX_AMP) . ');}';
    wp_add_inline_style('bb-login', $css);
});
add_filter('login_headerurl', function () { return home_url('/'); });
add_filter('login_headertext', function () { return 'Bieg Belfrów — strona główna'; });
add_filter('login_title', function ($title) { return str_replace('WordPress', 'Bieg Belfrów', $title); });
add_filter('login_message', function ($message) {
    return '<div class="bb-login-intro"><p class="bb-login-eyebrow">TWÓJ PANEL UCZESTNIKA</p></div>' . $message;
});

function bb_asset($name) {
    return get_template_directory_uri() . '/assets/' . $name;
}
function bb_setting($key) {
    $defaults = array(
        'edition' => '7. edycja Biegu Belfrów',
        'heading' => '7. edycja zakończona.',
        'announcement' => 'Do zobaczenia wiosną 2027!',
        'intro' => 'Dziękujemy za udział, wspólną aktywność i pomoc potrzebującym dzieciom. To Wy tworzycie Bieg Belfrów!',
        'primary' => '#d93400',
        'accent' => '#ffcf00',
        'background' => '#ffffff',
    );
    return get_theme_mod('bb_' . $key, $defaults[$key] ?? '');
}
function bb_image($key, $fallback) {
    $id = absint(get_theme_mod('bb_' . $key));
    return ($id ? wp_get_attachment_image_url($id, 'large') : false) ?: bb_asset($fallback);
}
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));
    register_nav_menus(array('primary' => 'Menu główne'));
});
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('biegbelfrow-fonts', bb_asset('fonts/fonts.css'), array(), filemtime(get_stylesheet_directory() . '/assets/fonts/fonts.css'));
    wp_enqueue_style('biegbelfrow', get_stylesheet_uri(), array('biegbelfrow-fonts'), filemtime(get_stylesheet_directory() . '/style.css'));
    $css = ':root{';
    foreach (array('primary', 'accent', 'background') as $key) {
        $css .= '--bb-' . $key . ':' . sanitize_hex_color(bb_setting($key)) . ';';
    }
    wp_add_inline_style('biegbelfrow', $css . '}');
    wp_enqueue_script('biegbelfrow', bb_asset('site.js'), array(), filemtime(get_stylesheet_directory() . '/assets/site.js'), true);
});
add_action('customize_register', function ($customizer) {
    $customizer->add_section('bb_home', array('title' => 'Bieg Belfrów — wygląd strony', 'priority' => 30));
    foreach (array('edition' => 'Nazwa edycji', 'heading' => 'Nagłówek banera', 'announcement' => 'Zapowiedź kolejnej edycji', 'intro' => 'Opis banera') as $key => $label) {
        $customizer->add_setting('bb_' . $key, array('default' => bb_setting($key), 'sanitize_callback' => 'sanitize_text_field'));
        $customizer->add_control('bb_' . $key, array('section' => 'bb_home', 'label' => $label, 'type' => $key === 'intro' ? 'textarea' : 'text'));
    }
    foreach (array('primary' => 'Kolor główny', 'accent' => 'Kolor pomocniczy', 'background' => 'Kolor tła') as $key => $label) {
        $customizer->add_setting('bb_' . $key, array('default' => bb_setting($key), 'sanitize_callback' => 'sanitize_hex_color'));
        $customizer->add_control(new WP_Customize_Color_Control($customizer, 'bb_' . $key, array('section' => 'bb_home', 'label' => $label)));
    }
    foreach (array('logo' => 'Logo edycji', 'hero' => 'Grafika banera') as $key => $label) {
        $customizer->add_setting('bb_' . $key, array('sanitize_callback' => 'absint'));
        $customizer->add_control(new WP_Customize_Media_Control($customizer, 'bb_' . $key, array('section' => 'bb_home', 'label' => $label, 'mime_type' => 'image')));
    }
});
function bb_fallback_menu() {
    echo '<ul class="menu">';
    foreach (array('/' => '7 edycja', '/edycje/' => 'Historia', '/onas/' => 'O nas', '/kontakt/' => 'Kontakt', '/sklepik/' => 'Sklepik') as $path => $label) {
        $current = $path === '/' ? is_front_page() : is_page(trim($path, '/'));
        $url = $path === '/sklepik/' && function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url($path);
        echo '<li><a href="' . esc_url($url) . '"' . ($current ? ' aria-current="page"' : '') . '>' . esc_html($label) . '</a></li>';
    }
    echo '</ul>';
}
