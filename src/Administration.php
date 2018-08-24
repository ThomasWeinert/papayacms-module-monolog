<?php

namespace Papaya\Module\Monolog {

  class Administration extends \Papaya\Administration\Page {

    const PERMISSION_MANAGE = 1;

    public $permissions = array(
      self::PERMISSION_MANAGE => 'Manage',
    );

    protected
      /** @noinspection ClassOverridesFieldOfSuperClassInspection */
      $_parameterGroup = 'log';

    public function __construct(\Papaya\Template $layout) {
      parent::__construct($layout, '36847363280ebb067f2874e8b1d6e37d');
    }

    protected function createContent() {
      return new Administration\Changes();
    }

    protected function createNavigation() {
      return new Administration\Navigation();
    }

    public function validateAccess() {
      return $this->papaya()->administrationUser->hasPerm(
        self::PERMISSION_MANAGE, $this->getModuleId()
      );
    }
  }
}
