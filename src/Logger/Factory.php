<?php

namespace Papaya\Module\Monolog\Logger {

  class Factory {

    const PROFILES = [
      Profile\StreamHandler::class => 'StreamHandler'
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
