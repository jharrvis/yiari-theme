<?php
get_template_part('template-parts/global/header');
?>

<?php if (have_posts()): ?>
  <?php while (have_posts()): the_post(); ?>
    <?php
    $post_id = get_the_ID();
    $categories = get_the_category($post_id);
    $primary_category = $categories[0] ?? null;
    $breadcrumb_items = [];

    if ($primary_category instanceof WP_Term) {
        if ($primary_category->parent) {
            $parent_term = get_term((int) $primary_category->parent, 'category');
            if ($parent_term instanceof WP_Term && !is_wp_error($parent_term)) {
                $breadcrumb_items[] = $parent_term->name;
            }
        }
        $breadcrumb_items[] = $primary_category->name;
    }

    $summary = has_excerpt()
        ? get_the_excerpt()
        : wp_trim_words(wp_strip_all_tags(get_the_content('')), 24, '…');

    $hero_image = get_the_post_thumbnail_url($post_id, 'section-wide') ?: (get_template_directory_uri() . '/assets/img/hero-section.jpg');
    $hero_image_full = get_the_post_thumbnail_url($post_id, 'full') ?: $hero_image;
    $hero_alt = get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true) ?: get_the_title();
    $hero_caption = wp_get_attachment_caption(get_post_thumbnail_id($post_id));
    $author_name = get_the_author();
    $tag_terms = get_the_tags($post_id) ?: [];
    $display_tags = array_slice($tag_terms, 0, 3);

    $related_args = [
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 2,
        'post__not_in' => [$post_id],
        'ignore_sticky_posts' => true,
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    if ($primary_category instanceof WP_Term) {
        $related_args['category__in'] = [$primary_category->term_id];
    }

    $related_query = new WP_Query($related_args);

    if (!$related_query->have_posts()) {
        unset($related_args['category__in']);
        $related_query = new WP_Query($related_args);
    }
    ?>

    <article class="single-post-page">
      <section class="single-post-hero">
        <div class="container single-post-hero-container">
          <?php if ($breadcrumb_items): ?>
            <div class="single-post-breadcrumbs">
              <?php foreach ($breadcrumb_items as $index => $crumb): ?>
                <?php if ($index > 0): ?><span class="single-post-breadcrumb-sep">/</span><?php endif; ?>
                <span><?php echo esc_html($crumb); ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <div class="single-post-meta-row">
            <span class="single-post-meta-item">
              <i data-lucide="calendar-days" class="icon-sm"></i>
              <span><?php echo esc_html(get_the_date('j F Y')); ?></span>
            </span>
            <span class="single-post-meta-dot">&bull;</span>
            <span class="single-post-meta-item">
              <i data-lucide="pen-line" class="icon-sm"></i>
              <span><?php echo esc_html($author_name); ?></span>
            </span>
          </div>

          <h1 class="single-post-title"><?php the_title(); ?></h1>

          <?php if ($summary): ?>
            <p class="single-post-summary"><?php echo esc_html($summary); ?></p>
          <?php endif; ?>

          <figure class="single-post-hero-figure">
            <img src="<?php echo esc_url($hero_image_full); ?>" alt="<?php echo esc_attr($hero_alt); ?>" class="single-post-hero-image" />
            <?php if ($hero_caption): ?>
              <figcaption class="single-post-caption"><?php echo esc_html($hero_caption); ?></figcaption>
            <?php endif; ?>
          </figure>
        </div>
      </section>

      <section class="single-post-content-section">
        <div class="container single-post-content-container">
          <div class="single-post-content entry-content">
            <?php the_content(); ?>
          </div>

          <?php if ($display_tags): ?>
            <div class="single-post-tags">
              <?php foreach ($display_tags as $tag): ?>
                <span class="single-post-tag"><?php echo esc_html($tag->name); ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <?php if ($related_query->have_posts()): ?>
        <section class="single-post-related">
          <div class="container single-post-related-container">
            <h2 class="single-post-related-heading"><?php echo esc_html__('Lanjut Baca', 'yiari'); ?></h2>
            <div class="single-post-related-grid">
              <?php while ($related_query->have_posts()): $related_query->the_post(); ?>
                <?php
                $related_id = get_the_ID();
                $related_image = get_the_post_thumbnail_url($related_id, 'card-thumb') ?: (get_template_directory_uri() . '/assets/img/hero-section.jpg');
                ?>
                <article class="single-post-related-card">
                  <div class="single-post-related-copy">
                    <h3 class="single-post-related-title">
                      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <div class="single-post-related-meta">
                      <span><?php echo esc_html(get_the_date('j F Y')); ?></span>
                      <span class="single-post-related-meta-dot">&bull;</span>
                      <span><?php echo esc_html(get_the_author()); ?></span>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="single-post-related-link">
                      <span><?php echo esc_html__('Baca Selengkapnya', 'yiari'); ?></span>
                      <i data-lucide="arrow-right" class="icon-sm"></i>
                    </a>
                  </div>
                  <a href="<?php the_permalink(); ?>" class="single-post-related-image-wrap" aria-label="<?php the_title_attribute(); ?>">
                    <img src="<?php echo esc_url($related_image); ?>" alt="<?php the_title_attribute(); ?>" class="single-post-related-image" />
                  </a>
                </article>
              <?php endwhile; ?>
            </div>
          </div>
        </section>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </article>
  <?php endwhile; ?>
<?php endif; ?>

<?php get_template_part('template-parts/global/footer'); ?>
