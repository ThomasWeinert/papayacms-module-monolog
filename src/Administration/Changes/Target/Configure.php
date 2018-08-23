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

namespace Papaya\Module\Monolog\Administration\Changes\Target {

  use Papaya\Module\Monolog\Logger;
  use Papaya\UI;

  class Configure
    extends UI\Control\Command {

    /**
     * @var Logger\Factory
     */
    private $_factory;

    /**
     * @var \Papaya\Module\Monolog\Content\Target
     */
    private $_target;


    public function __construct(\Papaya\Module\Monolog\Content\Target $target) {
      $this->_target = $target;
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

    public function appendTo(\Papaya\XML\Element $parent) {
      $profile = $this->loggerFactory()->createProfile($this->_target->profile);
      $options = $profile->options();
      $options->setValues($this->_target['options']);
      $editor = $options->editor();
      $editor->context()->merge(
        [
          $this->parameterGroup() =>
          [
            'cmd' => 'target_configure',
            'target_id' => $this->_target['id']
          ]
        ]
      );
      $parent->append($editor);
      if ($options->modified()) {
        $this->_target->options = $options->getArrayCopy();
        if ($this->_target->save()) {
          $this->papaya()->messages->display(
            \Papaya\Message::SEVERITY_INFO,
            new UI\Text\Translated('Options saved.')
          );
        }
      }
      return $parent;
    }
  }
}
