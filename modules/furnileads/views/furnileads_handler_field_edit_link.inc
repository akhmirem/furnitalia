<?php
/**
 * @file
 * Field handler for lead status
 *
 * @ingroup views_field_handlers
 */
class furnileads_handler_field_edit_link extends views_handler_field {
  	
	/**
	* Render the field.
	*
	* @param $values
	*   The values retrieved from the database.
	*/
	function render($values) {
		$lid = $values->lid;
		$attrs = array('html' => TRUE);
		if ($this->view->current_display == 'page') {
			$attrs['query'] = array('destination' => 'admin/leads');
		} else if ($this->view->current_display == 'page_1') {
			$attrs['query'] = array('destination' => 'admin/leads/lastweek');		
		}
		return l("View", "admin/leads/$lid", $attrs) . ' | ' . l("Edit", "admin/leads/$lid/edit", $attrs);
	}

}
