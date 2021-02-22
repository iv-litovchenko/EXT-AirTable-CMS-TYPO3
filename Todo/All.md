```
##2. smarty.configLoad для Smarty (нужен ли он?);

	

https://www.marc-willmann.de/typo3-cms/ein-eigener-route-enhancer?tx_pwcomments_pi1%5Baction%5D=new&tx_pwcomments_pi1%5BcommentToReplyTo%5D=5&tx_pwcomments_pi1%5Bcontroller%5D=Comment&cHash=2975e539da596e4b4ee1d1511de60674
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Prefix('my-prefix')]
class MyController
{
    #[Get('my-get-route')]
	 #[Get('my-get-route')]
    public function myGetMethod()
    {
    }

    #[Post('my-post-route')]
    public function myPostMethod()
    {
    }
}




	/*
	$a = [
	'/',
	'/profile/',
	'/profile/edit/pay/{:payId}/',
	'/profile/edit/{:id}/',
	'/profile/update/{:id}/',
	'/resetpassword/',
	'/registration/',
	'/exit/',
	
	'/extension/{:ext}/',
	'/test/',
	'/archive/rr/{:test}/',
	'/archive/{:month}/{:year}/',
	'/archive/{:month}/{:year}/sss/{:test}/',
	
	'/cat/{:catId}/page/{!page}/', // actionCat
	'/fabric/{:fabricId}/cat/{!catId}/page/{!page}/', // actionFabric
	'/fabric/{!fabricId}/cat/{!catId}/page/{!page}/model/{:modelId}/', // actionModel
	
	'/cat.13/model.277/page.4/',
	
	'/job/{:id}/',
	'/job/{:id}/slide/{:slide}/',
	'/test/job/{:id}/slide/{!slide}/',
	'/test/job/{:id}/',
	
	'/{:id}/{:slide}/',
	'/{:custom}/',
	'/{:custom}/{:tt}/{:mm}/',
	'/{:custom}/test/',
];
*/



 routeEnhancers:
  Aimeos:
    type: Extbase
    namespace: ai
    defaultController: 'Catalog::list'
    routes:
      - { routePath: '/pin/{pin_action}/{pin_id}/{d_name}', _controller: 'Catalog::detail' }
      - { routePath: '/history/{his_action}/{his_id}', _controller: 'Account::history' }
      - { routePath: '/watch/{wat_action}', _controller: 'Account::watch' }
      - { routePath: '/watch/{wat_action}/{wat_id}', _controller: 'Account::watch' }
      - { routePath: '/fav/{fav_action}', _controller: 'Account::favorite' }
      - { routePath: '/fav/{fav_action}/{fav_id}', _controller: 'Account::favorite' }
      - { routePath: '/b/{b_action}', _controller: 'Basket::index' }
      - { routePath: '/co/{c_step}', _controller: 'Checkout::index' }
      - { routePath: '/c/{f_name}~{f_catid}', _controller: 'Catalog::list' }
      - { routePath: '/d/{d_name}', _controller: 'Catalog::detail' }
      - { routePath: '/l/{f_sort}', _controller: 'Catalog::list' }
    defaults:
      b_action: ''
      c_step: ''
      f_sort: ''



<?php
namespace eleshlenkinasite\controllers;
use app\components\special\Typo3Controller;
class FabricController extends Typo3Controller
{
	
	/*



	*/
	
	
	// Настройка поведения действий контроллера
	public $TYPO3 = [
		'controllerType' => 'page',
		'disableAllHeaderCodeActions' => [], // Без секции <html><head> (только для страниц)
		'nonСachedActions' => [], // Не кэшируемые экшены
		'eIdAjaxActions' => [], // Доступно как eIdAjax
		'realurlActions' => [], // Действия в которых генерировать ссылки через EXT:realurl,
		
		// NEW
		'layoutAllowed' => 'Main,Main2', // ???
		'urlManager' => [ // segmentname || {:varName} || {:varName:tableName}
			'actionDefault.r1' => '/extension/{:ext}/',
			'actionDefault.r2' => '/test/',
			'actionDefault.r3' => '/archive/rr/{:year}/',
			'actionDefault.r4' => '/archive/{:month}/{:year}/',
			'actionDefault.r5' => '/archive/{:month}/{:year}/sss/{:test}/',
			'actionDefault.r6' => '/fabric/{:fabricId}/',
			'actionDefault.r1' => '/profile/',
			'actionDefault.r2' => '/profile/edit/pay/{:payId}/', // payId
			'actionDefault.r3' => '/profile/edit/{:id}/',
			'actionDefault.r4' => '/profile/update/{:id}/',
			'actionDefault.r5' => '/resetpassword/',
			'actionDefault.r6' => '/registration/',
			'actionDefault.r7' => '/exit/',
			'actionDefault.r1' => '/cat/{:catId}/page/{!page}/', // actionCat
			'actionDefault.r2' => '/fabric/{:fabricId}/cat/{!catId}/page/{!page}/', // actionFabric
			'actionDefault.r3' => '/fabric/{!fabricId}/cat/{!catId}/page/{!page}/model/{:modelId}/', // actionModel
		]
	];
	
		
	public function actionDefault()
	{
		$vars = [
			'ext'=>'air_table',
			'month'=>06,
			'year'=>2019,
			//'test'=>'mytest',
			'fabricId'=>100,
		];
		$contentLink = '';
		foreach($this->TYPO3['urlManager'] as $rName => $rUrl){
			$link = \Typo3Helpers::UrlManager('_self', $rName, $vars);
			$contentLink .= "<a href='".$link."'>".$link.'</a><br />';
		}
		
	}
	
	//public $actionCat = '/cat/{:catId}/page/{!page}/';
	public function actionCat($catId = 0, $page = 0)
	{
		return 'actionCat';
	}
	
	//public $actionFabric = '/fabric/{:fabricId}/cat/{!catId}/page/{!page}/';
	public function actionFabric($fabricId = 0, $catId = 0, $page = 0)
	{
		return 'actionFabric';
	}
	
	//public $actionModel = '/fabric/{!fabricId}/cat/{!catId}/page/{!page}/model/{:modelId}/';
	public function actionModel($fabricId = 0, $catId = 0, $page = 0, $modelId = 0)
	{
		return 'actionModel';
	}

}

Проверка конфлика маршрутов


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
Другие хелперы
--------------------------------------------------------------------------------------------------------------------

	\Typo3Helpers::IsEditMode();
	\Typo3Helpers::IsAjaxMode();
	
##########################################################################################################################
# Если это специальная страница - eIdAjax - кэширование страницы выключается
# Пример "page.html?eIdAjax=controller/action"
# См. "class.tx_yii2_configArrayPostProc.php"
##########################################################################################################################
	
[globalVar = GP:eIdAjax = 1]
					
	config {
		no_cache 					= 1
		disableAllHeaderCode 		= 1
		disablePrefixComment 		= 1
	}
					
[global]



	
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
	/**
		Генератор ссылки (EIdAjax)
	*/
	static function UriAjax($parameter = false, $controller = 'self', $action = false, $additionalParams = [])
	{
		$additionalParamsDefault = [];
		
		// page id
		if ($parameter == "self") {
			$additionalParamsDefault['id'] = $GLOBALS['TSFE']->id;
		}elseif($parameter != false){
			$additionalParamsDefault['id'] = $parameter;
		}
		
		// controller
		if($controller == "self"){
			$additionalParamsDefault['controller'] = Typo3Helpers::String2Controller(Yii::$app->params['typo3']['conf']['controller']);
		}elseif(!empty($controller) != false){
			$additionalParamsDefault['controller'] = $controller;
		}
		
		// action
		if($action != false){
			$additionalParamsDefault['action'] = $action;
		}
		
		// merge
		$additionalParams = $additionalParamsDefault+$additionalParams;
		
		$additionalParamsResult = '';
		if (count($additionalParams) > 0) {
			foreach ($additionalParams as $key => $value) {
				if ($value != null) {
					$additionalParamsResult .= "&" . $key . "=" . rawurlencode($value);
				}
			}
		}

		return '?eIdAjax=1'.$additionalParamsResult;
	}


--------------------------------------------------------------------------------------------------------------------
Запуск страницы eIdAjax
--------------------------------------------------------------------------------------------------------------------

	http://site-name.ru/ajaxrun/sitet3club.controllers.MainController->actionAjaxTest/
	?eIdAjax=1&controller=MyTestController&action=actionForm&param1=100&param2=***
	
	// Настройка поведения действий
	public $TYPO3 = [
		'disableAllHeaderCodeActions' =&gt; [], // Без секции &lt;html&gt;&lt;head&gt;
		'nonСachedActions' =&gt; ['*'], // Не кэшируемые экшены
		'eIdAjaxActions' =&gt; ['actionDefault'], // Доступно как eIdAjax
		'realurlActions' =&gt; ['actionDefault'] // Действия в которых генерировать ссылки через EXT:realurl
	];
	
	
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




--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------------------------------------

// TCEFORM.tx_examples_haiku.reference_page.PAGE_TSCONFIG_STR = image
 'foreign_table_where' => "AND pages.title LIKE '%###PAGE_TSCONFIG_STR###%'",
 
 'related_records' => [
            'label' => 'LLL:EXT:examples/Resources/Private/Language/locallang_db.xlf:tx_examples_haiku.related_records',
            'config' => [
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'pages, tt_content',
                'size' => 5,
                'minitems' => 0,
                'maxitems' => 10,
                'suggestOptions' => [
                    'default' => [
                        'searchWholePhrase' => 1,
                    ],
                    'pages' => [
                        'searchCondition' => 'doktype = 1',
                    ],
                ],
            ],
        ],


???

https://t3terminal.com/blog/typo3-community/
https://t3terminal.com/blog/typo3-blogger/
https://t3terminal.com/blog/free-typo3-support/
https://t3terminal.com/blog/earn-typo3/
https://docs.typo3.org/typo3cms/extensions/formz/stable/02-Usage/Index.html
https://docs.typo3.org/m/typo3/reference-coreapi/10.4/en-us/ApiOverview/Routing/AdvancedRoutingConfiguration.html
https://docs.typo3.org/m/typo3/reference-coreapi/10.4/en-us/ApiOverview/RequestHandling/Index.html
https://docs.typo3.org/m/typo3/book-extbasefluid/9.5/en-us/10-Outlook/6-dispatching.html
https://t3terminal.com/blog/typo3-site-configuration/
https://t3terminal.com/blog/typo3-routing/
https://typo3.sascha-ende.de/docs/development/extensions-general/generate-a-link-with-an-extbase-method/
https://typo3.sascha-ende.de/docs/development/extensions-general/standalone-view-for-a-controller-action/
https://typo3.sascha-ende.de/docs/development/extensions-general/setup-routine-on-extension-installation-execute-script/
https://www.youtube.com/c/IvanAbramenko/featured
https://www.youtube.com/watch?v=kIjepXxLjQM


---
https://laravel.demiart.ru/ways-of-laravel-validation/
	$validator = \Illuminate\Support\Facades\Validator::make(
    array(
        'name' => 'Dayle',
        'password' => 'lamepassword',
        'email' => 'email@example.com'
    ),
    array(
        'name' => 'required',
        'password' => 'required|min:8',
        'email' => 'required|email|unique:users'
    )
);
	
		// return \Illuminate\Support\Facades\Validator::make($values,$rules);
		$custom_validation_messages = array(
		  'password.min' => "Password can not be less than 6 characters.",
		  'password.required' => "Password is required"
		);

		   $validator = \Illuminate\Support\Facades\Validator::make($request, [
			'email'          => ['required','unique:users,email','email','max:255'],
		   'email'          => ['required','email','max:255'],
		   'password'       => 'required|min:6|confirmed'

		 ],$custom_validation_messages);
		 
		         if ($validator->fails()) {
		}
		


https://www.typo3.net/forum/thematik/zeige/thema/120062/
http://wikicode1111d.blogspot.com/2013/08/validation-create-and-validate.html
https://flowframework.readthedocs.io/en/stable/TheDefinitiveGuide/PartIII/Validation.html
