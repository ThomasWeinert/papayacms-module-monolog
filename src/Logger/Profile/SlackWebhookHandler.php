<?php

namespace Papaya\Module\Monolog\Logger\Profile {

  use Papaya\Module\Monolog\Logger;
  use Papaya\UI;

  class SlackWebhookHandler implements Logger\Profile {

    use Logger\Options\Aggregation;

    private static $_DEFAULTS = [
      'webhook_url' => '',
      'channel' => '',
      'username' => '',
      'emoji' => NULL,
      'use_attachment' => TRUE,
      'use_short_attachment' => FALSE,
      'include_context_and_extra ' => FALSE
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
        new \Monolog\Handler\SlackWebhookHandler(
          $this->options()->get('webhook_url', self::$_DEFAULTS['webhook_url']),
          $this->options()->get('channel', self::$_DEFAULTS['channel']),
          $this->options()->get('username', self::$_DEFAULTS['username']),
          $this->options()->get('use_attachment', self::$_DEFAULTS['use_attachment']),
          $this->options()->get('emoji', self::$_DEFAULTS['emoji']) ?: NULL,
          $this->options()->get('use_short_attachment', self::$_DEFAULTS['use_short_attachment']),
          $this->options()->get('include_context_and_extra', self::$_DEFAULTS['include_context_and_extra']),
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
      $dialog->fields[] = new UI\Dialog\Field\Input\URL(
        new UI\Text\Translated('Webhook URL'), 'webhook_url', self::$_DEFAULTS['webhook_url']
      );
      $dialog->fields[] = new UI\Dialog\Field\Input(
        new UI\Text\Translated('Channel'), 'channel', 40, self::$_DEFAULTS['channel']
      );
      $dialog->fields[] = new UI\Dialog\Field\Input(
        new UI\Text\Translated('Emoji'), 'emoji', 60, self::$_DEFAULTS['emoji']
      );
      $dialog->fields[] = new UI\Dialog\Field\Input\Checkbox(
        new UI\Text\Translated('Use attachment'), 'use_attachment', self::$_DEFAULTS['use_attachment']
      );
      $dialog->fields[] = new UI\Dialog\Field\Input\Checkbox(
        new UI\Text\Translated('Use short attachment'), 'use_short_attachment', self::$_DEFAULTS['use_short_attachment']
      );
      $dialog->fields[] = new UI\Dialog\Field\Input\Checkbox(
        new UI\Text\Translated('Include content and extra'), 'include_context_and_extra', self::$_DEFAULTS['include_context_and_extra']
      );
      return $editor;
    }
  }
}
