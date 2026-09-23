<?php get_header(); ?>
<main id="main" class="container contact-page">
    <h1 class="screen-reader-text"><?php the_title(); ?></h1>
    <section class="contact-form-area" aria-labelledby="contact-form-heading">
        <h2 id="contact-form-heading">Formularz kontaktowy:</h2>
        <div class="contact-placeholder">[tu będzie wtyczka Contact From 7]</div>
    </section>
    <section class="contact-office" aria-labelledby="contact-office-heading">
        <h2 id="contact-office-heading">Biuro Obsługi Klienta</h2>
        <address>
            <p><strong>ODN Rewers</strong><br>ul. Glazurowa 21/4<br>80-180 Gdańsk</p>
            <p><a href="mailto:biuro@odnrewers.pl">biuro@odnrewers.pl</a><br><a href="tel:+48575321222">575 321 222</a></p>
        </address>
        <p class="contact-bank">nestbank:<br><span>98 1870 1045 2078 1081 7959 0001</span></p>
        <p class="contact-hours">Biuro pracuje od poniedziałku do piątku w godzinach 7.00-15.00</p>
    </section>
</main>
<?php get_footer(); ?>
