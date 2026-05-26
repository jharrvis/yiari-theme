# YIARI WordPress Theme

Tema resmi Yayasan Inisiasi Alam Rehabilitasi Indonesia.  
Dibangun tanpa page builder — murni PHP template parts + ACF Free + Alpine.js.

---

## Persyaratan

| Dependensi | Versi minimum |
|---|---|
| WordPress | 6.4+ |
| PHP | 8.1+ |
| **Advanced Custom Fields (ACF) Free** | 6.x |

> Plugin yang **tidak diperlukan**: Elementor, WPBakery, Beaver Builder, Yoast (opsional), dll.

---

## Instalasi

### 1. Upload tema
Salin folder `yiari-theme/` ke `wp-content/themes/`.  
Aktifkan di **Appearance → Themes**.

### 2. Install ACF Free
Download dari [wordpress.org/plugins/advanced-custom-fields](https://wordpress.org/plugins/advanced-custom-fields/)  
atau lewat **Plugins → Add New → search "Advanced Custom Fields"**.  
Aktifkan plugin.

> Field groups **sudah terdaftar via PHP** di `inc/acf-fields.php` — tidak perlu import JSON.

### 3. Buat halaman & pilih template

| Halaman | Template |
|---|---|
| Beranda (Home) | `Home` |
| Tentang Kami | `About Us` |

Di wp-admin: **Pages → Add New → Page Attributes → Template → pilih sesuai tabel.**

### 4. Set halaman depan
**Settings → Reading → Your homepage displays → A static page**  
Pilih halaman beranda yang sudah dibuat.

### 5. Upload gambar & logo
- **Appearance → Customize → Site Identity → Logo** untuk logo navbar & footer.
- Setiap gambar section diatur dari **field ACF** di masing-masing halaman.

### 6. Isi konten via ACF
Buka halaman (misal Beranda) → **Edit** → gulir ke bawah editor.  
Akan muncul field group sesuai template:

- **🏠 Home Page – Sections** → tab Hero, Stats, Krisis, Story, Approach, Donate CTA
- **🌿 About Us – Sections** → tab Hero, Visi Misi, Timeline, Organisasi

---

## Struktur File

```
yiari-theme/
├── style.css                    ← metadata tema WordPress
├── index.php                    ← fallback WordPress
├── header.php                   ← (kosong, diperlukan WP core)
├── footer.php                   ← (kosong, diperlukan WP core)
├── functions.php                ← setup, enqueue, image sizes
│
├── inc/
│   ├── acf-fields.php           ← semua field group ACF (PHP)
│   └── helpers.php              ← fungsi bantu: yiari_img(), yiari_btn(), dll
│
├── templates/
│   ├── home.php                 ← Page Template: Home
│   └── about.php                ← Page Template: About Us
│
├── template-parts/
│   ├── global/
│   │   ├── header.php           ← navbar + mobile menu
│   │   └── footer.php           ← footer + scroll-to-top
│   └── sections/
│       ├── hero.php             ← [Home] Hero section
│       ├── stats.php            ← [Home] Statistik
│       ├── crisis.php           ← [Home] 3 Krisis
│       ├── story.php            ← [Home] Story feature
│       ├── approach.php         ← [Home+About] Program approach
│       ├── news.php             ← [Home] Berita terbaru (dari WP posts)
│       ├── donate-cta.php       ← [Home] CTA donasi
│       ├── about-hero.php       ← [About] Hero
│       ├── about-vision.php     ← [About] Visi & Misi
│       ├── about-timeline.php   ← [About] Timeline milestone
│       └── about-org.php        ← [About] Struktur organisasi (accordion)
│
└── assets/
    ├── css/styles.css           ← CSS utama (sama persis dari desain HTML)
    └── js/main.js               ← Alpine.js logic (sama persis dari desain HTML)
```

---

## Cara Menambah Halaman Baru

### Contoh: Halaman "Program"

**1. Buat template file**  
`templates/program.php`:
```php
<?php /* Template Name: Program */ ?>
<?php get_template_part('template-parts/global/header'); ?>

<?php get_template_part('template-parts/sections/about-hero'); // reuse section yang ada ?>
<?php get_template_part('template-parts/sections/approach'); ?>

<?php get_template_part('template-parts/global/footer'); ?>
```

**2. Daftarkan ACF field group** di `inc/acf-fields.php`:
```php
acf_add_local_field_group([
    'key'      => 'group_program',
    'title'    => '📋 Program – Sections',
    'location' => [[['param' => 'page_template', 'operator' => '==', 'value' => 'templates/program.php']]],
    'fields'   => [
        // field-field kamu
    ],
]);
```

**3. Buat halaman di WordPress** dan pilih template "Program".

---

## Berita / Blog

Section berita di Home **otomatis mengambil 3 post terbaru** dari WordPress.  
Tidak perlu setting tambahan — cukup buat post di **Posts → Add New**.

---

## Customizer Settings

Buka **Appearance → Customize** untuk mengubah:
- Logo situs
- Tagline footer (`footer_tagline`)
- Email footer (`footer_email`)
- URL sosial media (`social_instagram`, `social_twitter`, `social_facebook`, `social_youtube`)

---

## Catatan Teknis

- **Tidak ada page builder** — layout dikontrol murni dari PHP template parts.
- **ACF field group didaftarkan via PHP** (`acf_add_local_field_group`) bukan via JSON/DB, sehingga portable antar environment.
- **Alpine.js** diload dari CDN (defer) — semua interaktivitas (menu mobile, accordion, approach hover, counter animasi) terpusat di `assets/js/main.js`.
- **Lucide icons** diload dari CDN (unpkg).
- Semua output user-facing menggunakan fungsi escape WordPress (`esc_html`, `esc_url`, `esc_attr`, `wp_kses_post`).
