```
withPlugin 		(STR || AR) tt_content.list_type  Найдет все страницы, содержащие хотя бы один из указанных плагинов.
containsModule 	(STR || AR) pages.module  Найдет все страницы, на которых для параметра «Содержит подключаемый модуль» установлено одно из указанных значений.
withSwitchableControllerAction МswitchableControllerActionsзначений. Найдет все страницы, содержащие плагины с заданным настроенным действием.

[- ИНТЕРЕСНО ТЕМ, ЧТО ОН ОТДЕЛЬНУЮ СТРАНИЦУ СОЗДАЕТ - ]
https://www.marc-willmann.de/typo3-cms/ein-eigener-route-enhancer?tx_pwcomments_pi1%5Baction%5D=new&tx_pwcomments_pi1%5BcommentToReplyTo%5D=5&tx_pwcomments_pi1%5Bcontroller%5D=Comment&cHash=2975e539da596e4b4ee1d1511de60674

https://github.com/brannow/br-toolkit
https://github.com/SvenJuergens/miniredirect/

https://typo3.sascha-ende.de/docs/development/extensions-general/generate-a-link-with-an-extbase-method/


https://thecodingmachine.github.io/splash-router/
/**
 * @RoutePrefix("/example")
 * @Middleware("authMiddleware")
 */
class ExampleController
{
    /**
     * @Route("/hello", methods={"GET"}, name="example.hello")
     */
    public function hello(): ResponseInterface
    {
        ...
    }
}

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
			'/job/{:id}/',
			'/job/{:id}/slide/{:slide}/',
			'/test/job/{:id}/slide/{!slide}/',
			'/test/job/{:id}/',
			'/cat.13/model.277/page.4/',	
			'/{:id}/{:slide}/',
			'/{:custom}/',
			'/{:custom}/{:tt}/{:mm}/',
			'/{:custom}/test/',
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

routeEnhancers:
  BlogCategory:         #-> BlogPosts
    type: Extbase
    extension: Blog
    plugin: Category    #-> Posts
    routes:

    defaultController: 'Post::listPostsByDate'
      -
        routePath: '/archive/{year}'
        _controller: 'Post::listPostsByDate'
        _arguments:
          year: year
      -
        routePath: '/archive/{year}/page-{page}'
        _controller: 'Post::listPostsByDate'
        _arguments:
          year: year
          page: '@widget_0/currentPage'
      -
        routePath: '/archive/{year}/{month}'
        _controller: 'Post::listPostsByDate'
        _arguments:
          year: year
          month: month
      -
        routePath: '/archive/{year}/{month}/page-{page}'
        _controller: 'Post::listPostsByDate'
        _arguments:
          year: year
          month: month
          page: '@widget_0/currentPage'
    
    defaultController: 'Post::listPostsByAuthor'
      -
        routePath: '/author/{author_title}'
        _controller: 'Post::listPostsByAuthor'
        _arguments:
          author_title: author
      -
        routePath: '/author/{author_title}/page-{page}'
        _controller: 'Post::listPostsByAuthor'
        _arguments:
          author_title: author
          page: '@widget_0/currentPage'
    
    defaultController: 'Post::listPostsByCategory'
      -
        routePath: '/category/{category_title}'
        _controller: 'Post::listPostsByCategory'
      -
        routePath: '/category/{category_title}/page-{page}'
        _controller: 'Post::listPostsByCategory'
         -
         
    defaultController: 'Post::listRecentPosts'
        routePath: '/page-{page}'
        _controller: 'Post::listRecentPosts'
        _arguments:
    
    defaultController: 'Post::listPostsByTag'
       -
        routePath: '/tag/{tag_title}'
        _controller: 'Post::listPostsByTag'
        _arguments:
          tag_title: tag
      -
        routePath: '/tag/{tag_title}/page-{page}'
        _controller: 'Post::listPostsByTag'
        _arguments:
          tag_title: tag
          page: '@widget_0/currentPage'
          
routeEnhancers:
  PageTypeSuffix:
    type: PageType
    map:
      'blog.recent.xml': 200
      'blog.category.xml': 210
      'blog.tag.xml': 220
      'blog.archive.xml': 230
      'blog.comments.xml': 240
      'blog.author.xml': 250
```
