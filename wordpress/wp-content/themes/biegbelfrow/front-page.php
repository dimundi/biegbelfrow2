<?php get_header(); ?>
<main id="main">
<section class="hero container" aria-labelledby="hero-heading">
    <div class="hero-copy">
        <p class="eyebrow"><span class="edition-dot"></span><?php echo esc_html(bb_setting('edition')); ?></p>
        <h1 id="hero-heading"><?php echo esc_html(bb_setting('heading')); ?></h1>
        <p class="hero-announcement"><?php echo esc_html(bb_setting('announcement')); ?></p>
        <p class="hero-intro"><?php echo esc_html(bb_setting('intro')); ?></p>
        <div class="hero-actions"><a class="button" href="#aktywnosci">Znajdź swoją aktywność <span aria-hidden="true">↗</span></a><a class="text-link" href="#o-biegu">Poznaj Bieg Belfrów <span aria-hidden="true">↓</span></a></div>
        <div class="hero-note"><span class="note-mark" aria-hidden="true">♥</span><span>Dla siebie. Dla innych.<br><strong>W swoim tempie.</strong></span></div>
    </div>
    <?php
    $hero_slides = array();
    foreach (array('bieg' => 'Bieg', 'spacer' => 'Spacer', 'rower' => 'Rower', 'ironteacher' => 'Iron Teacher') as $key => $label) {
        $hero_slides[] = array(
            'src' => $key === 'bieg' ? bb_image('hero', 'BB7_post_bieg.jpg') : bb_asset('BB7_post_' . $key . '.jpg'),
            'alt' => $label . ' — grafika 7. edycji',
        );
    }
    ?>
    <div class="hero-visual" data-slides="<?php echo esc_attr(wp_json_encode($hero_slides)); ?>" tabindex="0" aria-label="Grafiki aktywności. Najedź kursorem lub ustaw fokus, aby zatrzymać przewijanie.">
        <img id="hero-activity-image" class="hero-image" src="<?php echo esc_url(bb_image('hero', 'BB7_post_bieg.jpg')); ?>" alt="Bieg — grafika 7. edycji" width="720" height="540" fetchpriority="high">
        <div class="visual-bottom"><span>Spacer. Bieg. Rower.</span><strong>Twój ruch ma moc.</strong></div>
    </div>
</section>
<div class="discipline-strip"><div class="container"><span>SPACER</span><i aria-hidden="true">✳</i><span>BIEG</span><i aria-hidden="true">✳</i><span>ROWER</span><i aria-hidden="true">✳</i><span>POMAGANIE</span></div></div>
<section class="container about-section section" id="o-biegu">
    <div><p class="eyebrow">Więcej niż wydarzenie sportowe</p><h2>Łączy nas ruch.<br>I chęć pomagania.</h2></div>
    <div class="about-copy"><p>Od 2021 roku Bieg Belfrów łączy pracowników oświaty, którzy przez spacer, bieganie i jazdę na rowerze wspierają potrzebujące dzieci.</p><p>To czas dla siebie i okazja do spotkania z innymi. W pojedynkę, z koleżanką z pracy lub całą szkolną ekipą — każda aktywność ma znaczenie.</p><a class="text-link" href="#jak-to-dziala">Zobacz, na czym to polega <span aria-hidden="true">↗</span></a></div>
</section>
<section class="activities-section section" id="aktywnosci">
<div class="container">
    <div class="section-heading"><div><p class="eyebrow">Wybierz swój sposób</p><h2>Każdy ma swoje tempo.</h2></div><p>Trzy dyscypliny. Jeden wspólny cel.<br>Znajdź miejsce dla siebie.</p></div>
    <p class="activity-archive-note">Opisy, dystanse i pakiety dotyczą zakończonej 7. edycji Biegu Belfrów.</p>
    <div class="activity-grid">
    <?php
    $activities = json_decode(file_get_contents(get_template_directory() . '/data/activities.json'), true);
    foreach ($activities as $index => $activity) : ?>
        <article class="activity-card<?php echo $activity['key'] === 'ironteacher' ? ' activity-card-iron' : ''; ?>" id="aktywnosc-<?php echo esc_attr($activity['key']); ?>">
            <img src="<?php echo esc_url(bb_asset('BB7_post_' . $activity['key'] . '.jpg')); ?>" alt="<?php echo esc_attr($activity['title'] . ' — grafika 7. edycji'); ?>" width="720" height="540" loading="lazy">
            <div class="activity-body">
                <div class="activity-title"><h3><?php echo esc_html($activity['title']); ?></h3><span><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span></div>
                <strong class="activity-distance"><?php echo esc_html($activity['distance']); ?></strong>
                <p class="activity-description"><?php echo esc_html($activity['description']); ?></p>
                <div class="activity-details">
                <?php foreach ($activity['sections'] as $section) : ?>
                    <details>
                        <summary><?php echo esc_html($section['title']); ?></summary>
                        <div class="activity-detail-content"><?php
                            // Empty links in the old page have no destination; retain their text.
                            echo wp_kses_post(str_replace('href=""', '', $section['content']));
                        ?></div>
                    </details>
                <?php endforeach; ?>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
    </div>
</div>
</section>
<section class="container section how-section" id="jak-to-dziala">
    <div><p class="eyebrow">Prosta idea. Dużo dobrego.</p><h2>Twoja aktywność.<br>Nasza wspólna historia.</h2></div>
    <ol class="steps"><li><h3>Wybierz to, co lubisz</h3><p>Spacer, bieg czy rower? Zacznij od aktywności, która sprawia Ci przyjemność.</p></li><li><h3>Zaproś innych</h3><p>Ruszaj samodzielnie albo zbierz ekipę. Razem łatwiej znaleźć motywację.</p></li><li><h3>Połącz ruch z pomaganiem</h3><p>Bądź częścią społeczności Belfrów, która wspiera potrzebujące dzieci.</p></li></ol>
</section>
<?php if (is_page()) : while (have_posts()) : the_post(); if (trim(get_the_content())) : ?>
<section class="container section editor-content"><?php the_content(); ?></section>
<?php endif; endwhile; endif; ?>
</main>
<?php get_footer(); ?>
