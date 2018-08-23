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

  use Papaya\UI;

  class Toolbar extends UI\Control\Command\Action  {

    private $_toolbar;
    private $_record;


    public function __construct(\Papaya\Database\Interfaces\Record $record) {
      $this->_record = $record;
    }

    public function appendTo(\Papaya\XML\Element $parent) {
      $parent->append($this->toolbar());
      parent::appendTo($parent);
    }

    public function toolbar(UI\Toolbar $toolbar = NULL) {
      if (NULL !== $toolbar) {
        $this->_toolbar = $toolbar;
      } elseif (NULL === $this->_toolbar) {
        $this->_toolbar = $toolbar = new UI\Toolbar();
        if (($recordId = $this->_record['id']) > 0) {
          $toolbar->elements[] = $button = new UI\Toolbar\Button();
          $button->caption = new UI\Text\Translated('General');
          $button->reference->setParameters(
            [
              'cmd' => 'target_edit',
              'target_id' => $recordId
            ],
            $this->parameterGroup()
          );
          $button->selected = $this->parameters()->get('cmd', 'target_edit') === 'target_edit';
          $toolbar->elements[] = $button = new UI\Toolbar\Button();
          $button->caption = new UI\Text\Translated('Handler Options');
          $button->reference->setParameters(
            [
              'cmd' => 'target_configure',
              'target_id' => $recordId
            ],
            $this->parameterGroup()
          );
          $button->selected = $this->parameters()->get('cmd') === 'target_configure';
        }
      }
      return $this->_toolbar;
    }
  }
}
