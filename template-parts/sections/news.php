<?php
$news_label = yiari_field('news_label', __('Berita Terkini', 'yiari'));
$news_title = yiari_field('news_title', __('Dari Lapangan', 'yiari'));
$see_all_url = yiari_get_posts_page_url(home_url('/cerita/'));
$query = new WP_Query([
    'post_type' => 'post',
    'posts_per_page' => 3,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC',
    'ignore_sticky_posts' => true,
]);
if (!$query->have_posts()) return;
?>
<section class="section-news" id="news">
  <div class="container">
    <div class="news-header fade-in">
      <div>
        <?php yiari_section_label($news_label); ?>
        <h2 class="section-heading"><?php echo esc_html($news_title); ?></h2>
      </div>
      <a href="<?php echo esc_url($see_all_url); ?>" class="link-more"><?php echo esc_html__('Lihat Semua Cerita', 'yiari'); ?> <i data-lucide="arrow-right" class="icon-sm"></i></a>
    </div>
    <div class="news-grid fade-in">
      <?php $query->the_post(); ?>
      <div class="news-featured">
        <?php if (has_post_thumbnail()): ?><div class="news-featured-img"><?php the_post_thumbnail('section-wide', ['alt' => get_the_title()]); ?></div><?php endif; ?>
        <div class="news-card-body">
          <div class="news-date"><?php echo get_the_date('j F Y'); ?> <span class="news-meta-sep">&bull;</span> <?php echo esc_html__('Oleh', 'yiari'); ?> <?php the_author(); ?></div>
          <h3 class="news-title"><?php the_title(); ?></h3>
          <p class="news-excerpt"><?php the_excerpt(); ?></p>
          <a href="<?php the_permalink(); ?>" class="link-more"><?php echo esc_html__('Baca Selengkapnya', 'yiari'); ?> <i data-lucide="arrow-right" class="icon-sm"></i></a>
        </div>
      </div>
      <?php if ($query->have_posts()): ?>
      <div class="news-stacked">
        <?php while ($query->have_posts()): $query->the_post(); ?>
          <div class="news-card-vertical">
            <?php if (has_post_thumbnail()): ?><div class="news-card-v-img"><?php the_post_thumbnail('card-thumb', ['alt' => get_the_title()]); ?></div><?php endif; ?>
            <div class="news-card-body">
              <div class="news-date"><?php echo get_the_date('j F Y'); ?> <span class="news-meta-sep">&bull;</span> <?php echo esc_html__('Oleh', 'yiari'); ?> <?php the_author(); ?></div>
              <h3 class="news-title"><?php the_title(); ?></h3>
              <a href="<?php the_permalink(); ?>" class="link-more"><?php echo esc_html__('Baca Selengkapnya', 'yiari'); ?> <i data-lucide="arrow-right" class="icon-sm"></i></a>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php wp_reset_postdata(); ?>
