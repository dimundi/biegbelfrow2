<?php
// Also protect the template if the plugin is temporarily disabled.
if (!is_user_logged_in()) {
    auth_redirect();
    exit;
}
nocache_headers();
$member = wp_get_current_user();
$views = array('start' => 'Mój BB', 'edycje' => 'Moje edycje', 'dane' => 'Moje dane');
$requested_view = isset($_GET['widok']) && is_string($_GET['widok']) ? sanitize_key($_GET['widok']) : 'start';
$view = array_key_exists($requested_view, $views) ? $requested_view : 'start';
$panel_link = static function ($tab = '') { return $tab ? add_query_arg('widok', $tab, home_url('/mojbb/')) : home_url('/mojbb/'); };
$greeting = $member->first_name ?: $member->display_name;
get_header();
?>
<main id="main" class="container member-panel">
    <aside class="member-sidebar">
        <nav class="member-nav" aria-label="Panel uczestnika">
            <?php foreach ($views as $key => $label) : ?>
            <a href="<?php echo esc_url($panel_link($key)); ?>" <?php if ($view === $key) echo 'aria-current="page"'; ?>><?php echo esc_html($label); ?><span aria-hidden="true">↗</span></a>
            <?php endforeach; ?>
        </nav>
        <div class="member-sidebar-bottom">
            <?php if (current_user_can('manage_options')) : ?><a href="<?php echo esc_url(admin_url()); ?>">Administracja WordPress</a><?php endif; ?>
            <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>">Wyloguj się</a>
        </div>
    </aside>
    <div class="member-content">
        <header class="member-heading"><p class="eyebrow">Panel uczestnika</p><h1><?php echo esc_html($view === 'start' ? 'Cześć, ' . $greeting . '!' : $views[$view]); ?></h1></header>
        <?php if ($view === 'start') : ?>
            <section class="member-welcome">
                <div><span class="member-badge">7. edycja zakończona</span><h2>Małe kroki.<br>Wielka pomoc.</h2><p>Dziękujemy, że jesteś z nami.<br>Do zobaczenia wiosną 2027!</p><a class="text-link" href="<?php echo esc_url(home_url('/#ranking')); ?>">Zobacz wyniki Biegu Belfrów <span aria-hidden="true">→</span></a></div>
                <img src="<?php echo esc_url(bb_image('logo', 'BB7_logo_small.png')); ?>" alt="Bieg Belfrów — 7. edycja" width="435" height="320">
            </section>
        <?php elseif ($view === 'dane') : ?>
            <?php $status = isset($_GET['status']) && is_string($_GET['status']) ? sanitize_key($_GET['status']) : ''; ?>
            <?php if ($status === 'saved') : ?><p class="member-notice" role="status">Zmiany zostały zapisane.</p><?php elseif (in_array($status, array('invalid', 'error'), true)) : ?><p class="member-notice member-error" role="alert">Nie zapisano zmian. Podaj nazwę wyświetlaną i użyj maksymalnie 100 znaków w każdym polu.</p><?php endif; ?>
            <section class="member-form-card">
                <h2>Dane profilu</h2>
                <form class="member-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <input type="hidden" name="action" value="bb_save_profile">
                    <?php wp_nonce_field('bb_save_profile', 'bb_profile_nonce'); ?>
                    <div class="member-form-row">
                        <label>Imię<input name="first_name" autocomplete="given-name" maxlength="100" value="<?php echo esc_attr($member->first_name); ?>"></label>
                        <label>Nazwisko<input name="last_name" autocomplete="family-name" maxlength="100" value="<?php echo esc_attr($member->last_name); ?>"></label>
                    </div>
                    <label>Nazwa wyświetlana<input name="display_name" autocomplete="nickname" required maxlength="100" value="<?php echo esc_attr($member->display_name); ?>"></label>
                    <label>Adres e-mail<input type="email" readonly value="<?php echo esc_attr($member->user_email); ?>" aria-describedby="member-email-help"></label>
                    <p id="member-email-help" class="member-field-help">W sprawie zmiany adresu e-mail <a href="<?php echo esc_url(home_url('/kontakt/')); ?>">skontaktuj się z nami</a>.</p>
                    <button class="button" type="submit">Zapisz zmiany</button>
                </form>
            </section>
            <section class="member-account-strip"><div><h2>Bezpieczeństwo</h2><p>Ustaw nowe hasło przez wiadomość na adres swojego konta.</p></div><a class="text-link" href="<?php echo esc_url(wp_lostpassword_url($panel_link('dane'))); ?>">Zresetuj hasło →</a></section>
        <?php elseif ($view === 'edycje') : ?>
            <?php
            global $wpdb;
            $participants_table = $wpdb->prefix . 'bb_participants';
            $editions_table = $wpdb->prefix . 'bb_editions';
            $activities_table = $wpdb->prefix . 'bb_participant_activities';
            $participations = $wpdb->get_results($wpdb->prepare(
                "SELECT p.bbParticipantId, p.fullName, p.irbNick, p.startNumber, p.irbEnabled, e.bbeditionId, e.editionNumber, e.isCurrent, e.logo,
                    GROUP_CONCAT(DISTINCT a.activityType ORDER BY FIELD(a.activityType, 'walk', 'run', 'bike', 'iron_teacher') SEPARATOR ',') AS activityTypes
                 FROM $participants_table p
                 INNER JOIN $editions_table e ON e.bbeditionId=p.bbeditionId
                 LEFT JOIN $activities_table a ON a.bbParticipantId=p.bbParticipantId
                 WHERE p.active=1 AND (p.email=%s OR p.registeredByUserId=%d OR p.managedByUserId=%d)
                 GROUP BY p.bbParticipantId
                 ORDER BY e.editionNumber DESC, p.bbParticipantId DESC",
                $member->user_email,
                $member->ID,
                $member->ID
            ), ARRAY_A);
            $activity_labels = array('walk' => 'Spacer', 'run' => 'Bieg', 'bike' => 'Rower', 'iron_teacher' => 'Iron Teacher');
            $ranking_types = array('walk' => 'Spacer', 'run' => 'Bieg', 'bike' => 'Rower');
            ?>
            <section class="member-editions">
                <?php if (!$participations) : ?>
                    <div class="member-empty"><h2>Nie znaleźliśmy jeszcze Twoich zapisów.</h2><p>Jeżeli uczestniczyłaś lub uczestniczyłeś w poprzedniej edycji, historia pojawi się po przypisaniu zapisu do konta.</p></div>
                <?php else : ?>
                    <div class="member-edition-list">
                    <?php foreach ($participations as $participation) : ?>
                        <?php
                        $types = array_filter(array_map('trim', explode(',', (string) $participation['activityTypes'])));
                        $labels = array_map(static function ($type) use ($activity_labels) { return $activity_labels[$type] ?? $type; }, $types);
                        $edition_logo = absint($participation['logo']);
                        ?>
                        <article class="member-edition-card">
                            <div><span class="member-edition-logo"><?php if ($edition_logo) { echo wp_get_attachment_image($edition_logo, 'medium', false, array('alt' => 'Logo ' . $participation['editionNumber'] . '. edycji')); } else { ?><span class="member-edition-logo-placeholder" aria-label="Edycja <?php echo esc_attr($participation['editionNumber']); ?>">BB</span><?php } ?></span><p><?php echo esc_html($participation['editionNumber']); ?>. edycja Biegu Belfrów</p></div>
                            <div class="member-edition-details">
                                <?php if ($labels) : ?><span><?php echo esc_html(implode(' · ', $labels)); ?></span><?php endif; ?>
                                <?php if ($participation['startNumber']) : ?><span class="member-start-number"><?php echo esc_html($participation['startNumber']); ?></span><?php endif; ?>
                            </div>
                            <?php if ($participation['irbEnabled']) : ?>
                                <button class="member-edition-results" type="button" data-member-results-open="<?php echo esc_attr($participation['bbParticipantId']); ?>">Moje wyniki</button>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
            <?php foreach ($participations as $participation) : ?>
                <?php if (!$participation['irbEnabled']) continue; ?>
                <?php
                $ranking_data = array();
                $default_ranking_type = 'walk';
                $participant_types = array_filter(array_map('trim', explode(',', (string) $participation['activityTypes'])));
                foreach (array_keys($ranking_types) as $type) {
                    if (in_array($type, $participant_types, true)) {
                        $default_ranking_type = $type;
                        break;
                    }
                }
                if (class_exists('BBW_Rankings')) {
                    $ranking_data = BBW_Rankings::rows_for_page(array(
                        'bbeditionId' => (int) $participation['bbeditionId'],
                        'isCurrent' => (int) $participation['isCurrent'],
                    ));
                }
                $modal_id = 'member-results-' . (int) $participation['bbParticipantId'];
                ?>
                <div id="<?php echo esc_attr($modal_id); ?>" class="member-results-modal" data-member-results-modal="<?php echo esc_attr($participation['bbParticipantId']); ?>" hidden>
                    <div class="member-results-dialog" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr($modal_id); ?>-title" tabindex="-1">
                        <button class="member-results-close" type="button" data-member-results-close aria-label="Zamknij wyniki">×</button>
                        <header class="member-results-heading">
                            <p class="eyebrow">Indywidualny Ranking Belfrów</p>
                            <h2 id="<?php echo esc_attr($modal_id); ?>-title"><?php echo esc_html($participation['editionNumber']); ?>. edycja Biegu Belfrów</h2>
                        </header>
                        <?php if (!$ranking_data) : ?>
                            <p class="member-empty-results">Wyniki tej edycji nie są jeszcze dostępne.</p>
                        <?php else : ?>
                            <div class="member-results-tabs" role="tablist" aria-label="Aktywność">
                                <?php foreach ($ranking_types as $type => $label) : ?>
                                    <button type="button" role="tab" data-member-results-tab="<?php echo esc_attr($type); ?>" aria-selected="<?php echo $type === $default_ranking_type ? 'true' : 'false'; ?>"><?php echo esc_html($label); ?></button>
                                <?php endforeach; ?>
                            </div>
                            <?php foreach ($ranking_types as $type => $label) : ?>
                                <?php $rows = $ranking_data[$type] ?? array(); ?>
                                <div class="member-results-panel" data-member-results-panel="<?php echo esc_attr($type); ?>" <?php echo $type === $default_ranking_type ? '' : 'hidden'; ?> role="tabpanel">
                                    <?php if (!$rows) : ?>
                                        <p class="member-empty-results">Brak wyników dla aktywności: <?php echo esc_html($label); ?>.</p>
                                    <?php else : ?>
                                        <div class="member-results-list" role="list">
                                        <?php foreach ($rows as $row) : ?>
                                            <?php
                                            $same_id = (int) $row['bbParticipantId'] === (int) $participation['bbParticipantId'];
                                            $same_nick = $participation['irbNick'] !== '' && $participation['irbNick'] === ($row['irbNick'] ?? '');
                                            $same_start_number = $participation['startNumber'] !== null && (int) $participation['startNumber'] === (int) ($row['startNumber'] ?? 0);
                                            $is_member_result = $same_id || ($same_nick && $same_start_number);
                                            ?>
                                            <article class="member-ranking-row<?php echo $is_member_result ? ' is-member-result' : ''; ?>"<?php echo $is_member_result ? ' data-member-result-highlight' : ''; ?> role="listitem">
                                                <span class="member-ranking-place"><?php echo esc_html($row['place']); ?></span>
                                                <div><strong><?php echo esc_html(BBW_Rankings::public_name($row)); ?></strong><small><?php if ($row['startNumber']) : ?><span class="member-start-number"><?php echo esc_html($row['startNumber']); ?></span><?php endif; ?><?php echo esc_html($row['irbGroupName'] ?: ''); ?></small></div>
                                                <b><?php echo esc_html(number_format(((int) $row['distanceMeters']) / 1000, 1, ',', '') . ' km'); ?></b>
                                            </article>
                                        <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
