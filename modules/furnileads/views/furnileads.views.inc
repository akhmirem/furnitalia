<?php

/**
 * @file furnileads.views.inc
 * Provides support for the Views module.
 *
 */

/**
 * Implements hook_views_data().
 */
function furnileads_views_data() {
  $data = array();

  // ----------------------------------------------------------------
  // nodequeue_nodes table
  $data['furnileads']['table']['group'] = t('Furnitalia Leads');
  
  $data['furnileads']['table']['base'] = array(
    'field' => 'lid',
    'title' => t('Leads'),
    'help' => t('Leads records stored in DB.'),
  );

  
 // Pull in user fields directly.
  /*$data['users']['table']['join']['furnileads'] = array(
    'left_field' => 'uid',
    'field' => 'uid',
  );*/

  // The nodequeue_nodes table is how nodequeue connects to the
  // rest of the world, but we will only join it in via relationship.

  // Lead ID
  $data['furnileads']['lid'] = array(
    'title' => t('Lead ID'),
    'help' => t('The ID of lead record.'),
    'field' => array(
      'handler' => 'views_handler_field_numeric',
      'click sortable' => TRUE,
     ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_numeric',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_numeric',
    ),
  );
  
  // First Name
  $data['furnileads']['first_name'] = array(
    'title' => t('Lead First Name'),
    'help' => t('The first name of lead.'),
    'field' => array(
      'handler' => 'views_handler_field',
      'click sortable' => TRUE,
     ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_string',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_string',
    ),
  );
  
  // Last Name
  $data['furnileads']['last_name'] = array(
    'title' => t('Lead Last Name'),
    'help' => t('The last name of lead.'),
    'field' => array(
      'handler' => 'views_handler_field',
      'click sortable' => TRUE,
     ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_string',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_string',
    ),
  );
  
  // Email
  $data['furnileads']['email'] = array(
    'title' => t('Lead Email'),
    'help' => t('Lead Email.'),
    'field' => array(
      'handler' => 'views_handler_field',
      'click sortable' => TRUE,
     ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_string',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_string',
    ),
  );

  // Address
  $data['furnileads']['address'] = array(
    'title' => t('Lead Address'),
    'help' => t('Lead Address.'),
    'field' => array(
      'handler' => 'views_handler_field',
      'click sortable' => TRUE,
     ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_string',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_string',
    ),
  );
  
  // Phone #
  $data['furnileads']['phone'] = array(
    'title' => t('Lead Phone Number'),
    'help' => t('Phone #.'),
    'field' => array(
      'handler' => 'views_handler_field',
      'click sortable' => TRUE,
     ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_string',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_string',
    ),
  );
  
  // ZIP
  $data['furnileads']['zip'] = array(
    'title' => t('Lead ZIP'),
    'help' => t('Lead ZIP code.'),
    'field' => array(
      'handler' => 'views_handler_field',
      'click sortable' => TRUE,
     ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_string',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_string',
    ),
  );
  
  // Status
  $data['furnileads']['status'] = array(
    'title' => t('Lead Status'),
    'help' => t('Lead Status.'),
    'field' => array(
      'handler' => 'furnileads_handler_field_status',
     ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
    'filter' => array(
      'handler' => 'furnileads_handler_filter_status_select',
      'label' => t('Lead status'),
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_string',
    ),
  );

  $data['furnileads']['status-filter'] = array(
    'title' => t('Filter by Lead Status'),
    'help' => t('Lead Status.'),
    'filter' => array(
      'handler' => 'furnileads_handler_filter_status_select',
    ),
  );

  // created timestamp
  $data['furnileads']['body'] = array(
    'title' => t('Lead request body'),
    'help' => t('Lead request body.'),
    'field' => array(
      'handler' => 'views_handler_field',
      'click sortable' => TRUE,
    ),
    'sort' => array(
      'handler' => 'views_handler_sort_string',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_string',
    ),
  );

  // created timestamp
  $data['furnileads']['created'] = array(
    'title' => t('Lead creation date'),
    'help' => t('Lead creation timestamp.'),
    'field' => array(
      'handler' => 'views_handler_field_date',
      'click sortable' => TRUE,
    ),
    'sort' => array(
      'handler' => 'views_handler_sort_date',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_date',
    ),
  );
  
  // last action timestamp
  $data['furnileads']['updated'] = array(
    'title' => t('Lead last action timestamp'),
    'help' => t('Last action taken by lead owner timestamp.'),
    'field' => array(
      'handler' => 'views_handler_field_date',
      'click sortable' => TRUE,
    ),
    'sort' => array(
      'handler' => 'views_handler_sort_date',
    ),
    'filter' => array(
      'handler' => 'views_handler_filter_date',
    ),
  );

  
  // Edit Link
  $data['furnileads']['edit_link'] = array(
    'title' => t('Edit Lead Link'),
    'help' => t('Link to edit lead record.'),
    'real field' => 'lid',
    'field' => array(
      'handler' => 'furnileads_handler_field_edit_link',
    ),
  );
  
  // uid field
  $data['furnileads']['uid'] = array(  
  	'title' => t('Lead Owner'),
    'help' => t("The name of the lead owner."),
    'relationship' => array(
      'title' => t('Lead Owner'),
      'base' => 'users',
      'base field' => 'uid',
      'handler' => 'views_handler_relationship',
      'label' => t('owner'),
    ),
    'field' => array(
      'handler' => 'furnileads_handler_field_owner',
      'click sortable' => TRUE,
    ),
    'filter' => array(
      'handler' => 'furnileads_handler_filter_uid_select',
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_string',
    ),
  );
  
  /*$data['furnileads']['uid'] = array(
    'title' => t('Lead Owner'),
    'relationship' => array(
      'title' => t('Lead Owner'),
      'base' => 'users',
      'base field' => 'uid',
      'handler' => 'views_handler_relationship',
      'label' => t('owner'),
    ),
    'real field' => 'updated_by',
    'filter' => array(
      'handler' => 'views_handler_filter_numeric',
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_numeric',
    ),
    'field' => array(
      'handler' => 'views_handler_field_user_name',
    ),
  );*/





  /*
// specialized is null/is not null filter
  $data['nodequeue_nodes']['in_queue'] = array(
    'title' => t('In queue'),
    'help' => t('Filter to ensure a node IS or IS NOT in the related queue'),
    'filter' => array(
      'handler' => 'nodequeue_handler_filter_in_queue',
      'label' => t('In queue'),
    ),
  );
*/

  return $data;
}

/**
 * Implements hook_views_handlers().
 */
function furnileads_views_handlers() {

  return array(
    'info' => array(
      'path' => drupal_get_path('module', 'furnileads') . '/views',
    ),
    'handlers' => array(
      'furnileads_handler_field_status' => array(
        'parent' => 'views_handler_field',
        'file' => 'furnileads_handler_field_status.inc',
      ),
      'furnileads_handler_filter_status_select' => array(
        'parent' => 'views_handler_filter_string',
      ),
    ),
  );
}

/**
 * Implements hook_views_data_alter().
 */
/*
function nodequeue_views_data_alter(&$data) {
  // queue relationship
  $data['node']['nodequeue_rel'] = array(
    'group' => t('Nodequeue'),
    'title' => t('Queue'),
    'help' => t('Create a relationship to a nodequeue.'),
    'real field' => 'nid',
    'relationship' => array(
      'handler' => 'nodequeue_handler_relationship_nodequeue',
      'base' => 'nodequeue_nodes',
      'field' => 'nid',
      'label' => t('queue'),
    ),
  );

  // links
  $data['node']['nodequeue_links'] = array(
    'group' => t('Nodequeue'),
    'title' => t('Add/remove links'),
    'help' => t('Display add/remove links for all eligible nodequeues. Note that this performs a node_load so could add many extra queries to a view.'),
    'real field' => 'nid',
    'field' => array(
      'handler' => 'nodequeue_handler_field_links',
    ),
  );

  $data['node']['nodequeue_queue_tab'] = array(
    'group' => t('Nodequeue'),
    'title' => t('Link to tab'),
    'help' => t('Display add/remove links for all eligible nodequeues. Note that this performs a node_load so could add many extra queries to a view.'),
    'real field' => 'nid',
    'field' => array(
      'handler' => 'nodequeue_handler_field_queue_tab',
    ),
  );

  // all queues
  $data['node']['nodequeue_all_queues'] = array(
    'group' => t('Nodequeue'),
    'title' => t('All queues'),
    'help' => t('Display all queues a node is a member of.'),
    'real field' => 'nid',
    'field' => array(
      'handler' => 'nodequeue_handler_field_all_queues',
    ),
  );

  // all subqueues
  $data['node']['nodequeue_all_subqueues'] = array(
    'group' => t('Nodequeue'),
    'title' => t('All subqueues'),
    'help' => t('Display all subqueues a node is a member of.'),
    'real field' => 'nid',
    'field' => array(
      'handler' => 'nodequeue_handler_field_all_subqueues',
    ),
  );
}
*/
