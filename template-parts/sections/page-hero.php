<?php
defined('ABSPATH') || exit;

$hero_id = $args['id'] ?? '';
$hero_classes = trim((string) ($args['classes'] ?? ''));
$image = $args['image'] ?? null;
$image_class = trim((string) ($args['image_class'] ?? ''));
$image_alt = $args['image_alt'] ?? '';
$overlay_class = trim((string) ($args['overlay_class'] ?? ''));
$content_class = trim((string) ($args['content_class'] ?? ''));
$copy_class = trim((string) ($args['copy_class'] ?? ''));
$title_class = trim((string) ($args['title_class'] ?? ''));
$text_class = trim((string) ($args['text_class'] ?? ''));
$actions_class = trim((string) ($args['actions_class'] ?? ''));
$title = (string) ($args['title'] ?? '');
$text = (string) ($args['text'] ?? '');
$allow_title_breaks = !empty($args['allow_title_breaks']);
$buttons = is_array($args['buttons'] ?? null) ? $args['buttons'] : [];

$section_classes = trim('page-hero ' . $hero_classes);
$img_classes = trim('page-hero-img ' . $image_class);
$overlay_classes = trim('page-hero-overlay ' . $overlay_class);
$content_classes = trim('page-hero-content ' . $content_class);
$copy_classes = trim('page-hero-copy ' . $copy_class);
$title_classes = trim('page-hero-title ' . $title_class);
$text_classes = trim('page-hero-text ' . $text_class);
$actions_classes = trim('page-hero-actions ' . $actions_class);
?>
<section class="<?php echo esc_attr($section_classes); ?>"<?php if ($hero_id !== ''): ?> id="<?php echo esc_attr($hero_id); ?>"<?php endif; ?>>
  <?php yiari_img($image, 'full', $img_classes, (string) $image_alt); ?>
  <div class="<?php echo esc_attr($overlay_classes); ?>"></div>
  <div class="<?php echo esc_attr($content_classes); ?>">
    <div class="<?php echo esc_attr($copy_classes); ?>">
      <h1 class="<?php echo esc_attr($title_classes); ?>">
        <?php echo $allow_title_breaks ? wp_kses_post(nl2br(esc_html($title))) : esc_html($title); ?>
      </h1>
      <p class="<?php echo esc_attr($text_classes); ?>"><?php echo esc_html($text); ?></p>
      <?php if (!empty($buttons)): ?>
        <div class="<?php echo esc_attr($actions_classes); ?>">
          <?php foreach ($buttons as $button): ?>
            <?php yiari_btn((string) ($button['text'] ?? ''), (string) ($button['url'] ?? ''), (string) ($button['class'] ?? 'btn-action btn-action-primary')); ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
