```
1) Как быть с ExtPages например static::TYPO3 не наследуется...
2) Удалить аннотации...  * @AirTable\Field:
3) Посмотреть SQL Анализер (запустить и посмотреть будут ли изменения в БД?)

BaseRTypes
BaseTabs
Fields/Ok /Оставшиекся
Трайты
Подумать насчет названия propmedia, propref...
Название таблиц tx_airtable_domainmodel_...
Пересмотреть 		
				'items:1' => 'Отмечен',
				'items:2' => 'Отмечен',
				'items:3' => 'Отмечен',
				'position[*]' => 'props,0'
displayCond и все что массивы & 'item's & 'position' => [
					'Marker.Text.Rte|main|5'
				]
									
1) parameterPosition && parameterItems
2) Сравнить TCA до и после! (также лучше сверить значения аннотации и public static $TYPO3['fields']
Проверить также что все начинается с маленькой буквы "doNotSqlAnalyze", "selectMinimizeInc"!!

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
```


1) Как быть с трейтами в моделях?
2) Убрать абстрактыне классы
3) Просмотреть везде где идет обработка public static function parameterClassDescription  Например explode (особенно уделить POSITION полей)...


```



1 Остановился на виджетах Как быть с абстрактными классами хелпера и виджета (из-за регистр.аргумент)?
2 Остановился на хелперах Как быть с абстрактными классами хелпера и виджета (из-за регистр.аргумент)?
3 Переименовать MySQL-ключи (убрать пробелы) по аналогии с "tx_typo3dummyextension_domain_model_typo3dummyextension"
Также переименуются! protected $prop_ext_air_table_modelname;
4 Оптимизировать функцию: getTableNameFromClass\
https://stackoverflow.com/questions/3014254/how-to-get-the-path-of-a-derived-class-from-an-inherited-method/3014344
И вообще все функции утилиты...

### Что искать в классах что все вырезал?
А) "function parameterClass"
Б) "function parameter"
В) " * @AirTable\"

/**
 * @AirTable\Label:<Название модуля>
 * @AirTable\Description:<Название модуля (описание)>
 * @AirTable\Section:<web || file || user || help || content || extension>
 * @AirTable\Position:<50>
 */



	/** EAV!!! AbstractPageController
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




			+ if(in_array('Litovchenko\AirTable\Controller\AbstractModuleController',$class_parents)
				+|| in_array('Litovchenko\AirTable\Controller\AbstractPageController',$class_parents)
					+|| in_array('Litovchenko\AirTable\Controller\AbstractPageElementController',$class_parents)
						+ || in_array('Litovchenko\AirTable\Controller\AbstractWidgetController',$class_parents)
							+ || in_array('Litovchenko\AirTable\ViewHelpers\AbstractViewHelper',$class_parents)
								
								|| in_array('Litovchenko\AirTable\Domain\Model\AbstractModel',$class_parents) -> Связать с  if(strstr($v,'Domain\Model\Ext\Ext')){ и не забудь их еще два подкласса: AbstractModelCrud & AbstractModelCrudOverride
									
									Отдельная песьня...
									|| in_array('Litovchenko\AirTable\Domain\Model\Fields\AbstractField',$class_parents)){
									
									

