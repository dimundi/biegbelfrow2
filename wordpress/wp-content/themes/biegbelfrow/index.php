<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo esc_html(get_bloginfo('name')); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<main>
    <h1>Hello world!</h1>
    <p>Bieg Belfrów — WordPress działa.</p>
    <a href="<?php echo esc_url(admin_url()); ?>">Panel administracyjny</a>
</main>
<?php wp_footer(); ?>
</body>
</html>

