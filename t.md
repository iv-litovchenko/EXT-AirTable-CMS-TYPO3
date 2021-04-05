```
1) Остановился на разработке каталога (Пагинация , Хлеб крошки)
2) Секции HeaderAssets FooterAssets + посмотреть как там отправляются Assents
3) Шорткоды передать
4) Для страницы #253 не задан шаблон
5) FAL + "_func" Отказаться от постфиксов "_func", сделать также алиас для uid_local_func as file
  * https://laravel.com/docs/8.x/eloquent-mutators 
  * protected function defineEntity(ClassDefinition $class) { $class->property($this->engine)->asObject(Engine::class); }



-----------------
<f:section name="HeaderAssets">
  <title>Super title from fluid</title>
  fewfefewfewfew
  fewfefewfewfewefew
</f:section>


<f:render partial="Page.Header" arguments="{_all}" />
	
	<f:vhsExtAirTable.content colPos="0" />

<f:render partial="Page.Footer" arguments="{_all}" />


<f:section name="FooterAssets">
  <title>Super title from fluid</title>
  fewfefewfewfew
  fewfefewfewfewefew
</f:section>

------------------


Зарегистрируйте  flux как плагин контента.

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'FluidTYPO3.Flux',
    'Content',
    [
        'Content' => 'render, error',
    ]
);

Добавьте TS для нового CTYPE

tt_content.flux_2columns = USER
tt_content.flux_2columns {
    userFunc = TYPO3\CMS\Extbase\Core\Bootstrap->run
    vendorName = FluidTYPO3
    extensionName = Flux
    pluginName = Content
}

 


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

		<flux:field.controllerActions name="controllerActions" label="controllerActions"   
		extensionName="extensionName"
		controllerExtensionName="controllerExtensionName" 
		pluginName="pluginName" controllerName="controllerName" actions="{foo: 'bar'}" />
		< flux:field.custom name="$1" label="$2"${4: default="$3"}${5: required="1"}${6: requestUpdate="1"}${7: clear="1"}${9: userFunc="${8:FluidTYPO3\\Flux\\UserFunction\\HtmlOutput->renderField}"} />
		< flux:field.userFunc name="$1" label="$2"${5: extensionName="$4"} userFunc="$6" />
			
		<flux:field.DateTime name="DateTime" label="DateTime"  required="1" clear="1" />
		<flux:field.text name="text" label="text"  required="1" clear="1" />
		<flux:field.none name="none" label="none"  required="1" clear="1" />
		<flux:field.checkbox name="settings.checkbox" label="checkbox" default="0" />
		<flux:field.select name="settings.select" label="select" items="left,right" default="left" emptyOption="2"/>
		<flux:field.radio name="settings.radio" label="radio" items="left,right" default="left" emptyOption="2"/>
		<flux:field.file name="file" label="file" useFalRelation="1" />
		<flux:field.inline.fal name="inline.fal" label="inline.fal" collapseAll="1" expandSingle="1" allowedExtensions="jpg,jpeg,png,svg" />
		<flux:field.inline name="inline" table="tt_content" />
		<flux:field.relation name="relation" table="tt_content" />
		<flux:field.MultiRelation name="MultiRelation" table="tt_content" />
		<flux:field.tree.category name="tree.category" label="tree.category" showThumbs="0" expandAll="1" />
