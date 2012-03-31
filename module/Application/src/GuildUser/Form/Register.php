<?php

namespace GuildUser\Form;

use ZfcUser\Form\Register as RegisterBase;

use Zend\Form\Form,
    ZfcUser\Module,
    Zend\Form\Element\Captcha as Captcha;

class Register extends RegisterBase
{
	public function initLate() {
		parent::initLate();
		$this->getElement('display_name')->setLabel('שם תצוגה');
		$this->getElement('email')->setLabel('כתובת דוא"ל');
		$this->getElement('captcha')->setLabel('קפצ\'ה');
		$this->getElement('password')->setLabel('סיסמא');
		$this->getElement('passwordVerify')->setLabel('סיסמא עוד פעם');
	}
}
