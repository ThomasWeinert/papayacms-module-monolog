<?php

namespace Papaya\Module\Monolog\Logger {

  class Factory {

    const PROFILES = [
      // Log to files and syslog
      Profile\StreamHandler::class => 'StreamHandler',
      Profile\RotatingFileHandler::class => 'RotatingFileHandler',
      Profile\SyslogHandler::class => 'SyslogHandler',
      Profile\ErrorLogHandler::class => 'ErrorLogHandler',
      Profile\ProcessHandler::class => 'ProcessHandler',
      // Send alerts and emails
      Profile\SlackWebhookHandler::class => 'SlackWebhookHandler',
      // Log specific servers and networked logging
      Profile\SocketHandler::class => 'SocketHandler',
      Profile\SyslogUdpHandler::class => 'SyslogUdpHandler'
    ];

    /**
     * @param string $profileClass
     * @param string $name
     * @param string $logLevel
     * @return \Psr\Log\LoggerInterface
     */
    public function createLogger($profileClass, $name, $logLevel) {
      $profile =$this->createProfile($profileClass);
      return $profile($name, $logLevel);
    }

    /**
     * @param $profileClass
     * @return Profile
     */
    public function createProfile($profileClass) {
      if (isset(self::PROFILES[$profileClass])) {
        return new $profileClass();
      }
      throw new \InvalidArgumentException(
        sprintf(
          'Unknown/unregistered profile class: %s',
          $profileClass
        )
      );
    }
  }
}
