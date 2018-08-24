<?php

namespace Papaya\Module\Monolog\Logger\Profile {

  use Papaya\Module\Monolog\Logger;
  use Papaya\UI;

  class SocketHandler implements Logger\Profile {

    use Logger\Options\Aggregation;

    private static $_DEFAULTS = [
      'socket' => '',
      'persistent' => FALSE
    ];

    /**
     * @param string $name
     * @param string $logLevel
     * @return \Monolog\Logger|\Psr\Log\LoggerInterface
     * @throws \Exception
     */
    public function __invoke($name, $logLevel) {
      $logger = new \Monolog\Logger('name');
      $handler = new \Monolog\Handler\SocketHandler(
        $this->options()->get('socket', self::$_DEFAULTS['socket']),
        $logLevel
      );
      if ($this->options()->get('persistent', self::$_DEFAULTS['persistent'])) {
        $handler->setPersistent(TRUE);
      }
      $logger->pushHandler($handler);
      return $logger;
    }

    /**
     * @param Logger\Options $options
     * @return \Papaya\Administration\Plugin\Editor\Dialog
     */
    public function createOptionsEditor(Logger\Options $options) {
      $editor = new \Papaya\Administration\Plugin\Editor\Dialog($options);
      $dialog = $editor->dialog();
      $dialog->fields[] = $field = new UI\Dialog\Field\Input(
        new UI\Text\Translated('Socket'),
        'socket'
      );
      $field->setHint(
        new UI\Text\Translated(
          'Examples: %s, %s, %s',
          [
            'unix:///var/log/httpd_app_log.socket',
            'udp://127.0.0.1:21',
            'tcp://[fe80::1]:80'
          ]
        )
      );
      $dialog->fields[] = new UI\Dialog\Field\Input\Checkbox(
        new UI\Text\Translated('Persistent'), 'persistent', self::$_DEFAULTS['persistent']
      );
      return $editor;
    }
  }
}
