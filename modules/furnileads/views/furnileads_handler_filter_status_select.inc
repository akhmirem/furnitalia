<?php
/**
 * My custom filter handler
 */
class furnileads_handler_filter_status_select extends views_handler_filter_string {
	
  /**
   * Override parent get_value_options() function.
   *
   * @return
   *   Return the stored values in $this->value_options if someone expects it.
   */
  /*function get_value_options() {
  
	$lead_status_options = array (
		'N' => 'Open',
		'P' => 'Pending',
		'S' => 'Sold',
		'X' => 'Closed',
	);
	
	$this->value_title = t('Lead status');
	$this->value_options = $lead_status_options;
	
	return $this->value_options;
  }*/
  
  /**
   * Overrides views_handler_field::query().
   */
  /*function query() {
    $lead_status_options = array (
		'N' => 'Open',
		'P' => 'Pending',
		'S' => 'Sold',
		'X' => 'Closed',
	);
    $this->query->add_field('furnileads', 'status');
    $this->query->add_where($this->options['group'], 'furnileads.status', $lead_status_options, empty($this->value) ? 'NOT IN' : 'IN');
  }*/
  
  /**
   * Shortcut to display the exposed options form.
   */
  function value_form(&$form, &$form_state) {
  
  	$lead_status_options = array (
		'N' => 'Open',
		'P' => 'Pending',
		'S' => 'Sold',
		'X' => 'Closed',
	);
	
    $form['value'] = array(
    '#type' => 'select',
    '#title' => t('Status'),
    '#options' => $lead_status_options,
    );
 
    return $form;
  }
  
}