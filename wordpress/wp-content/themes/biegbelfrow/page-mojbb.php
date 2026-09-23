<?php
// Also protect the template if the plugin is temporarily disabled.
if (!is_user_logged_in()) {
    auth_redirect();
    exit;
}
nocache_headers();
$member = wp_get_current_user();
$views = array('start' => 'Mój BB', 'edycje' => 'Moje edycje', 'wyniki' => 'Moje wyniki', 'zakupy' => 'Zakupy i zamówienia', 'dane' => 'Moje dane');
$requested_view = isset($_GET['widok']) && is_string($_GET['widok']) ? sanitize_key($_GET['widok']) : 'start';
$view = array_key_exists($requested_view, $views) ? $requested_view : 'start';
$panel_link = static function ($tab = '') { return $tab ? add_query_arg('widok', $tab, home_url('/mojbb/')) : home_url('/mojbb/'); };
$greeting = $member->first_name ?: $member->display_name;
get_header();
?>
<main id="main" class="container member-panel">
    <aside class="member-sidebar">
        <div class="member-identity">
            <span class="member-avatar" aria-hidden="true"><?php echo esc_html(mb_strtoupper(mb_substr($greeting, 0, 1))); ?></span>
            <div><strong><?php echo esc_html($greeting); ?></strong><span>Twoje konto Biegu Belfrów</span></div>
        </div>
        <nav class="member-nav" aria-label="Panel uczestnika">
            <?php foreach ($views as $key => $label) : ?>
            <a href="<?php echo esc_url($panel_link($key)); ?>" <?php if ($view === $key) echo 'aria-current="page"'; ?>><?php echo esc_html($label); ?><span aria-hidden="true">↗</span></a>
            <?php endforeach; ?>
        </nav>
        <div class="member-sidebar-bottom">
            <a href="<?php echo esc_url(home_url('/kontakt/')); ?>">Potrzebujesz pomocy?</a>
            <?php if (current_user_can('manage_options')) : ?><a href="<?php echo esc_url(admin_url()); ?>">Administracja WordPress</a><?php endif; ?>
            <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>">Wyloguj się</a>
        </div>
    </aside>
    <div class="member-content">
        <header class="member-heading"><p class="eyebrow">Panel uczestnika</p><h1><?php echo esc_html($view === 'start' ? 'Cześć, ' . $greeting . '!' : $views[$view]); ?></h1><p>Twój Bieg Belfrów. Wszystko w jednym miejscu.</p></header>
        <?php if ($view === 'start') : ?>
            <section class="member-welcome">
                <div><span class="member-badge">7. edycja zakończona</span><h2>Małe kroki.<br>Wielka pomoc.</h2><p>Dziękujemy, że jesteś z nami.<br>Do zobaczenia wiosną 2027!</p><a class="text-link" href="<?php echo esc_url(home_url('/#ranking')); ?>">Zobacz wyniki Biegu Belfrów <span aria-hidden="true">→</span></a></div>
                <img src="<?php echo esc_url(bb_image('logo', 'BB7_logo_small.png')); ?>" alt="Bieg Belfrów — 7. edycja" width="435" height="320">
            </section>
            <div class="member-shortcuts">
                <?php foreach (array('edycje' => array('01', 'Moje edycje', 'Twoje zapisy i historia udziału.'), 'wyniki' => array('02', 'Moje wyniki', 'Twoje aktywności i osiągnięcia.'), 'zakupy' => array('03', 'Zakupy', 'Pakiety, dodatki i zamówienia.')) as $key => $card) : ?>
                <a class="member-shortcut" href="<?php echo esc_url($panel_link($key)); ?>"><span><?php echo esc_html($card[0]); ?> <b aria-hidden="true">↗</b></span><h2><?php echo esc_html($card[1]); ?></h2><p><?php echo esc_html($card[2]); ?></p></a>
                <?php endforeach; ?>
            </div>
            <section class="member-account-strip"><div><h2>Twoje dane</h2><p><?php echo esc_html($member->user_email); ?></p></div><a class="button button-outline" href="<?php echo esc_url($panel_link('dane')); ?>">Edytuj profil</a></section>
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
            <section class="member-empty"><span class="member-badge">Historia udziału</span><h2>Twoje edycje w jednym miejscu</h2><p>Historia udziału nie jest jeszcze dostępna w nowym panelu. Twoje wcześniejsze zapisy pojawią się tutaj po udostępnieniu danych.</p><a class="button button-outline" href="<?php echo esc_url(home_url('/edycje/')); ?>">Poznaj poprzednie edycje</a></section>
        <?php elseif ($view === 'wyniki') : ?>
            <section class="member-empty"><span class="member-badge">Aktywności i wyniki</span><h2>Każdy kilometr ma znaczenie</h2><p>Twoje indywidualne wyniki i dodawanie aktywności nie są jeszcze dostępne w nowym panelu. Możesz już sprawdzić publiczne rankingi zakończonych edycji.</p><a class="button" href="<?php echo esc_url(home_url('/#ranking')); ?>">Zobacz rankingi</a></section>
        <?php elseif ($view === 'zakupy') : ?>
            <section class="member-empty"><span class="member-badge">Sklepik Biegu Belfrów</span><h2>Pakiety i dodatki do Twojej aktywności</h2><p>Sklepik oraz historia zamówień będą dostępne wkrótce.</p><a class="button button-outline" href="<?php echo esc_url(home_url('/kontakt/')); ?>">Zapytaj o zamówienie</a></section>
        <?php endif; ?>
    </div>
</main>
<?php get_footer(); ?>
