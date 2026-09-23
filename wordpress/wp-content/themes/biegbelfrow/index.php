<?php get_header(); ?>
<main id="main" class="container section editor-content">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article><h1><?php if (!is_singular()) : ?><a href="<?php the_permalink(); ?>"><?php endif; ?><?php the_title(); ?><?php if (!is_singular()) : ?></a><?php endif; ?></h1><?php the_content(); ?></article>
<?php endwhile; the_posts_pagination(); else : ?>
<h1>Nie znaleziono treści</h1><a href="<?php echo esc_url(home_url('/')); ?>">Wróć na stronę główną</a>
<?php endif; ?>
</main>
<?php get_footer(); ?>
