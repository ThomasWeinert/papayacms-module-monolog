<?php

namespace Papaya\Module\Monolog\Logger\Profile {

  use Papaya\Module\Monolog\Logger;
  use Papaya\UI;

  class RotatingFileHandler implements Logger\Profile {

    use Logger\Options\Aggregation;

    private static $_DEFAULTS = [
      'filename' => '',
      'maximum_files' => 0
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
        new \Monolog\Handler\RotatingFileHandler(
          $this->options()->get('filename', self::$_DEFAULTS['filename']),
          $this->options()->get('maximum_files', self::$_DEFAULTS['maximum_files']),
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
        new UI\Text\Translated('filename'),
        'filename'
      );
      $dialog->fields[] = new UI\Dialog\Field\Input\Number(
        new UI\Text\Translated('Maximum files'),
        'maximum_files'
      );
      return $editor;
    }
  }
}
