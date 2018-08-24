<?php

namespace Papaya\Module\Monolog\Logger\Profile {

  use Papaya\Module\Monolog\Logger;
  use Papaya\UI;

  class ProcessHandler implements Logger\Profile {

    use Logger\Options\Aggregation;

    private static $_DEFAULTS = [
      'command' => ''
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
        new \Monolog\Handler\ProcessHandler(
          $this->options()->get('command', self::$_DEFAULTS['command']),
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
      $dialog->fields[] = new UI\Dialog\Field\Input(
        new UI\Text\Translated('Command'), 'command', 2000, self::$_DEFAULTS['command']
      );
      return $editor;
    }
  }
}
