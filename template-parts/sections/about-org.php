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
      <div class="stats-intro"><?php yiari_section_label($label, true); ?><h2 class="section-heading"><?php echo esc_html($title); ?></h2></div>
      <div class="stats-desc"><p class="section-description"><?php echo esc_html($desc); ?></p></div>
    </div>
    <div class="about-org-accordion">
      <?php foreach ($org_groups as $gi => $group): ?>
        <section class="about-org-panel" :class="{ 'is-open': openPanel === <?php echo $gi; ?> }">
          <button class="about-org-trigger" type="button" @click="openPanel = openPanel === <?php echo $gi; ?> ? -1 : <?php echo $gi; ?>" :aria-expanded="openPanel === <?php echo $gi; ?>">
            <span><?php echo esc_html($group['group_name'] ?? ''); ?></span>
            <i data-lucide="chevron-down" class="about-org-chevron" :class="{ 'is-open': openPanel === <?php echo $gi; ?> }"></i>
          </button>
          <div
            class="about-org-content"
            x-show="openPanel === <?php echo $gi; ?>"
            x-transition:enter="about-org-transition"
            x-transition:enter-start="about-org-transition-enter-start"
            x-transition:enter-end="about-org-transition-enter-end"
            x-transition:leave="about-org-transition"
            x-transition:leave-start="about-org-transition-enter-end"
            x-transition:leave-end="about-org-transition-enter-start"
          >
            <div class="about-org-grid">
              <?php foreach (($group['members'] ?? []) as $member): ?>
                <?php
                $name = trim((string) ($member['name'] ?? ''));
                $role = trim((string) ($member['role'] ?? ''));
                $initials = '';
                if ($name !== '') {
                    $parts = preg_split('/\s+/', $name) ?: [];
                    foreach (array_slice($parts, 0, 2) as $part) {
                        $initials .= function_exists('mb_substr') ? mb_substr($part, 0, 1) : substr($part, 0, 1);
                    }
                }
                ?>
                <article class="about-org-member">
                  <div class="about-org-member-top">
                    <h3 class="about-org-name"><?php echo esc_html($name); ?></h3>
                    <span class="about-org-initials"><?php echo esc_html(strtoupper($initials)); ?></span>
                  </div>
                  <div class="about-org-member-bottom">
                    <p class="about-org-role"><?php echo esc_html($role); ?></p>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          </div>
        </section>
      <?php endforeach; ?>
    </div>
  </div>
</section>
