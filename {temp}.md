```
		'cols'			=> [
			0 => '*',
			1 => 'text,image',
			2 => 'text,image',
			'--br1--' => 1,
			3 => 'text,image',
			4 => 'text,image',
			5 => 'text,image',
			'--br2--' => 1,
			6 => 'text,image',
			7 => 'text,image'
		],
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
		$filter = [];
		$filter['orderBy'] = ['sorting']; 
		$filter['where'] = ['pid','=',$GLOBALS['TSFE']->page['uid']];
		$filter['with'] = 'media';
		$rows = \Litovchenko\AirTable\Domain\Model\Content\Pages::recSelect('get',$filter);
		$this->view->assign('rows', $rows);
		
		// Если проекты в стр-ве / студ. совете
		if ($GLOBALS['TSFE']->id == 10 || $GLOBALS['TSFE']->id == 166){
			$this->view->assign('cssWidth', 50);
			$this->view->assign('imgWidth', 400);
			$this->view->assign('imgHeight', 200); // 192
		
		// Если "Open Source проекты (2)"
		} elseif ($GLOBALS['TSFE']->id == 177){
			$this->view->assign('cssWidth', 100);
			$this->view->assign('imgWidth', 840);
			$this->view->assign('imgHeight', 0); // 192
		
		} else {
			$this->view->assign('cssWidth', 25);
			$this->view->assign('imgWidth', 200);
			$this->view->assign('imgHeight', 200);
		}
    }

}
```
