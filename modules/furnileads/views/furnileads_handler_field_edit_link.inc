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
		//dsm($values);
		$lid = $values->lid;
		$attrs = array('html' => TRUE);
		return l("View", "admin/leads/$lid", $attrs) . ' | ' . l("Edit", "admin/leads/$lid/edit", $attrs);
	}

}
