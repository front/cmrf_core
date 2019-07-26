<?php

namespace Drupal\cmrf_webform;

use Drupal\cmrf_webform\OptionSetInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\cmrf_core\Entity\CMRFConnector;
use RuntimeException;

class CMRFManager {

  use StringTranslationTrait;

  protected $core;

  public function __construct($core, $translation) {
    $this->core = $core;
    $this->stringTranslation = $translation;
  }

  protected function sendApiRequest($connector, $api_entity, $api_action, $parameters, $options) {
    $call = $this->core->createCall($connector, $api_entity, $api_action, $parameters, $options);
    $this->core->executeCall($call);

    if ($call->getStatus() == get_class($call)::STATUS_DONE) {
      $reply = $call->getReply();

      if (!empty($reply['is_error'])) {
        throw new RuntimeException('CMRF API call returned error');
      }
      if (!isset($reply['values']) || !is_array($reply['values'])) {
        throw new RuntimeException('Malformed CMRF API call response');
      }

      return $reply['values'];
    }
    else {
      throw new RuntimeException("CMRF Api call was unsuccessful ($api_entity/$api_action) - " . $call->getStatus());
    }
  }
}
