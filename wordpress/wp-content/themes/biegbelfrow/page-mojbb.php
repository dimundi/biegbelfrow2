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
                "SELECT p.bbParticipantId, p.fullName, p.startNumber, p.irbEnabled, e.editionNumber, e.startDate, e.endDate,
                    GROUP_CONCAT(DISTINCT a.activityType ORDER BY a.activityType SEPARATOR ',') AS activityTypes
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
            $format_date = static function ($date) { return $date ? wp_date('d.m.Y', strtotime($date)) : ''; };
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
                        $dates = array_filter(array($format_date($participation['startDate']), $format_date($participation['endDate'])));
                        ?>
                        <article class="member-edition-card">
                            <div><span class="member-edition-number"><?php echo esc_html($participation['editionNumber']); ?></span><p><?php echo esc_html($participation['editionNumber']); ?>. edycja Biegu Belfrów</p></div>
                            <div class="member-edition-details">
                                <?php if ($dates) : ?><span><?php echo esc_html(implode(' – ', $dates)); ?></span><?php endif; ?>
                                <?php if ($labels) : ?><span><?php echo esc_html(implode(' · ', $labels)); ?></span><?php endif; ?>
                                <?php if ($participation['startNumber']) : ?><span>Numer startowy <?php echo esc_html($participation['startNumber']); ?></span><?php endif; ?>
                            </div>
                            <span class="member-edition-status"><?php echo $participation['irbEnabled'] ? 'Udział w IRB' : 'Udział w Biegu Belfrów'; ?></span>
                        </article>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
