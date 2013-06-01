<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 *
 *   For more information, please visit the Theme Developer's Guide on
 *   Drupal.org: http://drupal.org/node/173880
 *
 * CREATE OR MODIFY VARIABLES FOR YOUR THEME
 *
 *   Each tpl.php template file has several variables which hold various pieces
 *   of content. You can modify those variables (or add new ones) before they
 *   are used in the template files by using preprocess functions.
 *
 *   This makes THEME_preprocess_HOOK() functions the most powerful functions
 *   available to themers.
 *
 *   It works by having one preprocess function for each template file or its
 *   derivatives (called theme hook suggestions). For example:
 *     THEME_preprocess_page    alters the variables for page.tpl.php
 *     THEME_preprocess_node    alters the variables for node.tpl.php or
 *                              for node--forum.tpl.php
 *     THEME_preprocess_comment alters the variables for comment.tpl.php
 *     THEME_preprocess_block   alters the variables for block.tpl.php
 *
 *   For more information on preprocess functions and theme hook suggestions,
 *   please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/node/223440 and http://drupal.org/node/1089656
 */

/**
 * Implementation of hook_js_alter
 */
function furnitheme_js_alter(&$javascript) {

	if (isset($javascript['misc/jquery.js'])) {
		$jquery_path = drupal_get_path('theme','furnitheme') . '/js/jquery-1.8.0.min.js';
		
		//We duplicate the important information from the Drupal one
		$javascript[$jquery_path] = $javascript['misc/jquery.js'];
		//..and we update the information that we care about
		$javascript[$jquery_path]['version'] = '1.8.0';
		$javascript[$jquery_path]['data'] = $jquery_path;
		
		unset($javascript['misc/jquery.js']);
	}
	
	if (isset($javascript['misc/jquery.form.js'])) {
		$jquery_path = drupal_get_path('theme','furnitheme') . '/js/jquery.form.min.js';
		
		//We duplicate the important information from the Drupal one
		$javascript[$jquery_path] = $javascript['misc/jquery.form.js'];
		//..and we update the information that we care about
		$javascript[$jquery_path]['version'] = '3.35';
		$javascript[$jquery_path]['data'] = $jquery_path;
		
		unset($javascript['misc/jquery.form.js']);
	}
	//jquery.form.js
	
	if (isset($javascript['misc/ui/jquery.ui.core.min.js'])) {
		//We define the path of our new jquery core file
		$jquery_path = drupal_get_path('theme','furnitheme') . '/js/jquery-ui/js/jquery-ui-1.10.3.custom.min.js';
		
		//We duplicate the important information from the Drupal one
		$javascript[$jquery_path] = $javascript['misc/ui/jquery.ui.core.min.js'];
		//..and we update the information that we care about
		$javascript[$jquery_path]['version'] = '1.10.3';
		$javascript[$jquery_path]['data'] = $jquery_path;
		
		unset($javascript['misc/ui/jquery.ui.core.min.js']);
	}
}

/**
 * Implementation of hook_css_alter
 */
function furnitheme_css_alter(&$css) {

	$jquery_ui_theme_path = drupal_get_path('theme','furnitheme') . '/css/ui/base/'; //base-1.8.0.min.js';
	
	if (isset($css['misc/ui/jquery.ui.core.css'])) {
		$new_path = $jquery_ui_theme_path . "jquery.ui.core.min.css";
		$css[$new_path] = $css['misc/ui/jquery.ui.core.css'];
		$css[$new_path]['version'] = '1.10.3';
		$css[$new_path]['data'] = $jquery_ui_theme_path . "jquery-ui.core.min.css";
		
		unset($css['misc/ui/jquery.ui.core.css']);
	}
	
	if (isset($css['misc/ui/jquery.ui.theme.css'])) {
		$new_path = $jquery_ui_theme_path . "jquery.ui.theme.min.css";
		$css[$new_path] = $css['misc/ui/jquery.ui.theme.css'];
		$css[$new_path]['version'] = '1.10.3';
		$css[$new_path]['data'] = $jquery_ui_theme_path . "jquery.ui.theme.min.css";
		
		unset($css['misc/ui/jquery.ui.theme.css']);
	}
}

function furnitheme_preprocess_page(&$vars) {
	drupal_add_css(drupal_get_path('theme', 'furnitheme') . '/css/ui/base/jquery.ui.all.css', array('group' => -100, 'every_page' => TRUE));
	drupal_add_css(drupal_get_path('theme', 'furnitheme') . '/css/ui/base/jquery.ui.slider.min.css', array('group' => -100, 'every_page' => TRUE));
	
	drupal_add_js('misc/jquery.form.js');
	drupal_add_library('system', 'drupal.ajax');
	drupal_add_js(drupal_get_path('module', 'webform_ajax') . '/js/webform_ajax.js', 'file');
	
	//item description page thumbnail gallery
	drupal_add_js(drupal_get_path('theme', 'furnitheme') . '/js/jquery.pikachoose.min.js');
	drupal_add_css(drupal_get_path('theme', 'furnitheme') . '/css/pikachoose.css');

	
}

/**
 * Returns HTML for a select form element.
 *
 * It is possible to group options together; to do this, change the format of
 * $options to an associative array in which the keys are group labels, and the
 * values are associative arrays in the normal $options format.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array containing the properties of the element.
 *     Properties used: #title, #value, #options, #description, #extra,
 *     #multiple, #required, #name, #attributes, #size.
 *
 * @ingroup themeable
 */
function furnitheme_select($variables) {
  $element = $variables['element'];
  element_set_attributes($element, array('id', 'name', 'size'));
  _form_set_class($element, array('form-select'));

  return '<select' . drupal_attributes($element['#attributes']) . '>' . furnitheme_form_select_options($element) . '</select>';
}

/**
 * Converts a select form element's options array into HTML.
 *
 * @param $element
 *   An associative array containing the properties of the element.
 * @param $choices
 *   Mixed: Either an associative array of items to list as choices, or an
 *   object with an 'option' member that is an associative array. This
 *   parameter is only used internally and should not be passed.
 *
 * @return
 *   An HTML string of options for the select form element.
 */
function furnitheme_form_select_options($element, $choices = NULL) {
  if (!isset($choices)) {
    $choices = $element['#options'];
  }
  // array_key_exists() accommodates the rare event where $element['#value'] is NULL.
  // isset() fails in this situation.
  $value_valid = isset($element['#value']) || array_key_exists('#value', $element);
  $value_is_array = $value_valid && is_array($element['#value']);
  $options = '';
  $i = 0;
  foreach ($choices as $key => $choice) {
    if (is_array($choice)) {
      $options .= '<optgroup label="' . $key . '">';
      $options .= form_select_options($element, $choice);
      $options .= '</optgroup>';
    }
    elseif (is_object($choice)) {
      $options .= form_select_options($element, $choice->option);
    }
    else {
      $key = (string) $key;
      if ($value_valid && (!$value_is_array && (string) $element['#value'] === $key || ($value_is_array && in_array($key, $element['#value'])))) {
        $selected = ' selected="selected"';
      }
      else {
        $selected = '';
      }
      $options .= '<option value="' . check_plain($key) . '"' . $selected . ' ' . $element['#extra_option_attributes'][$i] . '>' . check_plain($choice) . '</option>';
    }
    
    ++$i;
  }
  return $options;
}


/**
 * Override or insert variables into the maintenance page template.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("maintenance_page" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_maintenance_page(&$variables, $hook) {
  // When a variable is manipulated or added in preprocess_html or
  // preprocess_page, that same work is probably needed for the maintenance page
  // as well, so we can just re-use those functions to do that work here.
  STARTERKIT_preprocess_html($variables, $hook);
  STARTERKIT_preprocess_page($variables, $hook);
}
// */

/**
 * Override or insert variables into the html templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("html" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_html(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');

  // The body tag's classes are controlled by the $classes_array variable. To
  // remove a class from $classes_array, use array_diff().
  //$variables['classes_array'] = array_diff($variables['classes_array'], array('class-to-remove'));
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_page(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the node templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_node(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');

  // Optionally, run node-type-specific preprocess functions, like
  // STARTERKIT_preprocess_node_page() or STARTERKIT_preprocess_node_story().
  $function = __FUNCTION__ . '_' . $variables['node']->type;
  if (function_exists($function)) {
    $function($variables, $hook);
  }
}
// */

/**
 * Override or insert variables into the comment templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_comment(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the region templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("region" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_region(&$variables, $hook) {
  // Don't use Zen's region--sidebar.tpl.php template for sidebars.
  //if (strpos($variables['region'], 'sidebar_') === 0) {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('region__sidebar'));
  //}
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_block(&$variables, $hook) {
  // Add a count to all the blocks in the region.
  // $variables['classes_array'][] = 'count-' . $variables['block_id'];

  // By default, Zen will use the block--no-wrapper.tpl.php for the main
  // content. This optional bit of code undoes that:
  //if ($variables['block_html_id'] == 'block-system-main') {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('block__no_wrapper'));
  //}
}
// */
