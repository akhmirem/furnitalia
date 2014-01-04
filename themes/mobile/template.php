<?php


function mobile_preprocess_html(&$vars) {
  // If "mobile web app" selected:
  if (theme_get_setting('mobilewebapp')) {
  // set showgrid variable for html.tpl.php 
    $vars['meta_mobilewebapp'] = 1;
  }
  else {
    $vars['meta_mobilewebapp'] = 0;
  }

}


function mobile_preprocess_block(&$variables, $hook) {
  // Use a template with no wrapper for the page's main content.
  // Classes describing the position of the block within the region.
  $variables['classes_array'][] = $variables['block_zebra'];

  $variables['title_attributes_array']['class'][] = 'block-title';

  // Add Aria Roles via attributes.
  switch ($variables['block']->module) {
    case 'system':
      switch ($variables['block']->delta) {
        case 'main':
          // Note: the "main" role goes in the page.tpl, not here.
          break;
        case 'help':
        case 'powered-by':
          $variables['attributes_array']['role'] = 'complementary';
          break;
        default:
          // Any other "system" block is a menu block.
          $variables['attributes_array']['role'] = 'navigation';
          break;
      }
      break;
    case 'menu':
      $variables['attributes_array']['role'] = 'navigation';
      break;
    case 'menu_block':
      $variables['attributes_array']['role'] = 'navigation';
      break;
    case 'blog':
    case 'book':
      $variables['attributes_array']['role'] = 'navigation';
      break;
    case 'comment':
    case 'forum':
    case 'shortcut':
    case 'statistics':
      $variables['attributes_array']['role'] = 'navigation';
      break;
    case 'search':
      $variables['attributes_array']['role'] = 'search';
      break;
    case 'help':
      $variables['attributes_array']['role'] = 'note';
      break;
    case 'aggregator':
    case 'locale':
    case 'poll':
    case 'profile':
      $variables['attributes_array']['role'] = 'complementary';
      break;
    case 'node':
      switch ($variables['block']->delta) {
        case 'syndicate':
          $variables['attributes_array']['role'] = 'complementary';
          break;
        case 'recent':
          $variables['attributes_array']['role'] = 'navigation';
          break;
      }
      break;
    case 'user':
      switch ($variables['block']->delta) {
        case 'login':
          $variables['attributes_array']['role'] = 'form';
          break;
        case 'new':
        case 'online':
          $variables['attributes_array']['role'] = 'complementary';
          break;
      }
      break;
  }
}