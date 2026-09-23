<?php
get_header();
$history = json_decode(file_get_contents(get_template_directory() . '/data/history.json'), true);
?>
<main id="main" class="container history-page">
    <h1 class="screen-reader-text"><?php the_title(); ?></h1>
    <div class="history-editions">
    <?php foreach ($history['editions'] as $index => $edition) : ?>
        <article class="history-edition" aria-labelledby="edition-<?php echo esc_attr($index); ?>">
            <div class="history-logo">
                <img src="<?php echo esc_url(bb_asset($edition['localImage'])); ?>" alt="<?php echo esc_attr($edition['title']); ?>" <?php echo $index ? 'loading="lazy"' : 'fetchpriority="high"'; ?>>
            </div>
            <div class="history-copy">
                <h2 id="edition-<?php echo esc_attr($index); ?>"><?php echo esc_html($edition['title']); ?></h2>
                <p class="history-date"><?php echo esc_html($edition['date']); ?></p>
                <div class="history-original"><?php echo wp_kses_post($edition['content']); ?></div>
            </div>
        </article>
    <?php endforeach; ?>
    </div>
    <figure class="history-beneficiaries"><img src="<?php echo esc_url(bb_asset($history['closingLocalImage'])); ?>" alt="Podopieczni Biegu Belfrów" loading="lazy"></figure>
</main>
<?php get_footer(); ?>
