<?php

require_once 'eventlocations.civix.php';
use CRM_Eventlocations_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function eventlocations_civicrm_config(&$config) {
  _eventlocations_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function eventlocations_civicrm_xmlMenu(&$files) {
  _eventlocations_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function eventlocations_civicrm_install() {
  _eventlocations_civix_civicrm_install();
  CRM_EventLocations_Utils::installTableData();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function eventlocations_civicrm_postInstall() {
  _eventlocations_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function eventlocations_civicrm_uninstall() {
  _eventlocations_civix_civicrm_uninstall();
  CRM_Core_DAO::executeQuery("DROP TABLE IF EXISTS civicrm_loc_block_entity");
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function eventlocations_civicrm_enable() {
  _eventlocations_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function eventlocations_civicrm_disable() {
  _eventlocations_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function eventlocations_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _eventlocations_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function eventlocations_civicrm_managed(&$entities) {
  _eventlocations_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function eventlocations_civicrm_caseTypes(&$caseTypes) {
  _eventlocations_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function eventlocations_civicrm_angularModules(&$angularModules) {
  _eventlocations_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function eventlocations_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _eventlocations_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_alterSettingsMetaData().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsMetaData
 *
 */
function eventlocations_civicrm_alterSettingsMetaData(&$settingsMetadata, $domainID, $profile) {
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function eventlocations_civicrm_entityTypes(&$entityTypes) {
  _eventlocations_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
 */
function eventlocations_civicrm_navigationMenu(&$menu) {
  _eventlocations_civix_insert_navigation_menu($menu, 'Administer/CiviEvent', [
    'label' => ts('Event Locations', ['domain' => 'eventlocations']),
    'name' => 'event_locations',
    'url' => CRM_Utils_System::url('civicrm/eventlocations', 'reset=1&action=browse', TRUE),
    'active' => 1,
    'permission_operator' => 'AND',
    'permission' => 'administer Event Locations',
  ]);
}

/**
 * Implements hook_civicrm_permission().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_permission
 *
 */
function eventlocations_civicrm_permission(&$permissions) {
  $prefix = ts('CiviCRM ') . ': ';
  $permissions['administer Event Locations'] = [
    $prefix . ts('administer Event Locations'),
    ts('Add/Edit/View/Delete Event locations'),
  ];
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 *
 */
function eventlocations_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Event_Form_ManageEvent_Location') {
    $form->removeElement('loc_event_id');
    $locationEvents = CRM_EventLocations_Utils::getLocations();
    $form->add(
      'select',
      'loc_event_id',
      ts('Use Location'),
      $locationEvents,
      FALSE,
      ['class' => 'crm-select2']
    );
    CRM_Core_Resources::singleton()->addScript("
      CRM.$(function($) {
        $('#Address_Block_1 .crm-edit-address-form').hide();
        $('input[name=location_option][value=2]').prop('checked', true).trigger('click');
        $('input[name=location_option][value=1]').closest('td').hide();
        $('tr.crm-event-manage-location-form-block-location_option').hide();
      })"
    );
  }

}

/**
 * Implements hook_civicrm_alterMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterMenu
 *
 */
function eventlocations_civicrm_alterMenu(&$items) {
  if (!empty($items['civicrm/ajax/locBlock'])) {
    $items['civicrm/ajax/locBlock']['page_callback'] = 'CRM_EventLocations_Utils::getLocBlock';
  }
}

/**
 * Implements hook_civicrm_pre().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_pre
 *
 */
function eventlocations_civicrm_pre($op, $objectName, $id, &$params) {
  if ($objectName == 'Event' && in_array($op, ['delete']) && $id) {
    CRM_Core_DAO::executeQuery('
      UPDATE civicrm_event
      SET loc_block_id = NULL
      WHERE id = %1
    ', [1 => [$id, 'Integer']]);
  }
}
