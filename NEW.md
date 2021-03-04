```
0) 
class ExampleTable extends \Litovchenko\AirTable\Domain\Model\AbstractModelCrud
{

	#use \Litovchenko\AirTable\Domain\Model\Traits\Slug;		// ???? (сделать в самостоятельную страницу!)
	#use \Litovchenko\AirTable\Domain\Model\Traits\TextAndPicPreview;
	#use \Litovchenko\AirTable\Domain\Model\Traits\TextAndPicDetail;
    // use \Litovchenko\AirTable\Domain\Model\Traits\RelPolyDisplayForeignFields;	
	
	/**
	 * @AirTable\Field:<Input.Slug>
	 * @AirTable\Field\Position\*:<main,4>
	 * @AirTable\Field\Label:<Сегмент URL>
	 * @AirTable\Field\DoNotCheck:<1>
	 * @AirTable\Field\SelectMinimizeInc:<1>
	 * @AirTable\Field\DataTypeConditionUse:<tx_data,tx_data_category>
	 */
	#protected $slug;



Подумать насчет названия propmedia, propref...
Название таблиц и ключей страниц и элементов контента TYPO3 правильное tx_airtable_domainmodel_...
Переименовать MySQL-ключи (убрать пробелы) по аналогии с "tx_typo3dummyextension_domain_model_typo3dummyextension"
Также переименуются! protected $prop_ext_air_table_modelname;

1) BaseUtility HasTrait!
BaseUtility.php - оптимизировать, убрать рефликсию класслов, передалть названия таблиц!
https://stackoverflow.com/questions/3014254/how-to-get-the-path-of-a-derived-class-from-an-inherited-method/3014344

3) Трайты 
4.2 (продумать их реализцию для моделей Crud CrudOvveride)
5.3 (установка дефолтового конфика для специальных полей...) -их овверайд  'required' => 1, на примере $title
8) Посмотреть SQL Анализер (запустить и посмотреть будут ли изменения в БД?)
9) Проверить глобальные скоупы .... в буилдере! 

BaseRTypes
BaseTabs
Fields/Ok/Оставшиекся

Пересмотреть параметр "position" => 'Marker.Text.Rte|main|5' -> Структура|Таб|Позиция
Пересмотреть параметр "displayCond"					
2) Сравнить TCA до и после! 
3) Нужно ли разделение AbstractModelCrud и AbstractModelCrudOverride?
```

### ВАЖНО - КЛАССЫ НЕ НАСЛЕДУЮТСЯ ОТ LITOVCHENKO!!!!
```php
class DebugController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendModule',
		'name' 			=> 'Отладка кода',
		'description' 	=> 'Модуль для просмотра результатов работы кода написанного в файле: "typo3conf/UserDebug.php"',
		'access' 		=> 'admin',
		'section'		=> 'content',
		'position'		=> '100'
	];

class PageDefaultController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'FrontendPage',
		'name' 			=> 'Шаблон по умолчанию',
		'description' 	=> 'Шаблон по умолчанию'
	];

class ElementServiceController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController 
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'FrontendContentElement', 
		'type' 			=> 'Element || Gridelement || Plugin', 
		'name' 			=> 'Услуги',
		'description' 	=> 'Вывод элементов услуг',
	];
}

class SysFile extends \Litovchenko\AirTable\Domain\Model\AbstractModelCrudOverride
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 				=> 'BackendModelCrudOverride', || BackendModelCrud
		'name' 					=> 'Файл',
		'description' 			=> 'Регистрация модели в системе',
		'defaultListTypeRender' => 3
	];




	/** У EAV тоже были вкладки!!!! AbstractPageController
AbstractPageElementController
	/**
	* Табы по умолчанию (для атрибутов)
	* @return array
	*/
    public static function baseTabs()
    {
		return [
			0 => 'Основное',
		];
	}


1) Убрать абстрактыне классы 
2) Сделать Fix-для классов моделей, виджетов и хелперов (sql_autoload_register)
Остановился на виджетах Как быть с абстрактными классами хелпера и виджета (из-за регистр.аргумент)?
Остановился на хелперах Как быть с абстрактными классами хелпера и виджета (из-за регистр.аргумент)?

			+ if(in_array('Litovchenko\AirTable\Controller\AbstractModuleController',$class_parents)
				+|| in_array('Litovchenko\AirTable\Controller\AbstractPageController',$class_parents)
					+|| in_array('Litovchenko\AirTable\Controller\AbstractPageElementController',$class_parents)
						|| in_array('Litovchenko\AirTable\Controller\AbstractWidgetController',$class_parents)
							| in_array('Litovchenko\AirTable\ViewHelpers\AbstractViewHelper',$class_parents)
								
								
								Удалить абстрактный класс модели! Сделаем FIX-модели!
								 || in_array('Litovchenko\AirTable\Domain\Model\AbstractModel',$class_parents) -> Связать с  if(strstr($v,'Domain\Model\Ext\Ext')){ и не забудь их еще два подкласса: AbstractModelCrud & AbstractModelCrudOverride
									
									Отдельная песьня...
									|| in_array('Litovchenko\AirTable\Domain\Model\Fields\AbstractField',$class_parents)){
									
									
```
### $TYPO3 - наслоедование переменной?

