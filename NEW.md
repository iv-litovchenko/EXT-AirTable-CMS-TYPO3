## https://codebeautify.org/php-beautifier
```
1) 
SELECT backend_layout FROM `pages` GROUP BY backend_layout;
#update pages set backend_layout = REPLACE(backend_layout, 'pagets__ext_projiv_pagedefaultcontroller', 'pagets__tx_projiv_pagedefaultcontroller');
#update pages set backend_layout = REPLACE(backend_layout, 'pagets__ext_projiv_pagedefaultcontroller', 'pagets__tx_projiv_pagedefaultcontroller');

Подумать насчет названия propmedia, propref...
Название таблиц и ключей страниц и элементов контента TYPO3 правильное tx_airtable_domainmodel_...
Переименовать MySQL-ключи (убрать пробелы) по аналогии с "tx_typo3dummyextension_domain_model_typo3dummyextension"
Также переименуются! protected $prop_ext_air_table_modelname;

2) BaseUtility
BaseUtility.php - оптимизировать, убрать рефликсию класслов, передалть названия таблиц!
https://stackoverflow.com/questions/3014254/how-to-get-the-path-of-a-derived-class-from-an-inherited-method/3014344

3) Сравнить TCA до и после! 
```

### ВАЖНО - КЛАССЫ НЕ НАСЛЕДУЮТСЯ ОТ LITOVCHENKO!!!!
```php




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

			
						|| in_array('Litovchenko\AirTable\Controller\AbstractWidgetController',$class_parents)
							| in_array('Litovchenko\AirTable\ViewHelpers\AbstractViewHelper',$class_parents)
								
								
								Удалить абстрактный класс модели! Сделаем FIX-модели!
								 || in_array('Litovchenko\AirTable\Domain\Model\AbstractModel',$class_parents) -> Связать с  if(strstr($v,'Domain\Model\Ext\Ext')){ и не забудь их еще два подкласса: AbstractModelCrud & AbstractModelCrudOverride
									
									Отдельная песьня...
									|| in_array('Litovchenko\AirTable\Domain\Model\Fields\AbstractField',$class_parents)){
									
									
```
### $TYPO3 - наслоедование переменной?
### Проверь ForeignWhere и другие аналогичные параметры - работают не првильно!
