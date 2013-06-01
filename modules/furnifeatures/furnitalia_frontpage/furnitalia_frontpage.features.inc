<?php
/**
 * @file
 * furnitalia_frontpage.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function furnitalia_frontpage_ctools_plugin_api() {
  list($module, $api) = func_get_args();
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function furnitalia_frontpage_views_api() {
  return array("version" => "3.0");
}

/**
 * Implements hook_node_info().
 */
function furnitalia_frontpage_node_info() {
  $items = array(
    'frontpage_promo' => array(
      'name' => t('Frontpage Promo'),
      'base' => 'node_content',
      'description' => t('Create a content to go on front page.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  return $items;
}
