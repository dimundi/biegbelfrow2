<?php
get_header();
$about = json_decode(file_get_contents(get_template_directory() . '/data/about.json'), true);
?>
<main id="main" class="container about-page">
    <h1 class="screen-reader-text"><?php the_title(); ?></h1>
    <?php foreach ($about['sections'] as $index => $section) : ?>
    <section class="about-topic" aria-labelledby="about-topic-<?php echo esc_attr($index); ?>">
        <div class="about-topic-heading">
            <span class="about-topic-number" aria-hidden="true"><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span>
            <h2 id="about-topic-<?php echo esc_attr($index); ?>"><?php echo esc_html($section['title']); ?></h2>
        </div>
        <div class="about-topic-copy">
            <?php if ($section['subtitle']) : ?><p class="about-topic-subtitle"><?php echo esc_html($section['subtitle']); ?></p><?php endif; ?>
            <div class="about-original"><?php echo wp_kses_post($section['content']); ?></div>
        </div>
        <?php if ($section['images']) : ?>
        <div class="about-gallery<?php echo count($section['images']) === 1 ? ' about-gallery-single' : ''; ?>">
            <?php foreach ($section['images'] as $image_index => $image) : ?>
            <a href="<?php echo esc_url(bb_asset($image['local'])); ?>" aria-label="<?php echo esc_attr('Powiększ zdjęcie: ' . $section['title'] . ' — ' . ($image_index + 1)); ?>">
                <img src="<?php echo esc_url(bb_asset($image['local'])); ?>" alt="<?php echo esc_attr($section['title'] . ' — zdjęcie ' . ($image_index + 1)); ?>" width="<?php echo esc_attr($image['width']); ?>" height="<?php echo esc_attr($image['height']); ?>" loading="lazy">
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>
    <?php endforeach; ?>
</main>
<?php get_footer(); ?>
