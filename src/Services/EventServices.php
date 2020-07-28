<?php

/**
 * @file providing the service RdKafkaServices.
 *
 */

namespace Drupal\event_dispatcher\Services;

class EventServices {

  /**
   * @param $event_name
   * @param $event
   * @return bool
   */
  public function send($event_name, $event) {
    return TRUE;
  }
}
