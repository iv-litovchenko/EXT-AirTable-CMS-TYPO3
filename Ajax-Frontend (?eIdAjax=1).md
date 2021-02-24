## Ajax-Frontend (http://your-seite.com/?eIdAjax=1)

### eIdAjax _GET

```js script
$(function() {

    //*****************************************************************//
    // _GET Ajax (обновить фотографию)
    // <f:uri.action
    //		noCacheHash="true" 
    //		additionalParams="{eIdAjax:1,eIdAjaxPath:'projiv|RandPhotoController|index'}"
    // >
    //*****************************************************************//
    $('body').on('click', '#ext_projiv_randphotocontroller_a', function() {
        $('#ext_projiv_randphotocontroller_wrap').fadeTo("fast", 0.5);
        $.ajax({
            type: 'GET',
            url: "/?eIdAjax=1&eIdAjaxPath=projiv|RandPhotoController|index", //  EXT:projiv | Classes/Controllers/... | indexAction()
            data: {
                eIdAjaxSettings: {
                    imgWidthBig: 640,
                    imgWidthSmall: 300
                }
            },
            success: function(html) {
                $('#ext_projiv_randphotocontroller_wrap').replaceWith(html);
            }
        });
        return false;
    });

});
```

### eIdAjax _POST

```js script
$(function() {

    //*****************************************************************//
    // _POST Ajax (форма оставить сообщение)
    // <f:form 
    //		name="FeedBackForm" 
    //		object="{FeedBackForm}" 
    //		noCacheHash="true" 
    //		additionalParams="{eIdAjax:1,eIdAjaxPath:'projiv|FeedBackFormController|index',settings:{}}"
    // >
    // <f:form.hidden name="eIdAjaxSettings[imgWidthBig]" value="640" />
    // <f:form.hidden name="eIdAjaxSettings[imgWidthSmall]" value="300" />
    //*****************************************************************//
    $('body').on('submit', 'form#ext_projiv_feedbackformcontroller', function() {
        $(this).find(':submit').attr("disabled", true); // input submit
        $.ajax({
            type: 'POST',
            url: "/?eIdAjax=1&eIdAjaxPath=projiv|FeedBackFormController|index", //  EXT:projiv | Classes/Controllers/... | indexAction()
            data: $(this).serializeArray(),
            success: function(html) {
                $('#ext_projiv_feedbackformcontroller_wrap').replaceWith(html);
            }
        });
        return false;
    });

});
```






```

		#*********************************************************************************************
		# 1) Ошибки
		#*********************************************************************************************
		# 2) Form
		#*********************************************************************************************
		# Валидация формы база
		# Валидация формы в контроллере есть модель Form
		# Валидация формы в контроллере без модели Form
		#*********************************************************************************************
		# 3) FAL
		#*********************************************************************************************


if(TYPO3_AJAX_MODE === true) {
}


4) if (посмотреть проверки VHS) isAjax...
5) Убрать лишнуюю функцию @validationDataWithRules@

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
