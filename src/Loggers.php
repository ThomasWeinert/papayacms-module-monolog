<?php

namespace Papaya\Module\Monolog {

  use Monolog\Logger;
  use Monolog\Handler\StreamHandler;

  class Loggers implements \Papaya\Plugin\LoggerFactory {

    /**
     * @var Content\Targets
     */
    private $_targets;

    /**
     * @return \Papaya\Message\Dispatcher
     * @throws \Exception
     */
    public function createLogger() {
      $result = new \Papaya\Message\Dispatcher\Collection();
      foreach ($this->targets() as $target) {
        $logger = new Logger($target['name']);
        $logger->pushHandler(
          new StreamHandler(
            \Papaya\Utility\Arrays::get($target['options'], 'stream', ''),
            $target['level']
          )
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
  }
}
