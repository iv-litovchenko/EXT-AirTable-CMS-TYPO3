```
Просмотреть библиотеки и функции в репозиториях для Extbase...

https://viewhelpers.fluidtypo3.org/

https://www.youtube.com/c/IvanAbramenko/featured
https://www.youtube.com/watch?v=kIjepXxLjQM

https://akilli.github.io/ckeditor4-build-classic/demo/

<?php

{namespace v=FluidTYPO3\Vhs\ViewHelpers}

<f:if condition="{v:page.info(field: 'uid')} == '21'">
    <f:then>
        Shows only if page ID equals 21.
    </f:then>
</f:if>

	#Исходный текст
	#• $this->arguments is an associative array where you will find the values for all arguments you registered previously.
	#• $this->renderChildren() renders everything between the opening and closing tag of the view
	#helper and returns the rendered result (as string).
	#• $this->templateVariableContainer is an instance of TYPO3\Fluid\Core\ViewHelper\TemplateVariableContainer,
	#with which you have access to all variables currently available in the template, and can modify the variables
	#currently available in the template.

\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName() 
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath()
if (TYPO3_MODE === 'BE')

\TYPO3\CMS\Core\Configuration\ExtensionConfiguration
$temporaryDirectory = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class)
   ->get('my_extension_key', 'myVariable');
   
   \TYPO3\CMS\Core\Authentication\BackendUserAuthentication 
   \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication
   
$GLOBALS['TSFE']->sL(); // Зависит от языка выбранного пользователем на переключателе языков во Frontend.
$GLOBALS['LANG']->sL(); // Зависит от выбранного языка у пользователя в административной панели (и если Backend-пользователь авторизован).

// Debug
https://somethingphp.com/debugging-typo3/
\TYPO3\CMS\Core\Utility\DebugUtility::debug('VAR','HEADER','Debug');
\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump('VAR', 'FormObject:');

// Backend (CSS, JAVSCRIPT)
if (TYPO3_MODE === 'BE') {
   $renderer = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
   $renderer->addCssFile('EXT:my_ext_key/Resources/Public/Backend/Css/.css');
   $renderer->addJsFile('EXT:my_ext_key/Resources/Public/Backend/Js/.js', 'text/javascript');
}

Зарезервированные переменные

Данные переменные регистрируются автоматически при каждом создании (выполнении) объекта Smarty_Cobj и поставляются в шаблон – другие любые данные можно извлечь, к примеру, через 1) {mysql_exec - поддержка исключена}, 2) создание и подключение мини-контроллера, 3) разработку дополнительного самостоятельного плагина под Smarty, 4) указание рабочей области контроллера ({controllerload} {dataload}).

Примечание! Данные переменные всегда поставляются в шаблон (не зависимо от того, кэширован он или нет). Даже если у нас user_INT-плагин (условно говоря) – то данные переменные все равно будут доступны.

Постоянные переменные:

// Дата страницы 
{$t3_page.uid} – содержит данные о текущей странице (оставлено – и есть альтернатива {data source="page:title"}) {$t3_page.title}... {$t3_page.nav_title}... 

// Дата элемента контента 
{$t3_data.header} – содержит данные текущей выборки  
{$t3_data.bodytext|format:"lib.myParseFunc"} 

// Если вывод идет в режиме "eIdAjax" if (TYPO3_MODE_eIdAjax == 1) { return 1; } else { return 0; } 
{$t3_mode_eIdAjax} - 1 - да, 0 - нет (в основном нужно для создания <div>-оберток, хотя лучше это делать на основе jQuery  

Получить значения доступные во Fronend - аналог .data в TS

{data source="page:title"} // – получить название страницы     
{data source="DB:tt_content_gallery:1:title"} // – получиь из таблицы     
{data source="DB:TSFE:lang"} // – получить из масива 

TYPO3-константы

{env name="TYPO3_SITE_URL"} // Получить значение TYPO3-константы - (в примере обычно для используется для <base href="">)

##2. smarty.configLoad для Smarty (нужен ли он?);

	$("div.sc_UserComment_wrap").wrap( "<div class='sc_UserComment_wrap_Ajax' style='background: url(ajax-loader.gif) no-repeat;'></div>" );
	$('form#sc_UserComment').live('submit', function(){
		$("input#sc_UserComment_Submit").attr("disabled", true); // input submit
		$("div.sc_UserComment_wrap_Ajax").fadeTo( "fast" , 0.5 );
			$.ajax({  
				type: "POST",
				data: $("form#sc_UserComment").serializeArray(), // data: ({username : 123, password : 123}),
				url: window.location.href + ((window.location.href.indexOf('?') == -1) ? '?' : '&') + "eIdAjax=100", // что добавить "?" или "&"
				success: function(html){ 
                    $("div.sc_UserComment_wrap").replaceWith(html);   // wrapper form
					$("div.sc_UserComment_wrap_Ajax").stop(true,true).fadeTo( "fast" , 1 );
                }  
            });
		return false;
	}); 


use TYPO3\CMS\Fluid\View\StandaloneView;
        $templatePath = ExtensionManagementUtility::extPath('backend')
            . 'Resources/Private/Templates/DocumentTemplate/';
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename($templatePath . ($collapsible ? 'Collapse.html' : 'Tabs.html'));
        $view->setPartialRootPaths([$templatePath . 'Partials']);
        $view->assignMultiple([
            'id' => 'DTM-' . GeneralUtility::shortMD5($domId),
            'items' => $menuItems,
            'defaultTabIndex' => $defaultTabIndex,
            'wrapContent' => $wrapContent,
            'storeLastActiveTab' => $storeLastActiveTab,
        ]);

Mail

$email = GeneralUtility::makeInstance(MailMessage::class)
->to(new Address('kasperYYYY@typo3.org'), new Address('benni@typo3.org', 'Benni Mack'))
->subject('This is an example email')
->text('This is the plain-text variant')
->html('Hello Benni.
Enjoy a HTML-readable email. We love TYPO3.');

$email->send();

$GLOBALS['TYPO3_CONF_VARS']['MAIL']['templateRootPaths'][700] = 'EXT:my_site_extension/Resources/Private/Templates/Email';
$GLOBALS['TYPO3_CONF_VARS']['MAIL']['layoutRootPaths'][700] = 'EXT:my_site_extension/Resources/Private/Layouts';

$email
    ->to('contact@acme.com')
    ->from(new Address('jeremy@acme.com', 'Jeremy'))
    ->subject('TYPO3 loves you - here is why')
    ->setFormat('html') // only HTML mail
    ->setTemplate('TipsAndTricks')
    ->assign('mySecretIngredient', 'Tomato and TypoScript');
GeneralUtility::makeInstance(Mailer::class)->send($email);

<f:section name="Subject"> New Login at "{typo3.sitename}"</f:section>


<f:asset.css identifier="identifier123" href="EXT:my_ext/Resources/Public/Css/foo.css" />
<f:asset.css identifier="identifier123">
    .foo { color: black; }
</f:asset.css>

<f:asset.script identifier="identifier123" src="EXT:my_ext/Resources/Public/JavaScript/foo.js" />
<f:asset.script identifier="identifier123">
    alert('hello world');
</f:asset.script>

GeneralUtility::makeInstance(AssetCollector::class)
->addJavaScript('my_ext_foo', 'EXT:my_ext/Resources/Public/JavaScript/foo.js', ['data

$cacheManager = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class);
$cacheManager->flushCachesInGroupByTags('pages', [ 'pageId_'.$pid ]);

$cacheManager = $this->objectManager->get(\TYPO3\CMS\Extbase\Service\CacheService::class);
$cacheManager->clearPageCache([1,2,3]);

$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( \TYPO3\CMS\Extbase\Object\ObjectManager::class );
GeneralUtility::makeInstance(\TYPO3\CMS\Core\Cache\CacheManager::class)
    ->flushCachesInGroupByTags('pages', [ 'pageId_'.$id ]);
    
    
    $this->cacheService->clearPageCache([$pageIds]);

https://typo3.sascha-ende.de/docs/development/extensions-general/generate-a-link-with-an-extbase-method/
https://marketplace.visualstudio.com/items?itemName=ralffreit.typo3snippets
https://www.motions-media.de/2013/01/21/typo3-extbase-additionalheaderdata/
https://t3terminal.com/
https://github.com/sabbelasichon/typo3-rector/blob/master/docs/all_rectors_overview.md
https://docs.typo3.org/m/typo3/reference-typoscript/master/en-us/Index.html
https://docs.typo3.org/m/typo3/reference-coreapi/10.4/en-us/Index.html
https://docs.typo3.org/m/typo3/reference-coreapi/9.5/en-us/Index.html
https://docs.typo3.org/m/typo3/book-extbasefluid/9.5/en-us/Index.html


https://www.slideshare.net/FransSaris/typo3-create-a-ckeditor-plugin
https://www.slideshare.net/punktde/experiences-with-backend-user-rights-in-typo3
https://www.slideshare.net/pk77/get-happy-editors-with-a-suitable-typo3-backend-configuration
https://www.slideshare.net/nitsanindia/typo3-ckeditor-heaven-for-typo3-developer-editor

// Log message
$logMessage = 'Everything went fine.';
// Option extension key / module name
$extKey = 'my_extension';
// Error-level: 0 = message, 1 = error (user problem), 2 = System Error (which should not happen), 3 = security notice (admin)
$errorLevel = 0;
// Write sys_log using \TYPO3\CMS\Core\Utility\GeneralUtility::sysLog
$GLOBALS['BE_USER']->simplelog($logMessage, $extKey, $errorLevel);







https://typo3blogger.de/hook-contentpostproc-vs-cache/
https://marketplace.visualstudio.com/items?itemName=ralffreit.typo3snippets
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


magento
craftcms
craftcms.com clientbase.ru Laravel nova Orchid pyrocms instantcms clientbase ORCHARD evacms docs.laravel-enso.com/ fork-cms mv-framework.ru

[GLOBAL]
	fluidAjaxWidgetResponse = PAGE
	fluidAjaxWidgetResponse {
		typeNum = 7076
		headerData >
		config {
			no_cache = 1
			disableAllHeaderCode = 1
			debug = 0
		}
		10 = USER_INT
		10 {
			userFunc = TYPO3\CMS\Fluid\Core\Widget\Bootstrap->run
		}
	}
1511: 
--		>> ЧТО ЭТО?
1512: config.tx_extbase {
1513: 	mvc {
1514: 		requestHandlers {
1515: 			TYPO3\CMS\Fluid\Core\Widget\WidgetRequestHandler = TYPO3\CMS\Fluid\Core\Widget\WidgetRequestHandler
1516: 		}
1517: 	}
1518: }
1519: 

https://typo3.sascha-ende.de/docs/development/extensions-general/generate-a-link-with-an-extbase-method/
https://typo3.sascha-ende.de/docs/development/extensions-general/standalone-view-for-a-controller-action/
https://typo3.sascha-ende.de/docs/development/extensions-general/setup-routine-on-extension-installation-execute-script/
https://www.qbus.de/qblog/psr-15-middlewares-fuer-typo3-cms-v9/

		https://github.com/b13/uniquealiasmapper


ВИДОСИКи
ОБНОВЛЕНИЕ ТАЙПО3 до 10.4
Обзор FAL  (модуль список файлов) - почему нельзя сделать физ. Папки тоже на БД 
Доступы (замочки пользователя)  
Стандартные таблицы которые можно отредактировать через списки  
Маркеры 
Экспорт/импорт связей 
Выборка связей 
Работа с формами 


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

- вопрос кэширвоания
- вопрос роутера и тесты на:
Vorot pdf каталог
typo3.org экстеншены (фильтр)
kv-design фильтр

	&$vpCache = 0 && КЭШ ID && СБРОС КЭША (разрешение на сохранение вертиальной страниицы вроде cHash - типа идентификатор вирт. страницы)

	    Возможно как-то сделаем - в страницу пишем, что не разрешаем кэшировать и все и потом через strst проверяем данную метку
	
	

	// не обязательный параметры в маршруте
	// роутер для плагинов
	// как быть с параметрами которые слоожео проверить вроде page? из-за них можно завалить кэшами
	// 404 и данные не найдены
	// ссылки между блоками и плагинами и страницами.
	// breadcrumb убери _INT объекты со страницы и хлебки пропадут < ?=\Typo3Helpers::RunController('sitet3club\controllers\BlockContactController');? >
-------------------------------------------------------------

----------------

cache_check


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
	  
	  
	 
TypoScriptFrontendController->addCacheTags()










	

	
	
	
	$myRouteForPage = new Route(
'/test2/test3/test/{ttww}',
array('_controller' => 'MyController')

);

$pageCollection->add('default2', $myRouteForPage);
$fullCollection->addCollection($pageCollection);


Available Tokens

The following tokens are available to the URI setting:

    * – Any string of characters, except for a forward slash (/)
    day – Day of a month (1-31 or 01-31)
    month – Numeric representation of a month (1-12 or 01-12)
    number – Any positive integer
    page – Any positive integer
    uid – A v4 compatible UUID (universally unique ID)
    slug – Any string of characters, except for a forward slash (/)
    tag – Any string of characters, except for a forward slash (/)
    year – Four consecutive digits

#
Advanced Routing with URL Rules

In addition to routes, you can define URL rules

(opens new window) in config/routes.php.

return [
    // Route blog/archive/YYYY to a controller action
    'blog/archive/<year:\d{4}>' => 'controller/action/path',

    // Route blog/archive/YYYY to a template
    'blog/archive/<year:\d{4}>' => ['template' => 'blog/_archive'],
];

If your Craft installation has multiple sites, you can create site-specific URL rules by placing them in a sub-array, and set the key to the site’s handle.

return [
    'siteHandle' => [
        'blog/archive/<year:\d{4}>' => 'controller/action/path',
    ],
];




<?php
namespace eleshlenkinasite\controllers;
use app\components\special\Typo3Controller;
class FabricController extends Typo3Controller
{
	
	/*
	
Ajax<br />
КЭШ ID (разрешение на сохранение вертиальной страниицы вроде cHash - типа идентификатор вирт. страницы)<br />
Возможно как-то сделаем - в страницу пишем, что не разрешаем кэшировать и все и потом через strst проверяем данную метку

<s>Layout</s><br />

// 1 &$vpCache = 0
// 2 нексоклько маршрутов на 1 действие
// 3 http://site2.t3club.com/3d-modeli/fabric.24/cat.13/page.3/model.3194/
// 4 Смена действия толко для нужного контроллера, а не для всех.
// 5 BreadCrumb перестали работать!

	Vorot pdf каталог
typo3.org экстеншены (фильтр)
kv-design фильтр

	&$vpCache = 0 && КЭШ ID && СБРОС КЭША (разрешение на сохранение вертиальной страниицы вроде cHash - типа идентификатор вирт. страницы)

	    Возможно как-то сделаем - в страницу пишем, что не разрешаем кэшировать и все и потом через strst проверяем данную метку
	
	Layout


	// не обязательный параметры в маршруте
	// роутер для плагинов
	// как быть с параметрами которые слоожео проверить вроде page? из-за них можно завалить кэшами
	// 404 и данные не найдены
	// ссылки между блоками и плагинами и страницами.
	// breadcrumb убери _INT объекты со страницы и хлебки пропадут < ?=\Typo3Helpers::RunController('sitet3club\controllers\BlockContactController');? >
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
		
		$this->layout = 'EXT:eleshlenkinasite/views/layouts/Main.php';
		return $this->render('EXT:eleshlenkinasite/views/templates/page/FabricController.actionDefault.php',
			[
				'contentLink' => $contentLink
			]
		);
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
<?php
namespace sitet3club\controllers;
use Yii;
use app\components\special\Typo3Controller;
use sitet3club\models\Portfolio;

class MainController extends Typo3Controller
{
	// 1 &$vpCache = 0
	// 2 нексоклько маршрутов на 1 действие
	// 3 http://site2.t3club.com/3d-modeli/fabric.24/cat.13/page.3/model.3194/
	// 4 Смена действия толко для нужного контроллера, а не для всех.
	// 5 BreadCrumb перестали работать!
	
	// Настройка поведения действий
	public $TYPO3 = [
		'controllerType' => 'page',
		'disableAllHeaderCodeActions' => [], // Без секции <html><head>
		'nonСachedActions' => [], // Не кэшируемые экшены
		'eIdAjaxActions' => ['actionAjaxTest'], // Доступно как eIdAjax
		'urlManager' => [
			'actionJob' => '/job/{:job_id}/',
			'actionSlide' => '/job/{:job_id}/slide/{:slide_num}/',
			
			'actionSlide.r3' => '/job2/{:job_id}/page/{:page}/slide2/{:slide_num}/',
			'actionSlide.r4' => '/archive/{:m}/{:y}/', // [pagePath] [pagePost]
			'actionSlide.r5' => '/archive-full/m/{:m}/y/{!y}/', // [pagePath] [pagePost]
		]
	];
		
	// Регистрация страницы - нового типа
	public $TYPO3_title = 'Главная';
	public $TYPO3_fields = array(
		#'--div0--'=>false,
		#'pbase_tx_default___field_1' => ['CHECKBOX','Тест А'],
		#'pbase_tx_default___field_2' => ['CHECKBOX','Тест Б'],
		#'pbase_tx_default___field_3' => ['CHECKBOX','Тест В'],
		'backend_layout_config' => [
			[    1.4   ],
			[2.2,   3.2],
			[4, 5, 6, 7],
			[    8.4   ],
		]
	);
	
	// Показывать Google карты в блоке контактов
	public $displayGoogleMaps = true;
		
	public function actionDefault($data=[],$conf=[],$route=[],$args=[]) // , &$vpCache = 0
    {
#		print "<pre>";
#		print \Typo3Helpers::GetTsConstant('plugin.sitet3club.PID') . '<br />'; // TS-константы
#		print \Typo3Helpers::GetPageData('uid') . '<br />'; // данные записи страницы
#		print \Typo3Helpers::GetData('title') . '<br />'; // данные текущей записи
#		print_r(\Typo3Helpers::GetConf('controller')) . '<br />'; // переданные настройки для контроллера и действия
#		print_r(\Typo3Helpers::GetParams('sitet3club')) . '<br />'; // PHP-настройки 
#		print_r($GLOBALS['TSFE']->tmpl->flatSetup);
#		print_r($GLOBALS['TSFE']->tmpl->setup_constants);
#		print 1;
#		exit();
		
		$this->displayGoogleMaps = true;
		$this->layout = 'EXT:sitet3club/views/layouts/Main.php';
		return $this->render('EXT:sitet3club/views/templates/PageMainController/actionDefault.php', [
			'partialPortfolioWeb' => $this->partialPortfolio(0),
			'partialPortfolioDesign' => $this->partialPortfolio(1),
		]);
    }
	
	// type Web = 0
	// type Design = 1
	public function partialPortfolio($type = 1){
		$model = Portfolio::find()->where(['=','portfolio_type',$type])->orderBy(['sorting'=>SORT_DESC])->asArray()->all();
		return $this->renderPartial('EXT:sitet3club/views/templates/PageMainController/actionDefault_partialPortfolio.php', ['model'=>$model]);
	}
	
	// /job/{:job_id}/
	public function actionJob($job_id = 0) // , &$vpCache = 0
    {
		$model = Portfolio::find()->where('uid=:uid',[':uid' => $job_id])->asArray()->all();
		if(empty($model)){
			// \Typo3Helpers::AssetPage404AndExit('Работа №'.$job_id.' не существует!');
			return \Typo3Helpers::ErrorWrap('Работа №'.$job_id.' не существует!');
		}
		if($model[0]['nda'] == 1){
			// \Typo3Helpers::AssetPage404AndExit('Работа №'.$job_id.' закрыта для доступа - NDA!');
			return \Typo3Helpers::ErrorWrap('Работа №'.$job_id.' закрыта для доступа - NDA!');
		}
		
		\Typo3Helpers::AssetTitle('Работа №'.$job_id);
		\Typo3Helpers::AssetBreadcrumb('Работа №'.$job_id);
		
		$fileInfo = \Typo3Helpers::FileInfo('sitet3club\\models\\Portfolio','images',$job_id);
		$fileInfoVideo = \Typo3Helpers::FileInfo('sitet3club\\models\\Portfolio','video',$job_id);
	
		$this->layout = 'EXT:sitet3club/views/layouts/Main.php';
		return $this->render('EXT:sitet3club/views/templates/PageMainController/actionJob.php', [
			'model'=>$model[0],
			'fileInfo'=>$fileInfo,
			'fileInfoVideo'=>$fileInfoVideo
		]);
    }
	
	// /job/{:jobId}/slide/{:slide_num}/
	public function actionSlide($job_id = 0, $slide_num = 0)
    {
		$model = Portfolio::find()->where('uid=:uid',[':uid' => $job_id])->asArray()->all();
		if(empty($model)){
			// \Typo3Helpers::AssetPage404AndExit('Работа №'.$job_id.' не существует!');
			return \Typo3Helpers::ErrorWrap('Работа №'.$job_id.' не существует!');
		}
		if($model[0]['nda'] == 1){
			// \Typo3Helpers::AssetPage404AndExit('Работа №'.$job_id.' закрыта для доступа - NDA!');
			return \Typo3Helpers::ErrorWrap('Работа №'.$job_id.' закрыта для доступа - NDA!');
		}
		
		$urlJob = \Typo3Helpers::UrlManager('_self','actionJob',['job_id'=>$job_id]);
		\Typo3Helpers::AssetTitle('Работа №'.$job_id.' / Слайд №'.$slide_num);
		\Typo3Helpers::AssetBreadcrumb('Работа №'.$job_id, $urlJob);
		\Typo3Helpers::AssetBreadcrumb('Слай №'.$slide_num);
		
		$fileInfo = \Typo3Helpers::FileInfo('sitet3club\models\Portfolio','images',$job_id);
		$fileInfo = $fileInfo[$slide_num-1];
		if(count($fileInfo) == 0){
			#\Typo3Helpers::AssetPage404AndExit('Слайд №'.$slide_num.' не существует!');
		}
		
		$this->layout = 'EXT:sitet3club/views/layouts/Main.php';
		return $this->render('EXT:sitet3club/views/templates/PageMainController/actionSlide.php', [
			'model'=>$model[0],
			'fileInfo'=>$fileInfo
		]);
	}

	// /ajaxrun/sitet3club.controllers.MainController->actionAjaxTest/
	public function actionAjaxTest($testVar = 0)
    {
		return rand(1,10000).'|testVar:'.$testVar;
	}

}



<?php
namespace Litovchenko\AirTable\Routing;

use TYPO3\CMS\Core\Routing\Enhancer\PluginEnhancer;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Routing\Route;
use TYPO3\CMS\Core\Routing\RouteCollection;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 */
class CustomPage extends \TYPO3\CMS\Core\Routing\Enhancer\PluginEnhancer
{
    /**
     * @var array
     */
    protected $configuration;

    public function __construct(array $configuration)
    {
		parent::__construct($configuration);
        $this->configuration = $configuration;
    }
	
    /**
     * {@inheritdoc}
     */
    #public function buildResult(Route $route, array $results, array $remainingQueryParameters = []): PageArguments
    #{
        #$variableProcessor = $this->getVariableProcessor();
        // determine those parameters that have been processed
        #$parameters = array_intersect_key(
        #    $results,
        #    array_flip($route->compile()->getPathVariables())
        #);
        // strip of those that where not processed (internals like _route, etc.)
        #$internals = array_diff_key($results, $parameters);
        #$matchedVariableNames = array_keys($parameters);

        #$staticMappers = $route->filterAspects([StaticMappableAspectInterface::class], $matchedVariableNames);
        #$dynamicCandidates = array_diff_key($parameters, $staticMappers);

        // all route arguments
        #$routeArguments = $this->inflateParameters($parameters, $internals);
        // dynamic arguments, that don't have a static mapper
        #$dynamicArguments = $variableProcessor
        #    ->inflateNamespaceParameters($dynamicCandidates, $this->namespace);
        // static arguments, that don't appear in dynamic arguments
        #$staticArguments = ArrayUtility::arrayDiffAssocRecursive($routeArguments, $dynamicArguments);

        #$page = $route->getOption('_page');
        #$pageId = $page['uid'];
        #$type = 0;
		#$routeArguments = [1=>124];
		#$staticArguments = [2=>32];
		
        #return new PageArguments($pageId, $type, $routeArguments, $staticArguments, $remainingQueryParameters);
    #}
	
    /**
     * {@inheritdoc}
     */
    public function enhanceForMatching2(RouteCollection $collection): void
    {
        $myRouteForPage = new Route(
                'test2/test3/test/{ttww}',
                array('_controller' => 'MyController')
		);
        $collection->add('default2', $myRouteForPage);
    }
	
/**
     * {@inheritdoc}
     */
    public function enhanceForGeneration2(RouteCollection $collection, array $parameters): void
    {
        $myRouteForPage = new Route(
                'test2/test3/test/{ttww}',
                array('_controller' => 'MyController')
		);
        $collection->add('default2', $myRouteForPage);
    }
}


<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

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

class T3Router{
	
	protected $routes;
	protected $currentRoute;
	
	public function addRoute($router) {
		$this->routes[] = $router;
	}

	public function isFound(){
		if($this->currentRoute()!=FALSE){
			return TRUE;
		}
		return FALSE;
	}
	
	public function currentRequest(){
		$rUrl = $GLOBALS['_SERVER']['REQUEST_URI'];
		$rUrl = ltrim($rUrl, '/');
		$rUrl = rtrim($rUrl, '/');
		return $rUrl;
	}
	
	public function currentRoute(){
		$request = $this->currentRequest();
		$request_segment = explode('/', $request);
		#if($request_segment[0]==''){
		#	return TRUE;
		#}
		foreach($this->routes as $k => $v){
			$route = ltrim($v, '/');
			$route = rtrim($route, '/');
			$route_segment = explode('/',$route);
			$match = 0;
			$skip = 0; // не обязательный параметр
			$j = 0;
			for($i = 0; $i<count($route_segment); $i++){
				if(strstr($route_segment[$i],'{!') AND strstr($route_segment[$i],'}')){ // {?}
					if(@isset($request_segment[$j]) && $request_segment[$j] > 0){
						if(isset($request_segment[$j])){
							$match++;
							$j++;
						}
					} else {
						$match+=2; // всегда идет с предназванием переменной /slide/{?slide}/
						$skip+=2; // всегда идет с предназванием переменной /slide/{?slide}/
					}
				}elseif(strstr($route_segment[$i],'{:') AND strstr($route_segment[$i],'}')){ // {:any}
					if(@isset($request_segment[$j])){
						if($request_segment[$j] > 0){
							$match++;
							$j++;
						}
					}
				}elseif(@$request_segment[$j] == $route_segment[$i]){
					if(isset($request_segment[$j])){
						$match++;
						$j++;
					}
				}
				// } else {
				//	$match = 0;
				// }
			}
			if($match == count($route_segment) && $match >= 1 && count($request_segment)+$skip == count($route_segment)){
				// print 'Соответствует - ' . $route.'!';
				// $this->currentRoute = "{$k}";
				return "route-{$k}";
			}
		}
		return FALSE;
	}
}

?> <pre><?

$router = new T3Router;

foreach($a as $k => $v){
	$router->addRoute($v);
	$title = htmlentities($v);
	$href = preg_replace('/{([^}].*?)}/',100,$v);
	if($router->currentRoute() == 'route-'.$k){
		print "<a href='".$href."' style='color: red;'>".$k."|".$title."</a><br />";
	} else {
		print "<a href='".$href."'>".$k."|".$title."</a><br />";	
	}
}

if($router->isFound()){
	print '<div style="background: green; color: white;">';
	print 'Ok : текущий маршрут #'.$router->currentRoute();
	print '</div>';
} else {
	print '<div style="background: red; color: white;">';
	print '404';
	print '</div>';
}
?>

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


<?php
namespace Litovchenko\AirTable\Routing;

use TYPO3\CMS\Core\Routing\Enhancer\PluginEnhancer;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Routing\Route;
use TYPO3\CMS\Core\Routing\RouteCollection;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 */
class CustomPage extends \TYPO3\CMS\Core\Routing\Enhancer\PluginEnhancer
{
    /**
     * @var array
     */
    protected $configuration;

    public function __construct(array $configuration)
    {
		parent::__construct($configuration);
        $this->configuration = $configuration;
    }
	
    /**
     * {@inheritdoc}
     */
    #public function buildResult(Route $route, array $results, array $remainingQueryParameters = []): PageArguments
    #{
        #$variableProcessor = $this->getVariableProcessor();
        // determine those parameters that have been processed
        #$parameters = array_intersect_key(
        #    $results,
        #    array_flip($route->compile()->getPathVariables())
        #);
        // strip of those that where not processed (internals like _route, etc.)
        #$internals = array_diff_key($results, $parameters);
        #$matchedVariableNames = array_keys($parameters);

        #$staticMappers = $route->filterAspects([StaticMappableAspectInterface::class], $matchedVariableNames);
        #$dynamicCandidates = array_diff_key($parameters, $staticMappers);

        // all route arguments
        #$routeArguments = $this->inflateParameters($parameters, $internals);
        // dynamic arguments, that don't have a static mapper
        #$dynamicArguments = $variableProcessor
        #    ->inflateNamespaceParameters($dynamicCandidates, $this->namespace);
        // static arguments, that don't appear in dynamic arguments
        #$staticArguments = ArrayUtility::arrayDiffAssocRecursive($routeArguments, $dynamicArguments);

        #$page = $route->getOption('_page');
        #$pageId = $page['uid'];
        #$type = 0;
		#$routeArguments = [1=>124];
		#$staticArguments = [2=>32];
		
        #return new PageArguments($pageId, $type, $routeArguments, $staticArguments, $remainingQueryParameters);
    #}
	
    /**
     * {@inheritdoc}
     */
    public function enhanceForMatching2(RouteCollection $collection): void
    {
        $myRouteForPage = new Route(
                'test2/test3/test/{ttww}',
                array('_controller' => 'MyController')
		);
        $collection->add('default2', $myRouteForPage);
    }
	
/**
     * {@inheritdoc}
     */
    public function enhanceForGeneration2(RouteCollection $collection, array $parameters): void
    {
        $myRouteForPage = new Route(
                'test2/test3/test/{ttww}',
                array('_controller' => 'MyController')
		);
        $collection->add('default2', $myRouteForPage);
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
	wrap | Создать обертку для тэга (только для блочных элементов)
	allowedAttribs	| Разрешенные атрибуты для тэга
	disallowAttribs	| Запрещенные атрибуты для тэга
	fixAttrib | Точечная настрока значений атрибутов тэга
		set (=value) - принудительная установка значения атрибута
		default (=value) - значение по умолчанию, если пусто
		fixed (=value) - фиксированное значение (добавляется всегда в начало)
		list (=value1, value2 , value3) - разрешенные значений
		unset (=1) - удалить атрибут
