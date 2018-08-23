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
  use Papaya\Message;

  class Remove
    extends UI\Control\Command\Dialog\Database\Record {

    /**
     * @param \Papaya\Database\Interfaces\Record $record
     */
    public function __construct(\Papaya\Database\Interfaces\Record $record) {
      parent::__construct($record, self::ACTION_DELETE);
    }

    /**
     * @see \Papaya\UI\Control\Command\Dialog::createDialog()
     * @return \Papaya\UI\Dialog
     */
    public function createDialog() {
      $targetId = $this->record()['id'];
      $loaded = $targetId > 0;
      $dialog = new UI\Dialog\Database\Delete($this->record());
      $dialog->papaya($this->papaya());
      $dialog->caption = new UI\Text\Translated('Delete');
      if ($loaded) {
        $dialog->parameterGroup($this->parameterGroup());
        $dialog->parameters($this->parameters());
        $dialog->hiddenFields()->merge(
          array(
            'cmd' => 'target_delete',
            'target_id' => $targetId
          )
        );
        $dialog->fields[] = new UI\Dialog\Field\Information(
          new UI\Text\Translated('Delete log target: %s', [$this->record()['title']]),
          'places-trash'
        );
        $dialog->buttons[] = new UI\Dialog\Button\Submit(new UI\Text\Translated('Delete'));
        $this->callbacks()->onExecuteSuccessful = array($this, 'callbackDeleted');
      } else {
        $dialog->fields[] = new UI\Dialog\Field\Message(
          Message::SEVERITY_INFO, 'Log target not found.'
        );
      }
      return $dialog;
    }

    /**
     * Show success message
     */
    public function callbackDeleted() {
      $this->papaya()->messages->dispatch(
        new Message\Display\Translated(
          Message::SEVERITY_INFO,
          'Log target deleted.'
        )
      );
    }
  }
}
