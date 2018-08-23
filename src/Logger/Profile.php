<?php

namespace Papaya\Module\Monolog\Logger {

  interface Profile {

    /**
     * @param string $name
     * @param string $logLevel
     * @return \Psr\Log\LoggerInterface
     */
    public function __invoke($name, $logLevel);

    /**
     * @param \Papaya\Module\Monolog\Logger\Options $options
     * @return \Papaya\Module\Monolog\Logger\Options
     */
    public function options(Options $options = NULL);
  }
}
