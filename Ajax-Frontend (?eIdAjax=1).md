# FormRequest - почему все от этого класса наследуются? https://www.itsolutionstuff.com/post/laravel-form-validation-request-class-exampleexample.html
https://www.slideshare.net/pritamkumbhar/process-validation-of-capsules\
https://codereview.stackexchange.com/questions/249068/improvements-on-laravels-base-model-and-formrequest\
https://medium.com/@elishaukpongson/one-laravel-form-request-class-multiple-http-methods-961740b7f630\

```

if(TYPO3_AJAX_MODE === true) {
}

###_GET
1

###_POST
1

1) Заполнение формы данными
2) Показ ошибок
4) if (посмотреть проверки VHS) isAjax...

https://www.yiiframework.com/doc/guide/2.0/en/input-validation\
https://laravel.com/docs/5.1/validation#available-validation-rules\
https://laravel.demiart.ru/ways-of-laravel-validation/

--------------------------------------------------------------------------------------------------------------------
Другие хелперы
--------------------------------------------------------------------------------------------------------------------
	\Typo3Helpers::IsEditMode();
	\Typo3Helpers::IsAjaxMode();
	
	/**
		Работа по сценарию Ajax
	**/
	static function IsAjaxMode()
    {
		$eIdAjax = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('eIdAjax');
		if(!empty($eIdAjax)){
			return true;
		}else{
			return false;
		}
	}
	
<?php
namespace sitet3club\models;

use Yii;
use yii\base\Model;

class ContactForm extends Model
{
    public $username;
    public $telephone;
	public $bodytext;
	// public $subject;
    // public $policy;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
			['username','required','message'=>''],
			['username','string','max'=>100],
			
			['telephone','required','message'=>''],
			['telephone','string','max'=>100],
			
			// ['subject','required','message'=>''],
			['bodytext','required','message'=>''],
            ['bodytext','string','max'=>350],
			
			// ['policy','required','message'=>''],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        //return [
        //    'verifyCode' => 'Verification Code',
        //];
    }
    
    /**
     * Sends email
     */
    public function sendEmail()
    {
        if ($this->validate()) {
			\app\components\special\Typo3SwiftMailer::sendText(
				'i-litovan@yandex.ru',
				'На сайте по разработке сайтов поступила заявка',
				'EXT:sitet3club/mail/Template1.php',
				[
					'username'=>$this->username,
					'telephone'=>$this->telephone,
					'bodytext'=>$this->bodytext
				]
			);
            return true;
        }
        return false;
    }
}
```
