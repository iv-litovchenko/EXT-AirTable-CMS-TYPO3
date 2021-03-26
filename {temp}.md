### AJAX PATH
```
var link = '/?eIdAjax=1&eIdAjaxPath=projiv|FeedBackFormController|indexAction';
```


### HOOKS (для залипания) - может в контроллер кодключить хук?
```
$GLOBALS['TYPO3_CONF_VARS' ]['SC_OPTIONS']['tslib/class.tslib_fe.php']['isOutputting'][] = 'tx_cachecontrolheader_controller->processDirective';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['usePageCache']
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['insertPageIncache']
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['get_cache_timeout']
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['determineId-PreProcessing']
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['determineId-PostProc'] 
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['pageLoadedFromCache']

/* Collect menu tags when rendering */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/tslib/class.tslib_menu.php']['filterMenuPages'][] =  \Qbus\Autoflush\Hooks\Frontend\RegisterMenuTags::class;


```

### Extbase Generate a link with an extbase method
```
$pageid = intval($GLOBALS['TSFE']->id);
$uri = urldecode($this->uriBuilder->reset()->setTargetPageUid($pageid)->setCreateAbsoluteUri(true)->setArguments([
    'tx_vsnearbycompanies_nearbycompanies' => [
        'action' => 'ajax',
        'controller' => 'Companies'
    ],
    'detail' => 2,
    'type'  => 1335898899
])->build());

$cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\ContentObject\\ContentObjectRenderer');

$conf = array(
    'parameter' => $id, // Page UID
    'useCashHash' => false,
    'returnLast' => 'url',
'forceAbsoluteUrl'  =>  true
);
$link = $cObj->typolink_URL($conf); 
```

## Задокументироват

```
<?php
namespace Litovchenko\Projiv\Controller\PagesElements\Elements;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class ElementSubPagesController extends ActionController 
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'FrontendContentElement',
		'name' 			=> 'Дочерние страницы (красивый список)',
		'description' 	=> '',
		'dataFields' 	=> [
            'attr_field1' => [
                'type' => 'Input',
                'name' => 'Field Input 1',
				'items' => [ // Только для Enum, Switcher
					'S' => '(37-38)',
					'L' => 'L (41-42)',
					'XL' => 'XL (43-44)',
					'XXL' => 'XXL (45-46)',
					'XXXL' => '(47-48)'
				]
            ],
            'attr_field2' => [
                'type' => 'Input',
                'name' => 'Field Input 2'
            ],
            'attr_field3' => [
                'type' => 'Input',
                'name' => 'Field Input 3'
            ]
		]
	];
	
    public function indexAction()
    {
    }

}
```

```
class PageDefaultController extends ActionController
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'FrontendPage',
		'name' 			=> 'Шаблон по умолчанию',
		'description' 	=> 'Шаблон по умолчанию',
		'urlManager'	=> [
			'travelViewAction' => [
				'/travels/{num}',
				'/travels/dat/',
				
			]
			'/travels' => 'travels',				
												// -> array $form = [] тоже работает!!! public function indexAction(array $form = [] TADA!!!)
			'/travels/{num}' => 'travelView',	// -> не забудь задокументировать public function travelViewAction(float $num TADA!!! = 0) 
		],
		'dataFields' 	=> [
            'attr_pic' => [
                'type' => 'Input',
                'name' => 'Фоновое изображение!'
            ]
		]
	];
