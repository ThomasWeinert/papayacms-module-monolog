<?php
namespace Papaya\Module\Monolog\Logger {

  class Options extends \Papaya\Plugin\Editable\Data {

    /**
     * Checksum buffer
     *
     * @var string|NULL
     */
    private $_checksum;

    /**
     * @param array $values
     */
    public function __construct(array $values = []) {
      parent::__construct($values);
      $this->_checksum = $this->getChecksum();
    }

    /**
     * @param array $values
     */
    public function setValues(array $values = []) {
      parent::assign($values);
      $this->_checksum = $this->getChecksum();
    }

    /**
     * Check if the contained data was modified.
     *
     * @return boolean
     */
    public function modified() {
      return $this->_checksum !== $this->getChecksum();
    }
  }
}
