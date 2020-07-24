<?php

namespace Drupal\event_dispatcher\EventDispatcher;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher;

class EventDispatcher extends ContainerAwareEventDispatcher {

  /**
   * EventDispatcher constructor.
   * @param ContainerInterface $container
   * @param array $listeners
   */
  public function __construct(ContainerInterface $container, array $listeners = []) {
    parent::__construct($container, $listeners);
  }

  /**
   * @param string $event_name
   * @param Event|NULL $event
   * @return Event|void|null
   */
  public function dispatch($event_name, Event $event = NULL) {
    parent::dispatch($event_name, $event);
    $route_name = \Drupal::routeMatch()->getRouteName();
    if ($route_name != 'event_dispatcher.kafka_configuration_form') {
      $kafka_config = \Drupal::service('event_dispatcher.kafka_factory_service');
      if ($kafka_config->getKafkaConfigStatus()) {
        $kafka_config->produce($event_name);
      }
    }
    // \Drupal::logger('custom_event')->notice("I am being called - $event_name");
     //dvm($event_name);
    // //dvm($event);
  }
}
