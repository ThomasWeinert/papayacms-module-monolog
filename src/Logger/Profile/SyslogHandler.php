<?php

namespace Papaya\Module\Monolog\Logger\Profile {

  use Papaya\Module\Monolog\Logger;
  use Papaya\UI;

  class SyslogHandler implements Logger\Profile {

    use Logger\Options\Aggregation;

    private static $_DEFAULTS = [
      'identifier' => 'papaya',
      'facility' => LOG_USER
    ];

    private static $_FACILITIES = [
      LOG_USER => 'user-level messages',
      LOG_LOCAL0 => 'local0',
      LOG_LOCAL1 => 'local1',
      LOG_LOCAL2 => 'local2',
      LOG_LOCAL3 => 'local3',
      LOG_LOCAL4 => 'local4',
      LOG_LOCAL5 => 'local5',
      LOG_LOCAL6 => 'local6',
      LOG_LOCAL7 => 'local7'
    ];

    /**
     * @param string $name
     * @param string $logLevel
     * @return \Monolog\Logger|\Psr\Log\LoggerInterface
     * @throws \Exception
     */
    public function __invoke($name, $logLevel) {
      $logger = new \Monolog\Logger($name);
      $logger->pushHandler(
        new \Monolog\Handler\SyslogHandler(
          $this->options()->get('identifier', self::$_DEFAULTS['identifier']),
          $this->options()->get('facility', self::$_DEFAULTS['facility']),
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
      $dialog->fields[] = new UI\Dialog\Field\Input\Identifier(
        new UI\Text\Translated('Identifier'), 'identifier', self::$_DEFAULTS['identifier']
      );
      $dialog->fields[] = new UI\Dialog\Field\Select(
        new UI\Text\Translated('Facility'),
        'facility',
        self::$_FACILITIES
      );
      return $editor;
    }
  }
}
