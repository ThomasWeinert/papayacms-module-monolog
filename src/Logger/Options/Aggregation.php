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

namespace Papaya\Module\Monolog\Logger\Options {

  trait Aggregation {

    /**
     * @var \Papaya\Plugin\Editable\Content
     */
    private $_options;

    /**
     * The content is an {@see ArrayObject} child class containing the stored data.
     *
     * @see \Papaya\Plugin\Adaptable::options()
     * @param \Papaya\Module\Monolog\Logger\Options $options
     * @return \Papaya\Module\Monolog\Logger\Options
     */
    public function options(\Papaya\Module\Monolog\Logger\Options $options = NULL) {
      if (NULL !== $options) {
        $this->_options = $options;
      } elseif (NULL === $this->_options) {
        $this->_options = new \Papaya\Module\Monolog\Logger\Options();
        $this->_options->callbacks()->onCreateEditor = function (
          $context, \Papaya\Module\Monolog\Logger\Options $options
        ) {
          return $this->createOptionsEditor($options);
        };
      }
      return $this->_options;
    }

    /**
     * @param \Papaya\Module\Monolog\Logger\Options $options
     * @return \Papaya\Plugin\Editor
     */
    abstract public function createOptionsEditor(\Papaya\Module\Monolog\Logger\Options $options);
  }
}
