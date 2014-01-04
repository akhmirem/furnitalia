<header role="banner">

  <?php if ($logo): ?>
    <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
      <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
    </a>
  <?php endif; ?>

  <hgroup>
    <?php if ($site_name): ?>
      <h1 id="site-name">
        <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
      </h1>
    <?php endif; ?>

    <?php if ($site_slogan): ?>
      <h2 id="site-slogan"><?php print $site_slogan; ?></h2>
    <?php endif; ?>
  </hgroup>

</header>

<?php print $messages; ?>

<main role="main" id="main-content">

  <?php if ($page['highlighted']): ?><div id="highlighted"><?php print render($page['highlighted']); ?></div><?php endif; ?>

  <?php if ($tabs): ?><div class="tabs"><?php print render($tabs); ?></div><?php endif; ?>

  <a id="main-content"></a>

  <?php if ($title): ?>
    <?php
    // open article tag if page is a node
    if (($page) && (arg(0) == 'node')): ?>
      <article role="article">
    <?php endif; ?>
  <?php print render($title_prefix); ?>
    <h1 class="title" id="page-title"><?php print $title; ?></h1>
  <?php endif; ?>
  <?php print render($title_suffix); ?>

  <?php print render($page['help']); ?>

  <?php if ($action_links): ?><nav><ul class="action-links"><?php print render($action_links); ?></ul></nav><?php endif; ?>

  <?php print render($page['content']); ?>

  <?php if ($breadcrumb): ?>
    <nav id="breadcrumb"><?php print $breadcrumb; ?></nav>
  <?php endif; ?>

</main><!--/main -->

<?php if ($page['supplementary']): ?>
  <aside id="supplementary" class="column sidebar" role="complementary">
  <?php print render($page['supplementary']); ?>
<?php endif; ?>

<?php if ($main_menu || $secondary_menu): ?>
  <nav role="navigation">

    <?php print theme('links__system_main_menu', array('links' => $main_menu, 'attributes' => array('id' => 'main-menu', 'class' => array('links', 'inline', 'clearfix')), 'heading' => t('Main menu'))); ?>

    <?php print theme('links__system_secondary_menu', array('links' => $secondary_menu, 'attributes' => array('id' => 'secondary-menu', 'class' => array('links', 'inline', 'clearfix')), 'heading' => t('Secondary menu'))); ?>

  </nav>
<?php endif; ?>

  <?php if ($page['footer']): ?>
    <footer role="contentinfo">
      <?php print render($page['footer']); ?>
    </footer>
  <?php endif; ?>