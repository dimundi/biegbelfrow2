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
            <details class="account-menu">
                <summary aria-label="Menu konta">
                    <span><?php echo is_user_logged_in() ? 'Mój BB' : 'Zaloguj się'; ?></span>
                    <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><circle cx="12" cy="7" r="4"/><path d="M4 22v-3a8 8 0 0 1 16 0v3Z"/></svg>
                </summary>
                <div class="account-links">
                <?php if (is_user_logged_in()) : ?>
                    <a href="<?php echo esc_url(home_url('/mojbb/')); ?>">Mój BB</a>
                    <a href="<?php echo esc_url(add_query_arg('widok', 'dane', home_url('/mojbb/'))); ?>">Moje dane</a>
                    <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>">Wyloguj się</a>
                <?php else : ?>
                    <a href="<?php echo esc_url(wp_login_url(home_url('/mojbb/'))); ?>">Zaloguj się</a>
                <?php endif; ?>
                </div>
            </details>
        </nav>
    </div>
</header>
