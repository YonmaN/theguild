<?php

namespace GuildUser\Form;

use Zend\Form\Form,
	ZfcUser\Form\Login as ZfcUserLoginForm,
    ZfcBase\Form\ProvidesEventsForm,
    ZfcUser\Module as ZfcUser;

class Login extends ZfcUserLoginForm
{
    public function init()
    {
    	parent::init();
    	$this->getElement('identity')->setLabel('דוא"ל');
    	$this->getElement('credential')->setLabel('סיסמא');
    	$this->getElement('login')->
    	setValue('הזדהה')->
    	setAttrib('id', 'loginbutton');
        
        $this->events()->trigger('init', $this);
    }
}
