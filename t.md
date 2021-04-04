```
1) Остановился на разработке каталога (Пагинация , Хлеб крошки)
2) Шорткоды передать
3) FAL + "_func" Отказаться от постфиксов "_func", сделать также алиас для uid_local_func as file
  * https://laravel.com/docs/8.x/eloquent-mutators 
  * protected function defineEntity(ClassDefinition $class) { $class->property($this->engine)->asObject(Engine::class); }


site_package/
site_package/Configuration
site_package/Configuration/TCA
site_package/Configuration/TCA/Overrides
site_package/Configuration/TCA/Overrides/sys_template.php
site_package/Configuration/TypoScript
site_package/Configuration/TypoScript/constants.typoscript
site_package/Configuration/TypoScript/setup.typoscript
site_package/ext_emconf.php
site_package/ext_icon.png
site_package/Resources
site_package/Resources/Private
site_package/Resources/Private/Layouts
site_package/Resources/Private/Layouts/Page
site_package/Resources/Private/Layouts/Page/Default.html
site_package/Resources/Private/Partials
site_package/Resources/Private/Partials/Page
site_package/Resources/Private/Partials/Page/Jumbotron.html
site_package/Resources/Private/Templates
site_package/Resources/Private/Templates/Page
site_package/Resources/Private/Templates/Page/Default.html
site_package/Resources/Public
site_package/Resources/Public/Css
site_package/Resources/Public/Css/website.css
site_package/Resources/Public/Images/
site_package/Resources/Public/Images/logo.png
site_package/Resources/Public/JavaScript
site_package/Resources/Public/JavaScript/website.js


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
