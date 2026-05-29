<?php
$label = yiari_field('story_label', __('DARI LAPANGAN', 'yiari'));
$title = yiari_field('story_title', __('Barda: Dari Nyaris Mati ke Duta Konservasi', 'yiari'));
$para1 = yiari_field('story_para1', __('Ditemukan dengan luka tembak di kepala, kehilangan penglihatan, dan trauma mental mendalam. Tim YIARI memberikan perawatan medis intensif dan rehabilitasi jangka panjang.', 'yiari'));
$para2 = yiari_field('story_para2', __('Setelah berbulan-bulan perawatan, Barda kini hidup aman di pusat rehabilitasi kami. Ia menjadi simbol harapan yang menginspirasi ribuan orang untuk melindungi satwa Indonesia.', 'yiari'));
$btn1_text = yiari_field('story_btn1_text', __('Baca Selengkapnya', 'yiari'));
$btn1_url = yiari_field('story_btn1_url', yiari_get_posts_page_url(home_url('/cerita/')));
$btn2_text = yiari_field('story_btn2_text', __('Donasi Sekarang', 'yiari'));
$btn2_url = yiari_field('story_btn2_url', yiari_fragment_url('donate'));
$image = yiari_field('story_image');
$gallery = yiari_field('story_gallery', []);

$story_summary = $para1 ?: $para2;
$story_slides = [];

if (is_array($gallery) && $gallery) {
    foreach ($gallery as $gallery_image) {
        if (!empty($gallery_image['url'])) {
            $story_slides[] = $gallery_image;
        }
    }
}

if (!empty($image['url'])) {
    array_unshift($story_slides, $image);
}

if (empty($story_slides)) {
    $story_slides = [
        ['url' => get_template_directory_uri() . '/assets/img/gambar4.jpg', 'alt' => $title],
        ['url' => get_template_directory_uri() . '/assets/img/gambar5.jpg', 'alt' => $title],
        ['url' => get_template_directory_uri() . '/assets/img/gambar6.jpg', 'alt' => $title],
        ['url' => get_template_directory_uri() . '/assets/img/barda.png', 'alt' => $title],
        ['url' => get_template_directory_uri() . '/assets/img/hero-section.jpg', 'alt' => $title],
    ];
}

$story_slides = array_values(array_filter($story_slides, static fn($slide) => !empty($slide['url'])));
$story_slide_count = (int) count($story_slides);
?>
<section
  class="section-story"
  id="story"
  x-data="{
    activeStorySlide: 0,
    storySlideCount: <?php echo $story_slide_count; ?>,
    storyAutoSlide: null,
    nextStorySlide() {
      if (this.storySlideCount <= 1) return;
      this.activeStorySlide = (this.activeStorySlide + 1) % this.storySlideCount;
    },
    startStoryAutoSlide() {
      if (this.storySlideCount <= 1) return;
      this.stopStoryAutoSlide();
      this.storyAutoSlide = setInterval(() => this.nextStorySlide(), 4200);
    },
    stopStoryAutoSlide() {
      if (this.storyAutoSlide) {
        clearInterval(this.storyAutoSlide);
        this.storyAutoSlide = null;
      }
    }
  }"
  x-init="startStoryAutoSlide()"
  @mouseenter="stopStoryAutoSlide()"
  @mouseleave="startStoryAutoSlide()"
>
  <div class="container">
    <div class="story-card fade-in">
      <div class="story-card-head">
        <div class="story-card-title-block">
          <?php yiari_section_label($label, true); ?>
          <h2 class="section-heading story-heading"><?php echo wp_kses_post(nl2br(esc_html($title))); ?></h2>
        </div>
        <div class="story-card-copy">
          <?php if ($story_summary): ?><p class="section-description story-summary"><?php echo esc_html($story_summary); ?></p><?php endif; ?>
          <div class="story-actions">
            <?php yiari_btn($btn1_text, $btn1_url, 'btn-primary'); ?>
            <?php yiari_btn($btn2_text, $btn2_url, 'btn-outline-warning'); ?>
          </div>
        </div>
      </div>

      <div class="story-carousel">
        <div class="story-carousel-frame">
          <?php foreach ($story_slides as $index => $slide): ?>
            <?php
            $slide_alt = $slide['alt'] ?? $title;
            $slide_url = $slide['url'] ?? '';
            ?>
            <div
              class="story-slide"
              x-show="activeStorySlide === <?php echo (int) $index; ?>"
              x-transition:enter="story-fade-enter"
              x-transition:enter-start="story-fade-enter-start"
              x-transition:enter-end="story-fade-enter-end"
              x-transition:leave="story-fade-leave"
              x-transition:leave-start="story-fade-leave-start"
              x-transition:leave-end="story-fade-leave-end"
              x-cloak
            >
              <img src="<?php echo esc_url($slide_url); ?>" alt="<?php echo esc_attr($slide_alt); ?>" class="story-slide-img" loading="lazy" />
            </div>
          <?php endforeach; ?>
        </div>

        <div class="story-indicators" x-show="storySlideCount > 1" x-cloak>
          <template x-for="index in storySlideCount" :key="index">
            <button
              class="story-indicator-btn"
              type="button"
              :aria-label="`<?php echo esc_js(__('Go to story slide', 'yiari')); ?> ${index}`"
              @click="activeStorySlide = index - 1; startStoryAutoSlide()"
            >
              <span class="story-indicator" :class="{ 'is-active': activeStorySlide === (index - 1) }"></span>
            </button>
          </template>
        </div>
      </div>
    </div>
  </div>
</section>
