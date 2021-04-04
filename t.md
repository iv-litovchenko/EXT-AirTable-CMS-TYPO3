```
2) Шорткоды передать
3) Многостраничники плагин VS pagecontent
4) Остановился на разработке каталога (Пагинация , Хлеб крошки)
5) Пересмотреть Tabs, Position

* FAL:  Конвертация массива файлов with() в объекы FAL при выборке (скорее всего сделаем свой обработчик)? 
  * Отказаться от постфиксов "_func", сделать также алиас для uid_local_func as file
  * https://laravel.com/docs/8.x/eloquent-mutators 
  * https://docs.typo3.org/m/typo3/reference-coreapi/master/en-us/ApiOverview/Fal/UsingFal/Frontend.html
  * protected function defineEntity(ClassDefinition $class) { $class->property($this->engine)->asObject(Engine::class); }
  * <f:for each="{orderItems.files}" as="file"><f:image src="{file.uid}" treatIdAsReference="1" /></f:for>
  * <f:for each="{juchgasse.bimagesingle}" as="image"><f:image src="{image.originalResource.publicUrl}" width="200" /></f:for>

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
