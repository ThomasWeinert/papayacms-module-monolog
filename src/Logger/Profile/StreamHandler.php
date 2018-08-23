<?php

namespace Papaya\Module\Monolog\Logger\Profile {

  use Papaya\UI;

  class StreamHandler implements \Papaya\Module\Monolog\Logger\Profile  {

    use \Papaya\Module\Monolog\Logger\Options\Aggregation;

    private static $_DEFAULTS = [
      'stream' => ''
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
        new \Monolog\Handler\StreamHandler(
          $this->options()->get('stream', self::$_DEFAULTS['stream']),
          $logLevel
        )
      );
      return $logger;
    }

    public function createOptionsEditor(\Papaya\Module\Monolog\Logger\Options $options) {
      $editor = new \Papaya\Administration\Plugin\Editor\Dialog($options);
      $dialog = $editor->dialog();
      $dialog->fields[] = new UI\Dialog\Field\Input(
        new UI\Text\Translated('Stream'),
        'stream'
      );
      return $editor;
    }
  }
}
