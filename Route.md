```
https://github.com/SvenJuergens/miniredirect/
https://extensions.typo3.org/extension/language_router/
https://extensions.typo3.org/extension/routing/
https://extensions.typo3.org/extension/my_configurable_routes/
https://extensions.typo3.org/extension/routes/
https://extensions.typo3.org/extension/cc_routing/
https://extensions.typo3.org/extension/sl_cycleroutes/
https://extensions.typo3.org/extension/sg_routes/
https://extensions.typo3.org/extension/st_routeplanner/
https://extensions.typo3.org/extension/naviki_routing_request/
https://extensions.typo3.org/extension/extbase_realurl/
https://extensions.typo3.org/extension/slug_extbase/



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
