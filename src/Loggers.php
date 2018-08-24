<?php

namespace Papaya\Module\Monolog {

  class Loggers implements \Papaya\Plugin\LoggerFactory {

    /**
     * @var Content\Targets
     */
    private $_targets;
    /**
     * @var Logger\Factory
     */
    private $_factory;

    /**
     * @return \Papaya\Message\Dispatcher
     * @throws \Exception
     */
    public function createLogger() {
      $result = new \Papaya\Message\Dispatcher\Collection();
      foreach ($this->targets() as $target) {
        $logger = $this->loggerFactory()->createLogger(
          $target['profile'], $target['name'], $target['level'], $target['options']
        );
        $result->add(new \Papaya\Message\Dispatcher\PSR3($logger));
      }
      return $result;
    }


    /**
     * @param Content\Targets|NULL $targets
     * @return Content\Targets
     */
    public function targets(Content\Targets $targets = NULL) {
      if (NULL !== $targets) {
        $this->_targets = $targets;
      } elseif (NULL === $this->_targets) {
        $this->_targets = new Content\Targets;
        $this->_targets->activateLazyLoad(['is_active' => TRUE]);
      }
      return $this->_targets;
    }

    /**
     * @param Logger\Factory $factory
     * @return Logger\Factory
     */
    public function loggerFactory(Logger\Factory $factory = NULL) {
      if (NULL !== $factory) {
        $this->_factory = $factory;
      } elseif (NULL === $this->_factory) {
        $this->_factory = new Logger\Factory();
      }
      return $this->_factory;
    }
  }
}
