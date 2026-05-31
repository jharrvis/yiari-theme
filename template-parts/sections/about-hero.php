<?php
$image = yiari_field('about_hero_image');
$title = yiari_field('about_hero_title', __("Untuk Mereka yang\nTak Bisa Membela Diri", 'yiari'));
$text = yiari_field('about_hero_text', __('YIARI berdiri untuk memberikan perlindungan, rehabilitasi, dan kesempatan kedua bagi satwa liar Indonesia yang terancam. Dari penyelamatan darurat hingga pelepasliaran kembali ke habitat alami.', 'yiari'));
$btn1_text = yiari_field('about_btn1_text', __('Lihat Program Kami', 'yiari'));
$btn1_url = yiari_field('about_btn1_url', yiari_get_program_url());
$btn2_text = yiari_field('about_btn2_text', __('Pelajari Lebih Lanjut', 'yiari'));
$btn2_url = yiari_field('about_btn2_url', '#visi-misi');
get_template_part('template-parts/sections/page-hero', null, [
    'id' => 'about-hero',
    'classes' => 'about-hero-section',
    'image' => !empty($image['url']) ? $image : ['url' => get_template_directory_uri() . '/assets/img/hero-about-us.jpg'],
    'image_class' => 'about-hero-img',
    'image_alt' => !empty($image['alt']) ? $image['alt'] : __('About YIARI', 'yiari'),
    'overlay_class' => 'about-hero-overlay',
    'content_class' => 'about-hero-content',
    'copy_class' => 'about-hero-copy',
    'title_class' => 'about-hero-title',
    'text_class' => 'about-hero-text',
    'actions_class' => 'about-hero-actions',
    'title' => $title,
    'text' => $text,
    'allow_title_breaks' => true,
    'buttons' => [
        ['text' => $btn1_text, 'url' => $btn1_url, 'class' => 'btn-action btn-action-primary'],
        ['text' => $btn2_text, 'url' => $btn2_url, 'class' => 'btn-action btn-action-outline'],
    ],
]);
