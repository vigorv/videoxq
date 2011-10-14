<?php

class Report extends AppModel
{
	var $name = 'Report';
	var $useTable = false;
	var $validate = array(
                'name_from' => array(
                    'rule' => '/^([a-z0-9а-яё\-\s\)\:)]{3,})$/ui',
                    'required' => true,
                    'allowEmpty' => false,
                    'minLength' => 6,
                    'maxLength' => 100,
                    'message' => 'Только буквы, цифры, подчеркивания и пробелы (мин 3)'
                    ),
		'email_from' => array(
                    'rule' => 'email',
                    'required' => false,
                    'allowEmpty' => true,
                    'message' => 'введите коректный email адрес'

                    ),
		'message' => array(
                    'rule' => '/^([a-z0-9а-яё\/\"\'\_\-\s\\,\.(\)\:)]{8,})$/ui',
                    'required' => true,
                    'allowEmpty' => false,
                    'minLength' => 8,
                    'minLength' => 500,
                    'message' => 'Только буквы, цифры, подчеркивания и пробелы (мин 8)'
                    ),
                'captcha' => array(
                    'rule' => array('comparecaptcha', 'captcha'),
                    'message' => 'Неверный код подтверждения'),
                'captcha2' => array(
                    'rule' => 'alphanumeric', 'required' => true)



/***********
 * 2й вариант для каптчи (1of2)

                 'captcha' => array(
                    'rule' => 'checkCaptcha',
                    'required' => true,
                    'allowEmpty' => false,
                    'message' => 'Неверный код подтверждения.'
                    )


 **********/



	);


function comparecaptcha($data) {
    $valid = false;
    if ($data['captcha'] == $this->data['Report']['captcha2']){
        $valid = true;
    }
    return $valid;
}

/***********
 * 2й вариант для каптчи (2of2)
 function checkCaptcha()
    {

        if ($this->data['Report']['captcha'] == $this->data['Report']['captcha2'])
            return true;
        return false;
    }

 ***********/

}


?>