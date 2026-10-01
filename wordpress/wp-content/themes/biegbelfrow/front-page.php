<?php get_header(); ?>
<main id="main">
<?php echo do_shortcode('[bb_home_hero]'); ?>
<div class="discipline-strip"><div class="container"><span>SPACER</span><i aria-hidden="true">✳</i><span>BIEG</span><i aria-hidden="true">✳</i><span>ROWER</span><i aria-hidden="true">✳</i><span>POMAGANIE</span></div></div>
<section class="container about-section section" id="o-biegu">
    <div><p class="eyebrow">Więcej niż wydarzenie sportowe</p><h2>Łączy nas ruch.<br>I chęć pomagania.</h2></div>
    <div class="about-copy"><p>Od 2021 roku Bieg Belfrów łączy pracowników oświaty, którzy przez spacer, bieganie i jazdę na rowerze wspierają potrzebujące dzieci.</p><p>To czas dla siebie i okazja do spotkania z innymi. W pojedynkę, z koleżanką z pracy lub całą szkolną ekipą — każda aktywność ma znaczenie.</p><a class="text-link" href="#jak-to-dziala">Zobacz, na czym to polega <span aria-hidden="true">↗</span></a></div>
</section>
<?php get_template_part('template-parts/ranking'); ?>
<section class="container participants-section" aria-labelledby="participants-heading">
    <div class="section-heading">
        <div><p class="eyebrow">Nasza społeczność</p><h2 id="participants-heading">To Wy tworzycie Bieg Belfrów.</h2></div>
        <div class="participants-controls" hidden>
            <button type="button" data-direction="-1" aria-label="Poprzednie zdjęcia" aria-controls="participants-gallery">←</button>
            <button type="button" data-direction="1" aria-label="Następne zdjęcia" aria-controls="participants-gallery">→</button>
        </div>
    </div>
    <div class="participants-gallery" id="participants-gallery" tabindex="0" role="region" aria-label="Zdjęcia uczestników Biegu Belfrów">
        <?php
        $participant_photos = json_decode(file_get_contents(get_template_directory() . '/data/participants.json'), true);
        foreach ($participant_photos as $photo_index => $photo) : ?>
        <a class="participant-photo" href="<?php echo esc_url(bb_asset($photo['local'])); ?>" aria-label="<?php echo esc_attr('Powiększ zdjęcie uczestników ' . ($photo_index + 1)); ?>">
            <img src="<?php echo esc_url(bb_asset($photo['local'])); ?>" alt="<?php echo esc_attr('Uczestnicy Biegu Belfrów — zdjęcie ' . ($photo_index + 1)); ?>" width="<?php echo esc_attr($photo['width']); ?>" height="<?php echo esc_attr($photo['height']); ?>" loading="lazy">
        </a>
        <?php endforeach; ?>
    </div>
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
