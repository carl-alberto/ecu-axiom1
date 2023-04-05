<?php $menu = get_nav_menu_locations();
if (!empty($menu["secondary"])): ?>
<nav id="footer-nav" aria-label="footer navigation">
  <?php get_template_part('partials/components/navigation/nav-footer'); ?>
</nav>
<?php endif; ?>
