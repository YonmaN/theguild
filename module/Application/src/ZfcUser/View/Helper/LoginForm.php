<?php
namespace ZfcUser\View\Helper;

use ZfcUser\Form\Login;

use Zend\View\Helper\AbstractHelper;

class LoginForm extends AbstractHelper {

	public function __invoke() {
		return new Login();
	}
}

