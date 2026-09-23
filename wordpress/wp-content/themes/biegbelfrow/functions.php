<?php
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('biegbelfrow', get_stylesheet_uri(), array(), '0.1.0');
});

