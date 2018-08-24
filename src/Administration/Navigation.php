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
  use Papaya\UI;

  class Navigation extends \Papaya\Administration\Page\Part {

    /**
     * @var UI\Listview
     */
    private $_listview;
    /**
     * @var Content\Targets
     */
    private $_targets;

    /**
     * Append navigation to parent xml element
     *
     * @param \Papaya\XML\Element $parent
     */
    public function appendTo(\Papaya\XML\Element $parent) {
      $parent->append($this->listview());
      $targetId = $this->parameters()->get('target_id', 0);
      $this->toolbar()->elements[] = $button = new UI\Toolbar\Button();
      $button->caption = new UI\Text\Translated('Add target');
      $button->image = 'actions-generic-add';
      $button->reference()->setParameters(
        array(
          'cmd' => 'target_edit',
          'target_id' => 0
        ),
        $this->parameterGroup()
      );
      if (0 < $targetId) {
        $this->toolbar()->elements[] = $button = new UI\Toolbar\Button();
        $button->caption = new UI\Text\Translated('Delete target');
        $button->image = 'actions-generic-delete';
        $button->reference()->setParameters(
          array(
            'cmd' => 'target_delete',
            'target_id' => $targetId
          ),
          $this->parameterGroup()
        );
      }
    }

    /**
     * @param UI\Listview $listview
     * @return UI\Listview
     */
    public function listview(UI\Listview $listview = NULL) {
      if (NULL !== $listview) {
        $this->_listview = $listview;
      } elseif (NULL === $this->_listview) {
        $this->_listview = new UI\Listview();
        $this->_listview->caption = new UI\Text\Translated('Log Targets');
        $this->_listview->builder(
          $builder = new UI\Listview\Items\Builder($this->targets())
        );
        $builder->callbacks()->onCreateItem = function(
          /** @noinspection PhpUnusedParameterInspection */
          $context, UI\Listview\Items $items, $record
        ) {
           $items[] = $item = new UI\Listview\Item(
             $record['is_active'] ? 'status-sign-ok' : 'status-sign-off',
             $record['title'],
             ['target_id' => $record['id']]
           );
           $item->selected = (int)$record['id'] === $this->parameters()->get('target_id', 0);
        };
        $this->_listview->parameterGroup($this->parameterGroup());
        $this->_listview->parameters($this->parameters());
      }
      return $this->_listview;
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
        $this->_targets->papaya($this->papaya());
        $this->_targets->activateLazyLoad();
      }
      return $this->_targets;
    }
  }
}
