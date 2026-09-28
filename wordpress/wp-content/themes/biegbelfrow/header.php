<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Przejdź do treści</a>
<header class="site-header">
    <div class="container header-inner">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Bieg Belfrów — strona główna"><img src="<?php echo esc_url(bb_image('logo', 'BB7_logo_small.png')); ?>" alt="Bieg Belfrów" width="435" height="320"></a>
        <button class="menu-toggle" aria-controls="site-nav" aria-expanded="false" hidden>Menu <span aria-hidden="true">☰</span></button>
        <nav id="site-nav" aria-label="Menu główne">
            <?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'bb_fallback_menu', 'depth' => 1)); ?>
            <?php if (is_user_logged_in()) : ?>
            <?php
            $account_user = wp_get_current_user();
            $initial_source = trim($account_user->first_name . ' ' . $account_user->last_name) ?: $account_user->display_name;
            $initial_parts = preg_split('/\s+/', trim($initial_source));
            $account_initials = mb_strtoupper(mb_substr($initial_parts[0], 0, 1) . (count($initial_parts) > 1 ? mb_substr(end($initial_parts), 0, 1) : ''));
            ?>
            <details class="account-menu">
                <summary aria-label="Menu konta">
                    <span class="account-initials" aria-hidden="true"><?php echo esc_html($account_initials); ?></span>
                </summary>
                <div class="account-links">
                    <a href="<?php echo esc_url(home_url('/mojbb/')); ?>">Mój BB</a>
                    <a href="<?php echo esc_url(add_query_arg('widok', 'dane', home_url('/mojbb/'))); ?>">Moje dane</a>
                    <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>">Wyloguj się</a>
                </div>
            </details>
            <?php else : ?>
            <a class="account-login" href="<?php echo esc_url(wp_login_url(home_url('/mojbb/'))); ?>">Zaloguj się
                <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><circle cx="12" cy="7" r="4"/><path d="M4 22v-3a8 8 0 0 1 16 0v3Z"/></svg>
            </a>
            <?php endif; ?>
        </nav>
    </div>
</header>
