<?php
/**
 * papaya CMS
 *
 * @copyright 2000-2018 by papayaCMS project - All rights reserved.
 * @link http://www.papaya-cms.com/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, version 2
 *
 *  You can redistribute and/or modify this script under the terms of the GNU General Public
 *  License (GPL) version 2, provided that the copyright and license notes, including these
 *  lines, remain unmodified. papaya is distributed in the hope that it will be useful, but
 *  WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 *  FOR A PARTICULAR PURPOSE.
 */

namespace Papaya\Module\Monolog\Administration {

  use Papaya\Module\Monolog\Content;
  use Papaya\Module\Monolog\Logger;
  use Papaya\UI;

  class Changes extends \Papaya\Administration\Page\Part {

    /**
     * @var Content\Target
     */
    private $_target;

    /**
     * @param string $name
     * @param string $default
     * @return UI\Control\Command\Controller
     */
    public function _createCommands($name = 'cmd', $default = 'target_edit') {
      $commands = parent::_createCommands($name, $default);
      $commands['target_edit'] = new UI\Control\Command\Collection(
        new Changes\Target\Change($this->target()),
        new Changes\Target\Toolbar($this->target())
      );
      $commands['target_delete'] = new Changes\Target\Remove($this->target());
      if ($this->allowHandlerConfiguration()) {
        $commands['target_configure'] = new UI\Control\Command\Collection(
          new Changes\Target\Configure($this->target()),
          new Changes\Target\Toolbar($this->target())
        );
      }
      return $commands;
    }

    private function allowHandlerConfiguration() {
      return
        (string)$this->target()->profile !== '' &&
        class_exists($this->target()->profile) &&
        class_implements($this->target()->profile, Logger\Profile::class);
    }

    /**
     * @param Content\Target|NULL $target
     * @return Content\Target
     */
    public function target(Content\Target $target = NULL) {
      if (NULL !== $target) {
        $this->_target = $target;
      } elseif (NULL === $this->_target) {
        $this->_target = new Content\Target;
        $this->_target->papaya($this->papaya());
        $this->_target->activateLazyLoad(
          ['id' => $this->parameters()->get('target_id', 0)]
        );
      }
      return $this->_target;
    }
  }
}
