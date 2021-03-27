### https://www.mittwald.de/fileadmin/pdf/dokus/Extbase_Fluid_Dokumentation.pdf
### https://various.at/news/typo3-9-custom-content-elements/
### https://various.at/news/dropzonejs-mit-typo3
### https://various.at/news/image-upload-mit-typo3
### https://various.at/news/typo3-data-processor
### https://various.at/news/typo3-tipps-und-tricks-psr-15-mideelware-am-beispiel-mailchimp-webhook


### AJAX PATH
```
var link = '/?eIdAjax=1&eIdAjaxPath=projiv|FeedBackFormController|indexAction';
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

$pageUid = $this->settings['myflexformsettingpart'];
$uriBuilder = $this->uriBuilder;
$uri = $uriBuilder
  ->setTargetPageUid($pageUid)
  ->build();
$this->redirectToURI($uri, $delay=0, $statusCode=303);
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


