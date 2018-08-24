<?php

namespace Papaya\Module\Monolog\Logger\Profile {

  use Papaya\Module\Monolog\Logger;
  use Papaya\UI;

  class ErrorLogHandler implements Logger\Profile {

    use Logger\Options\Aggregation;

    private static $_DEFAULTS = [
      'message_type' => \Monolog\Handler\ErrorLogHandler::OPERATING_SYSTEM
    ];

    private static $_MESSAGE_TYPES = [
      \Monolog\Handler\ErrorLogHandler::OPERATING_SYSTEM => 'operating system',
      \Monolog\Handler\ErrorLogHandler::SAPI => 'sapi'
    ];

    /**
     * @param string $name
     * @param string $logLevel
     * @return \Monolog\Logger|\Psr\Log\LoggerInterface
     * @throws \Exception
     */
    public function __invoke($name, $logLevel) {
      $logger = new \Monolog\Logger('name');
      $logger->pushHandler(
        new \Monolog\Handler\ErrorLogHandler(
          $this->options()->get('message_type', self::$_DEFAULTS['message_type']),
          $logLevel
        )
      );
      return $logger;
    }

    /**
     * @param Logger\Options $options
     * @return \Papaya\Administration\Plugin\Editor\Dialog
     */
    public function createOptionsEditor(Logger\Options $options) {
      $editor = new \Papaya\Administration\Plugin\Editor\Dialog($options);
      $dialog = $editor->dialog();
      $dialog->fields[] = new UI\Dialog\Field\Select(
        new UI\Text\Translated('message_type'),
        'message_type',
        self::$_MESSAGE_TYPES
      );
      return $editor;
    }
  }
}
