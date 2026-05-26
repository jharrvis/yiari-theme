<?php
$label = yiari_field('org_label', __('Kepemimpinan Kami', 'yiari'));
$title = yiari_field('org_title', __('Struktur Organisasi', 'yiari'));
$desc = yiari_field('org_desc', __('Didukung oleh pengurus dan profesional berpengalaman untuk memastikan tata kelola yang efektif dan berintegritas.', 'yiari'));
$org_groups = yiari_field('org_groups', []);
if (empty($org_groups)) {
    $org_groups = [
        ['group_name' => __('Pembina Yayasan', 'yiari'), 'members' => [['photo' => null, 'name' => 'Unggul Suprayitno', 'role' => __('Pembina Yayasan', 'yiari')], ['photo' => null, 'name' => 'Alan Knight', 'role' => __('Pembina Yayasan', 'yiari')], ['photo' => null, 'name' => 'Gavin Bruce', 'role' => __('Pembina Yayasan', 'yiari')]]],
        ['group_name' => __('Pengawas Yayasan', 'yiari'), 'members' => [['photo' => null, 'name' => 'Veronika Hutting', 'role' => __('Ketua Pengawas', 'yiari')], ['photo' => null, 'name' => 'Mesayu Yulia', 'role' => __('Pengawas', 'yiari')], ['photo' => null, 'name' => 'Drymer Heidi', 'role' => __('Pengawas', 'yiari')]]],
    ];
}
?>
<section class="about-org-section" id="struktur-organisasi" x-data="{ openPanel: 0 }">
  <div class="container">
    <div class="stats-layout about-org-header">
      <div class="stats-intro"><?php yiari_section_label($label, true); ?><h2 class="section-heading-lg"><?php echo esc_html($title); ?></h2></div>
      <div class="stats-desc"><p class="section-description"><?php echo esc_html($desc); ?></p></div>
    </div>
    <div class="about-org-accordion">
      <?php foreach ($org_groups as $gi => $group): ?>
        <section class="about-org-panel" :class="{ 'is-open': openPanel === <?php echo $gi; ?> }">
          <button class="about-org-trigger" type="button" @click="openPanel = openPanel === <?php echo $gi; ?> ? -1 : <?php echo $gi; ?>" :aria-expanded="openPanel === <?php echo $gi; ?>">
            <span><?php echo esc_html($group['group_name'] ?? ''); ?></span>
            <i data-lucide="chevron-down" class="about-org-chevron" :class="{ 'is-open': openPanel === <?php echo $gi; ?> }"></i>
          </button>
          <div class="about-org-content" x-show="openPanel === <?php echo $gi; ?>">
            <div class="about-org-grid">
              <?php foreach (($group['members'] ?? []) as $member): ?>
                <article class="about-org-member">
                  <div class="about-org-photo">
                    <?php if (!empty($member['photo']['url'])): ?>
                      <img src="<?php echo esc_url($member['photo']['url']); ?>" alt="<?php echo esc_attr($member['name'] ?? ''); ?>" />
                    <?php else: ?>
                      <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/organization/board-placeholder.webp'); ?>" alt="<?php echo esc_attr($member['name'] ?? ''); ?>" />
                    <?php endif; ?>
                  </div>
                  <h3 class="about-org-name"><?php echo esc_html($member['name'] ?? ''); ?></h3>
                  <p class="about-org-role"><?php echo esc_html($member['role'] ?? ''); ?></p>
                </article>
              <?php endforeach; ?>
            </div>
          </div>
        </section>
      <?php endforeach; ?>
    </div>
  </div>
</section>
