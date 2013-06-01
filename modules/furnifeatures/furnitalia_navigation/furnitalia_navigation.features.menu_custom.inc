<?php
/**
 * @file
 * furnitalia_navigation.features.menu_custom.inc
 */

/**
 * Implements hook_menu_default_menu_custom().
 */
function furnitalia_navigation_menu_default_menu_custom() {
  $menus = array();

  // Exported menu: menu-categories.
  $menus['menu-categories'] = array(
    'menu_name' => 'menu-categories',
    'title' => 'Categories',
    'description' => 'List of Furnitalia Categories.',
  );
  // Translatables
  // Included for use with string extractors like potx.
  t('Categories');
  t('List of Furnitalia Categories.');


  return $menus;
}
