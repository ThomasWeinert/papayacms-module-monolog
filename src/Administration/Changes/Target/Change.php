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

  class Change
    extends UI\Control\Command\Dialog\Database\Record {

    private static $_LOG_LEVELS = [
        \Psr\Log\LogLevel::EMERGENCY,
        \Psr\Log\LogLevel::ALERT,
        \Psr\Log\LogLevel::CRITICAL,
        \Psr\Log\LogLevel::ERROR,
        \Psr\Log\LogLevel::WARNING,
        \Psr\Log\LogLevel::NOTICE,
        \Psr\Log\LogLevel::INFO,
        \Psr\Log\LogLevel::DEBUG
    ];

    public function createDialog() {
      $targetId = $this->parameters()->get('target_id', 0);
      $this->resetAfterSuccess(TRUE);
      $dialogCaption = 'Add log target';
      $buttonCaption = 'Add';
      if ($this->record()['id'] > 0) {
        $targetId = $this->record()['id'];
        $dialogCaption = 'Edit log target';
        $buttonCaption = 'Save';
      }
      $dialog = parent::createDialog();
      $dialog->parameterGroup($this->parameterGroup());
      $dialog->parameters($this->parameters());
      $dialog->hiddenFields()->merge(
        array(
          'cmd' => 'target_edit',
          'target_id' => $targetId
        )
      );
      $dialog->caption = new UI\Text\Translated($dialogCaption);
      $dialog->fields[] = $field = new UI\Dialog\Field\Input(
        new UI\Text\Translated('Title'), 'title', 200
      );
      $field->setMandatory(TRUE);
      $dialog->fields[] = $field = new UI\Dialog\Field\Input\Checkbox(
        new UI\Text\Translated('Active'), 'is_active', '', FALSE
      );
      $dialog->fields[] = $group = new UI\Dialog\Field\Group(new UI\Text\Translated('Logger'));
      $group->fields[] = $field = new UI\Dialog\Field\Input\Identifier(
        new UI\Text\Translated('Name'), 'name'
      );
      $field->setMandatory(TRUE);
      $group->fields[] = $field = new UI\Dialog\Field\Select(
        new UI\Text\Translated('Level'),
        'level',
        self::$_LOG_LEVELS,
        TRUE,
        UI\Dialog\Field\Select::VALUE_USE_CAPTION
      );
      $group->fields[] = $field = new UI\Dialog\Field\Select(
        new UI\Text\Translated('Handler'),
        'profile',
        \Papaya\Module\Monolog\Logger\Factory::PROFILES,
        TRUE
      );
      $dialog->buttons[] = new UI\Dialog\Button\Submit(
        new UI\Text\Translated($buttonCaption)
      );
      $this->callbacks()->onExecuteSuccessful = function() {
        $this->papaya()->messages->dispatch(
          new \Papaya\Message\Display\Translated(
            \Papaya\Message::SEVERITY_INFO,
            'Log target saved.'
          )
        );
      };
      $this->callbacks()->onExecuteFailed = function(
          /** @noinspection PhpUnusedParameterInspection */
        $context, UI\Dialog $dialog
      ) {
        $this->papaya()->messages->dispatch(
          new \Papaya\Message\Display\Translated(
            \Papaya\Message::SEVERITY_ERROR,
            'Invalid input. Please check the field(s) "%s".',
            array(implode(', ', $dialog->errors()->getSourceCaptions()))
          )
        );
      };
      return $dialog;
    }
  }
}
