<?php
/**
 * index.php – fallback WordPress jika tidak ada template yang cocok.
 * Halaman utama sebaiknya menggunakan template 'Home' (templates/home.php).
 */

get_template_part('template-parts/global/header');
?>

<div class="container" style="padding: 80px 24px; text-align: center;">
  <h1 style="font-family: 'Poppins', sans-serif; font-size: 28px; margin-bottom: 16px;">
    <?php bloginfo('name'); ?>
  </h1>
  <p style="color: #6b7280;">
    Silakan atur halaman utama di <strong>Settings → Reading → Your homepage displays</strong>
    dan pilih <em>A static page</em>, lalu pilih halaman dengan template <em>Home</em>.
  </p>

  <?php if (have_posts()): ?>
    <?php while (have_posts()): the_post(); ?>
      <article style="max-width: 760px; margin: 40px auto; text-align: left;">
        <h2><?php the_title(); ?></h2>
        <?php the_content(); ?>
      </article>
    <?php endwhile; ?>
  <?php endif; ?>
</div>

<?php get_template_part('template-parts/global/footer'); ?>
