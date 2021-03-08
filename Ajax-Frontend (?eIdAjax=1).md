## https://codebeautify.org/php-beautifier


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
  * https://coderoad.ru/54051157/TYPO3-Extbase-%D0%BA%D0%B0%D0%BA-%D1%80%D0%B0%D0%B7%D0%BC%D0%B5%D1%81%D1%82%D0%B8%D1%82%D1%8C-%D0%B4%D0%B0%D0%BD%D0%BD%D1%8B%D0%B5-%D1%84%D0%BE%D1%80%D0%BC%D1%8B-%D0%BD%D0%B0-%D0%BA%D0%BE%D0%BD%D1%82%D1%80%D0%BE%D0%BB%D0%BB%D0%B5%D1%80%D0%B5
		#*********************************************************************************************
		# 1) Ошибки
		# Вывод ошибок на общей форме
		# Вывод ошибок под полем
		# css-class-error для полей
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




	
--------------------------------------------------------------------------------------------------------------------
Отправка писем, предварительно нужно настроить адрес с которого 
будет производится отправка писем в настройках расширения "yii2"
--------------------------------------------------------------------------------------------------------------------

	Настройки расширения "yii2":
	basic.swiftmailer_from (string) 
	T3Club|noreply@t3club.com

	\app\components\special\Typo3SwiftMailer::sendText(
		'i-litovan@yandex.ru',
		'На сайте по разработке сайтов поступила заявка',
		'EXT:sitet3club/mail/Template1.php',
		[
			'username'=&gt;$this-&gt;username,
			'telephone'=&gt;$this-&gt;telephone,
			'bodytext'=&gt;$this-&gt;bodytext
		]
	);



--------------------------------------------------------------------------------------------------------------------
Получение настроек и данных о странице (элементе содержимого)
--------------------------------------------------------------------------------------------------------------------

	\Typo3Helpers::GetTsConstant('plugin.sitet3club.PID'); // TS-константы (строка)
	\Typo3Helpers::GetPageData('uid'); // массив, или данные записи страницы (строка)
	\Typo3Helpers::GetData('title'); // массив или данные текущей записи (строка)
	\Typo3Helpers::GetConf(); // переданные настройки для текущего контроллера и действия (массив)
	\Typo3Helpers::GetExtParams('sitet3club'); // PHP-настройки расширения (массив)


	
--------------------------------------------------------------------------------------------------------------------
Описание функции парсинга атрибутов "postUserFunc=" для "lib.parseFunc_RTE"
--------------------------------------------------------------------------------------------------------------------
https://gist.github.com/ogrosko/5126ebe7249066b26007b46970327475
https://docs.typo3.org/m/typo3/reference-typoscript/master/en-us/Functions/HtmlparserTags.html


	wrap | Создать обертку для тэга (только для блочных элементов)
	allowedAttribs	| Разрешенные атрибуты для тэга
	disallowAttribs	| Запрещенные атрибуты для тэга
	fixAttrib | Точечная настрока значений атрибутов тэга
		set (=value) - принудительная установка значения атрибута
		default (=value) - значение по умолчанию, если пусто
		fixed (=value) - фиксированное значение (добавляется всегда в начало)
		list (=value1, value2 , value3) - разрешенные значений
		unset (=1) - удалить атрибут


https://t3terminal.com/blog/typo3-site-configuration/
https://t3terminal.com/blog/typo3-routing/
!!! https://typo3.sascha-ende.de/docs/development/extensions-general/generate-a-link-with-an-extbase-method/

Просмотреть все функции Extbase и расширений core в папке typo3/sysext/
https://kronova.net/tutorials/typo3/extbase-fluid/use-middlewares-with-multilanguage.html

config.tx_extbase {
	mvc {
		requestHandlers {
			TYPO3\CMS\Extbase\Mvc\Web\FrontendRequestHandler = TYPO3\CMS\Extbase\Mvc\Web\FrontendRequestHandler
			TYPO3\CMS\Extbase\Mvc\Web\BackendRequestHandler = TYPO3\CMS\Extbase\Mvc\Web\BackendRequestHandler
			TYPO3\CMS\Extbase\Mvc\Cli\RequestHandler = TYPO3\CMS\Extbase\Mvc\Cli\RequestHandler
		}
		throwPageNotFoundExceptionIfActionCantBeResolved = 0
	}


