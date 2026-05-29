<?php
/**
 * YIARI Theme – ACF Field Groups
 * Semua field group didaftarkan via PHP agar portable (tidak bergantung JSON/DB).
 * Field group aktif berdasarkan Page Template yang dipilih.
 */

defined('ABSPATH') || exit;

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group([
        'key' => 'group_home',
        'title' => __('Home Page - Sections', 'yiari'),
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'templates/home.php']]],
        'menu_order' => 10,
        'fields' => [
            ['key' => 'field_home_hero_tab', 'label' => __('Hero Section', 'yiari'), 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_home_hero_img', 'label' => __('Gambar Hero', 'yiari'), 'name' => 'hero_image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'],
            ['key' => 'field_home_hero_title', 'label' => __('Judul Hero', 'yiari'), 'name' => 'hero_title', 'type' => 'text', 'default_value' => __('Bersama Selamatkan Alam', 'yiari')],
            ['key' => 'field_home_hero_subtitle', 'label' => __('Subtitle Hero', 'yiari'), 'name' => 'hero_subtitle', 'type' => 'textarea', 'rows' => 2, 'default_value' => __('Bersama kita bisa menjaga satwa liar tetap hidup di alamnya', 'yiari')],
            ['key' => 'field_home_hero_cta_text', 'label' => __('Teks Tombol CTA', 'yiari'), 'name' => 'hero_cta_text', 'type' => 'text', 'default_value' => __('Donasi Sekarang', 'yiari')],
            ['key' => 'field_home_hero_cta_url', 'label' => __('URL Tombol CTA', 'yiari'), 'name' => 'hero_cta_url', 'type' => 'url'],

            ['key' => 'field_home_stats_tab', 'label' => __('Stats Section', 'yiari'), 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_home_stats_label', 'label' => __('Label Atas', 'yiari'), 'name' => 'stats_label', 'type' => 'text', 'default_value' => __('DAMPAK TERUKUR', 'yiari')],
            ['key' => 'field_home_stats_title', 'label' => __('Judul', 'yiari'), 'name' => 'stats_title', 'type' => 'text', 'default_value' => __('Bukti Nyata Konservasi Kami', 'yiari')],
            ['key' => 'field_home_stats_desc', 'label' => __('Deskripsi', 'yiari'), 'name' => 'stats_desc', 'type' => 'textarea', 'rows' => 3],
            [
                'key' => 'field_home_stats_items', 'label' => __('Item Statistik', 'yiari'), 'name' => 'stats_items',
                'type' => 'repeater', 'min' => 1, 'max' => 8, 'layout' => 'table',
                'button_label' => __('Tambah Statistik', 'yiari'),
                'sub_fields' => [
                    ['key' => 'field_stat_number', 'label' => __('Angka', 'yiari'), 'name' => 'number', 'type' => 'number', 'column_width' => '20'],
                    ['key' => 'field_stat_suffix', 'label' => __('Suffix', 'yiari'), 'name' => 'suffix', 'type' => 'text', 'column_width' => '10', 'placeholder' => '+'],
                    ['key' => 'field_stat_label', 'label' => __('Label', 'yiari'), 'name' => 'label', 'type' => 'text', 'column_width' => '70'],
                ],
            ],

            ['key' => 'field_home_crisis_tab', 'label' => __('Krisis Section', 'yiari'), 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_home_crisis_label', 'label' => __('Label Atas', 'yiari'), 'name' => 'crisis_label', 'type' => 'text', 'default_value' => __('ANCAMAN MENDESAK', 'yiari')],
            ['key' => 'field_home_crisis_title', 'label' => __('Judul', 'yiari'), 'name' => 'crisis_title', 'type' => 'text', 'default_value' => __('3 Krisis yang Kita Hadapi Bersama', 'yiari')],
            ['key' => 'field_home_crisis_desc', 'label' => __('Deskripsi', 'yiari'), 'name' => 'crisis_desc', 'type' => 'textarea', 'rows' => 3],
            [
                'key' => 'field_home_crisis_cards', 'label' => __('Kartu Krisis', 'yiari'), 'name' => 'crisis_cards',
                'type' => 'repeater', 'min' => 1, 'max' => 4, 'layout' => 'row',
                'button_label' => __('Tambah Kartu Krisis', 'yiari'),
                'sub_fields' => [
                    ['key' => 'field_crisis_img', 'label' => __('Gambar', 'yiari'), 'name' => 'image', 'type' => 'image', 'return_format' => 'array'],
                    ['key' => 'field_crisis_title', 'label' => __('Judul', 'yiari'), 'name' => 'title', 'type' => 'text'],
                    ['key' => 'field_crisis_text', 'label' => __('Deskripsi', 'yiari'), 'name' => 'text', 'type' => 'textarea', 'rows' => 3],
                ],
            ],

            ['key' => 'field_home_story_tab', 'label' => __('Story Section', 'yiari'), 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_home_story_label', 'label' => __('Label Atas', 'yiari'), 'name' => 'story_label', 'type' => 'text', 'default_value' => __('DARI LAPANGAN', 'yiari')],
            ['key' => 'field_home_story_title', 'label' => __('Judul', 'yiari'), 'name' => 'story_title', 'type' => 'text'],
            ['key' => 'field_home_story_para1', 'label' => __('Paragraf 1', 'yiari'), 'name' => 'story_para1', 'type' => 'textarea', 'rows' => 4],
            ['key' => 'field_home_story_para2', 'label' => __('Paragraf 2', 'yiari'), 'name' => 'story_para2', 'type' => 'textarea', 'rows' => 4],
            ['key' => 'field_home_story_btn1_text', 'label' => __('Tombol 1 - Teks', 'yiari'), 'name' => 'story_btn1_text', 'type' => 'text', 'default_value' => __('Baca Selengkapnya', 'yiari')],
            ['key' => 'field_home_story_btn1_url', 'label' => __('Tombol 1 - URL', 'yiari'), 'name' => 'story_btn1_url', 'type' => 'url'],
            ['key' => 'field_home_story_btn2_text', 'label' => __('Tombol 2 - Teks', 'yiari'), 'name' => 'story_btn2_text', 'type' => 'text', 'default_value' => __('Donasi Sekarang', 'yiari')],
            ['key' => 'field_home_story_btn2_url', 'label' => __('Tombol 2 - URL', 'yiari'), 'name' => 'story_btn2_url', 'type' => 'url'],
            ['key' => 'field_home_story_image', 'label' => __('Gambar Utama / Slide Pertama', 'yiari'), 'name' => 'story_image', 'type' => 'image', 'return_format' => 'array'],
            [
                'key' => 'field_home_story_gallery',
                'label' => __('Gallery Carousel', 'yiari'),
                'name' => 'story_gallery',
                'type' => 'gallery',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'insert' => 'append',
                'library' => 'all',
                'instructions' => __('Tambahkan beberapa gambar untuk carousel. Gambar utama di atas akan dipakai sebagai slide pertama jika diisi.', 'yiari'),
            ],

            ['key' => 'field_home_approach_tab', 'label' => __('Approach Section', 'yiari'), 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_home_approach_label', 'label' => __('Label Atas', 'yiari'), 'name' => 'approach_label', 'type' => 'text', 'default_value' => __('Program', 'yiari')],
            ['key' => 'field_home_approach_title', 'label' => __('Judul', 'yiari'), 'name' => 'approach_title', 'type' => 'text'],
            ['key' => 'field_home_approach_desc', 'label' => __('Deskripsi', 'yiari'), 'name' => 'approach_desc', 'type' => 'textarea', 'rows' => 3],
            [
                'key' => 'field_home_approach_items', 'label' => __('Item Program', 'yiari'), 'name' => 'approach_items',
                'type' => 'repeater', 'min' => 1, 'max' => 8, 'layout' => 'table',
                'button_label' => __('Tambah Program', 'yiari'),
                'sub_fields' => [
                    ['key' => 'field_approach_title', 'label' => __('Judul', 'yiari'), 'name' => 'title', 'type' => 'text'],
                    ['key' => 'field_approach_subtitle', 'label' => __('Subtitle', 'yiari'), 'name' => 'subtitle', 'type' => 'text'],
                    ['key' => 'field_approach_image', 'label' => __('Gambar', 'yiari'), 'name' => 'image', 'type' => 'image', 'return_format' => 'array'],
                ],
            ],

            ['key' => 'field_home_donate_tab', 'label' => __('Donate CTA Section', 'yiari'), 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_home_donate_title', 'label' => __('Judul', 'yiari'), 'name' => 'donate_title', 'type' => 'text', 'default_value' => __('Setiap Donasi Menyelamatkan Nyawa', 'yiari')],
            ['key' => 'field_home_donate_text', 'label' => __('Teks', 'yiari'), 'name' => 'donate_text', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_home_donate_btn1_text', 'label' => __('Tombol 1 - Teks', 'yiari'), 'name' => 'donate_btn1_text', 'type' => 'text', 'default_value' => __('Donasi Sekarang', 'yiari')],
            ['key' => 'field_home_donate_btn1_url', 'label' => __('Tombol 1 - URL', 'yiari'), 'name' => 'donate_btn1_url', 'type' => 'url'],
            ['key' => 'field_home_donate_btn2_text', 'label' => __('Tombol 2 - Teks', 'yiari'), 'name' => 'donate_btn2_text', 'type' => 'text', 'default_value' => __('Jadi Relawan', 'yiari')],
            ['key' => 'field_home_donate_btn2_url', 'label' => __('Tombol 2 - URL', 'yiari'), 'name' => 'donate_btn2_url', 'type' => 'url'],
            ['key' => 'field_home_donate_image', 'label' => __('Gambar CTA', 'yiari'), 'name' => 'donate_image', 'type' => 'image', 'return_format' => 'array'],
        ],
    ]);

    acf_add_local_field_group([
        'key' => 'group_about',
        'title' => __('About Us - Sections', 'yiari'),
        'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'templates/about.php']]],
        'menu_order' => 10,
        'fields' => [
            ['key' => 'field_about_hero_tab', 'label' => __('Hero', 'yiari'), 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_about_hero_img', 'label' => __('Gambar Hero', 'yiari'), 'name' => 'about_hero_image', 'type' => 'image', 'return_format' => 'array'],
            ['key' => 'field_about_hero_title', 'label' => __('Judul', 'yiari'), 'name' => 'about_hero_title', 'type' => 'text', 'default_value' => __("Untuk Mereka yang\nTak Bisa Membela Diri", 'yiari')],
            ['key' => 'field_about_hero_text', 'label' => __('Teks', 'yiari'), 'name' => 'about_hero_text', 'type' => 'textarea', 'rows' => 4],
            ['key' => 'field_about_hero_btn1_text', 'label' => __('Tombol 1 Teks', 'yiari'), 'name' => 'about_btn1_text', 'type' => 'text', 'default_value' => __('Lihat Program Kami', 'yiari')],
            ['key' => 'field_about_hero_btn1_url', 'label' => __('Tombol 1 URL', 'yiari'), 'name' => 'about_btn1_url', 'type' => 'url'],
            ['key' => 'field_about_hero_btn2_text', 'label' => __('Tombol 2 Teks', 'yiari'), 'name' => 'about_btn2_text', 'type' => 'text', 'default_value' => __('Pelajari Lebih Lanjut', 'yiari')],
            ['key' => 'field_about_hero_btn2_url', 'label' => __('Tombol 2 URL', 'yiari'), 'name' => 'about_btn2_url', 'type' => 'url'],

            ['key' => 'field_about_vm_tab', 'label' => __('Visi & Misi', 'yiari'), 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_about_vm_label', 'label' => __('Label Atas', 'yiari'), 'name' => 'vm_label', 'type' => 'text', 'default_value' => __('Arah Kami Bergerak', 'yiari')],
            ['key' => 'field_about_vm_section_title', 'label' => __('Judul Section', 'yiari'), 'name' => 'vm_section_title', 'type' => 'text', 'default_value' => __('Visi & Misi YIARI', 'yiari')],
            ['key' => 'field_about_vm_image', 'label' => __('Gambar', 'yiari'), 'name' => 'vm_image', 'type' => 'image', 'return_format' => 'array'],
            ['key' => 'field_about_vm_visi_title', 'label' => __('Label Visi', 'yiari'), 'name' => 'visi_label', 'type' => 'text', 'default_value' => __('Visi', 'yiari')],
            ['key' => 'field_about_vm_visi_text', 'label' => __('Teks Visi', 'yiari'), 'name' => 'visi_text', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_about_vm_misi_title', 'label' => __('Label Misi', 'yiari'), 'name' => 'misi_label', 'type' => 'text', 'default_value' => __('Misi', 'yiari')],
            ['key' => 'field_about_vm_misi_text', 'label' => __('Teks Misi', 'yiari'), 'name' => 'misi_text', 'type' => 'textarea', 'rows' => 3],

            ['key' => 'field_about_timeline_tab', 'label' => __('Timeline', 'yiari'), 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_about_timeline_label', 'label' => __('Label Atas', 'yiari'), 'name' => 'timeline_label', 'type' => 'text', 'default_value' => __('Dampak Nyata', 'yiari')],
            ['key' => 'field_about_timeline_title', 'label' => __('Judul', 'yiari'), 'name' => 'timeline_title', 'type' => 'text', 'default_value' => __('Jejak Dampak YIARI', 'yiari')],
            ['key' => 'field_about_timeline_desc', 'label' => __('Deskripsi', 'yiari'), 'name' => 'timeline_desc', 'type' => 'textarea', 'rows' => 3],
            [
                'key' => 'field_about_timeline_items', 'label' => __('Item Timeline', 'yiari'), 'name' => 'timeline_items',
                'type' => 'repeater', 'min' => 1, 'max' => 12, 'layout' => 'row',
                'button_label' => __('Tambah Milestone', 'yiari'),
                'sub_fields' => [
                    ['key' => 'field_timeline_year', 'label' => __('Tahun', 'yiari'), 'name' => 'year', 'type' => 'text'],
                    ['key' => 'field_timeline_title', 'label' => __('Judul', 'yiari'), 'name' => 'title', 'type' => 'text'],
                    ['key' => 'field_timeline_text', 'label' => __('Teks', 'yiari'), 'name' => 'text', 'type' => 'textarea', 'rows' => 4],
                ],
            ],

            ['key' => 'field_about_org_tab', 'label' => __('Struktur Organisasi', 'yiari'), 'name' => '', 'type' => 'tab', 'placement' => 'top'],
            ['key' => 'field_about_org_label', 'label' => __('Label Atas', 'yiari'), 'name' => 'org_label', 'type' => 'text', 'default_value' => __('Kepemimpinan Kami', 'yiari')],
            ['key' => 'field_about_org_title', 'label' => __('Judul', 'yiari'), 'name' => 'org_title', 'type' => 'text', 'default_value' => __('Struktur Organisasi', 'yiari')],
            ['key' => 'field_about_org_desc', 'label' => __('Deskripsi', 'yiari'), 'name' => 'org_desc', 'type' => 'textarea', 'rows' => 3],
            [
                'key' => 'field_about_org_groups', 'label' => __('Kelompok Organisasi', 'yiari'), 'name' => 'org_groups',
                'type' => 'repeater', 'min' => 1, 'max' => 8, 'layout' => 'row',
                'button_label' => __('Tambah Kelompok', 'yiari'),
                'sub_fields' => [
                    ['key' => 'field_org_group_name', 'label' => __('Nama Kelompok', 'yiari'), 'name' => 'group_name', 'type' => 'text'],
                    [
                        'key' => 'field_org_members', 'label' => __('Anggota', 'yiari'), 'name' => 'members',
                        'type' => 'repeater', 'min' => 1, 'layout' => 'table',
                        'button_label' => __('Tambah Anggota', 'yiari'),
                        'sub_fields' => [
                            ['key' => 'field_member_photo', 'label' => __('Foto', 'yiari'), 'name' => 'photo', 'type' => 'image', 'return_format' => 'array'],
                            ['key' => 'field_member_name', 'label' => __('Nama', 'yiari'), 'name' => 'name', 'type' => 'text'],
                            ['key' => 'field_member_role', 'label' => __('Jabatan', 'yiari'), 'name' => 'role', 'type' => 'text'],
                        ],
                    ],
                ],
            ],
        ],
    ]);
});
