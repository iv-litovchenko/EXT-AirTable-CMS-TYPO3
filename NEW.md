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
		'thisIs' 		=> 'backendModule',
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
		'thisIs' 		=> 'frontendPage',
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
		'thisIs' 		=> 'frontendContentElement', 
		'name' 			=> 'Услуги',
		'description' 	=> 'Вывод элементов услуг',
		'type' 			=> 'element || gridelement || plugin', 
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
		'thisIs' 				=> 'backendModelCrudOverride', || backendModelCrud
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
4 Оптимизировать функцию: getTableNameFromClass\
https://stackoverflow.com/questions/3014254/how-to-get-the-path-of-a-derived-class-from-an-inherited-method/3014344
И вообще все функции утилиты...

### Что искать в классах что все вырезал?
А) "function parameterClass"
Б) " * @AirTable\"

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
									
									
									
									
