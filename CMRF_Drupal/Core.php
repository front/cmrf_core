<?php

/**
 * Drupal-based implementation of a CMRF Core
 *
 * @author Björn Endres, SYSTOPIA (endres@systopia.de)
 */

namespace CMRF\Drupal;

include_once('CMRF/Core/Core.php');
include_once('CMRF/Connection/Curl.php');
include_once('CMRF_Drupal/Call.php');

use CMRF\Core\Core       as AbstractCore;
use CMRF\Connection\Curl as CurlConnection;
use CMRF\Drupal\Call     as DrupalCall;


class Core extends AbstractCore {

  protected $connections = array();

  public function __construct() {
    
  }

  public function getDefaultProfile() {
    return 'default';
  }

  protected function getConnection($connector_id) {
    if (!isset($this->connections[$connector_id])) {
      $this->connections[$connector_id] = new CurlConnection($this, $connector_id);
    }

    return $this->connections[$connector_id];
  }

  public function isReady() {
    return $this->getConnection('test')->isReady();
  }


  /************************************
   *    Call related infrastructure   *
   ************************************/



  public function createCall($connector_id, $entity, $action, $parameters, $options = NULL, $callback = NULL) {
    if (empty($options['cache'])) {
      return new DrupalCall($connector_id, $this, $entity, $action, $parameters, $options, $callback);
    } else {
      // caching enabled -> see if it exists
      // TODO: implement
      return new DrupalCall($connector_id, $this, $entity, $action, $parameters, $options, $callback);
      // $id = $this->generateURN("call:curl");
      // return new LocalCall($id, $this, $entity, $action, $parameters, $options, $callback);
    }
  }

  public function getCall($call_id) {
    // TODO: implmenet
    return NULL;
  }

  public function findCall($options) {
    // TODO: implmenet
    return NULL;
  }


  /*********************************************************
   *  Use Drupal variables to store config for the moment  *
   *********************************************************/

  public function getConnectionProfiles() {
    error_log("B.getConnectionProfiles");
    return variable_get('cmrf_core_connection_profiles');
  }

  protected function storeConnectionProfiles($profiles) {
    return variable_set('cmrf_core_connection_profiles', $profiles);
  }

  public function getRegisteredConnectors() {
    return variable_get('cmrf_core_connectors');
  }

  protected function storeRegisteredConnectors($connectors) {
    return variable_set('cmrf_core_connectors', $connectors);
  }

  public function getSettings() {
    return variable_get('cmrf_core_settings');
  }

  protected function storeSettings($settings) {
    return variable_set('cmrf_core_settings', $settings);
  }

}
