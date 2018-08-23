<?php

namespace Papaya\Module\Monolog\Content {

  class Targets extends \Papaya\Database\Records\Lazy {

    protected $_fields = [
      'id' => 'target_id',
      'is_active' => 'target_active',
      'title' => 'target_title',
      'name' => 'target_name',
      'level' => 'target_level',
      'profile' => 'target_profile',
      'options' => 'target_options'
    ];

    protected $_orderByProperties = [
      'title' => \Papaya\Database\Interfaces\Order::ASCENDING,
      'name' => \Papaya\Database\Interfaces\Order::ASCENDING,
      'id' => \Papaya\Database\Interfaces\Order::ASCENDING
    ];

    protected $_tableName = TABLES::TARGETS;

    public function _createMapping() {
      $mapping = parent::_createMapping();
      $mapping->callbacks()->onMapValueFromFieldToProperty = function (
        /** @noinspection PhpUnusedParameterInspection */
        $context, $property, $field, $value
      ) {
        switch ($property) {
        case 'options' :
          return \Papaya\Utility\Text\XML::unserializeArray($value);
        }
        return $value;
      };
      $mapping->callbacks()->onMapValueFromPropertyToField = function (
        /** @noinspection PhpUnusedParameterInspection */
        $context, $property, $field, $value
      ) {
        switch ($property) {
        case 'options' :
          return \Papaya\Utility\Text\XML::serializeArray(empty($value) ? array() : $value);
        }
        return $value;
      };
      return $mapping;
    }
  }
}
