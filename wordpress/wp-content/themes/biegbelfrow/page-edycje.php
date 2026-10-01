<?php
get_header();
?>
<main id="main" class="container history-page">
    <h1 class="screen-reader-text"><?php the_title(); ?></h1>
    <div class="history-editions">
        <?php the_content(); ?>
    </div>
</main>
<?php get_footer(); ?>
