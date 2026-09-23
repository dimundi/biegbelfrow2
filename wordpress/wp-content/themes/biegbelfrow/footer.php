<footer class="site-footer" id="kontakt">
    <div class="container footer-panel">
    <div class="footer-grid">
        <div class="footer-contact">
            <h2>Biuro obsługi i kontaktu<br>z klientami:</h2>
            <address>
                <span class="footer-label">Adres:</span><p>ODN Rewers<br>ul. Glazurowa 21/4<br>80-180 Gdańsk</p>
                <span class="footer-label">Tel.:</span><a href="tel:+48575321222">575 321 222</a>
                <span class="footer-label">Email:</span><a href="mailto:biuro@odnrewers.pl">biuro@odnrewers.pl</a>
            </address>
        </div>
        <nav class="footer-navigation" aria-label="Menu w stopce">
            <?php foreach (array('/' => '7 edycja Biegu Belfrów', '/edycje/' => 'Historia', '/onas/' => 'O nas', '/kontakt/' => 'Kontakt', '/sklepik/' => 'Sklepik') as $path => $label) : ?>
                <a href="<?php echo esc_url($path === '/sklepik/' && function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url($path)); ?>"><span aria-hidden="true">→</span><?php echo esc_html($label); ?></a>
            <?php endforeach; ?>
            <a href="https://odnrewers.pl"><span aria-hidden="true">→</span>ODN Rewers — organizator</a>
        </nav>
        <div class="footer-about"><p class="footer-title">Małe kroki.<br>Wielka pomoc.</p><p>Bieg Belfrów łączy aktywność, ludzi i pomaganie.<br>Do zobaczenia w ruchu!</p><a class="button" href="<?php echo esc_url(home_url('/kontakt/')); ?>">Skontaktuj się z nami <span aria-hidden="true">→</span></a></div>
    </div>
    <div class="footer-bottom">
        <span>© <?php echo esc_html(wp_date('Y')); ?> Bieg Belfrów · ODN Rewers</span>
        <div class="footer-social"><a href="https://www.facebook.com/people/Bieg-Belfr%C3%B3w/61574945740117/">Facebook ↗</a><a href="https://www.strava.com/clubs/1040645/">Strava ↗</a></div>
        <span>Każda aktywność ma znaczenie.</span>
    </div>
    <div class="footer-wordmark" aria-hidden="true">BIEG BELFRÓW</div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
