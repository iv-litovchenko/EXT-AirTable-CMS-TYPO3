```php
class DebugController extends AbstractModuleController
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

class PageDefaultController extends AbstractPageController
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

class ElementServiceController extends AbstractPageElementController 
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
		'type' 			=> 'Element',   ->>>>>>>>>Убрать этот параметр!
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
3) Просмотреть везде где идет обработка public static function parameterClassDescription  Например explode (особенно уделить POSITION полей)...      /**
     * @return array
     */
    public static function parameterPosition($class, $property, $keyAnnotation, $value)
    {
		$keyRealName = end(explode('\\',$keyAnnotation));
        return [$keyRealName => $value];
    }
4)
Убрать функции:     /**
     * @return string
     */
    public static function parameterClassDescription($class, $keyAnnotation, $value)
    {
        return $value;
    }
	
	
	5) После можно будет поудалять аннотации

```



1 Остановился на виджетах Как быть с абстрактными классами хелпера и виджета (из-за регистр.аргумент)?
2 Остановился на хелперах Как быть с абстрактными классами хелпера и виджета (из-за регистр.аргумент)?
3 Остановился на типе элемента контента 
4 Table-name (возможно переведем на генератор TYPO3 - в т.ч. сегменты именования элементов содержимого и названий таблиц в БД MySQL
5 Загрузка классов и их анализ сделаем 1 раз в глобальную переменную с разбивкой по "thisIs"
6 if(strstr($v,'Domain\Model\Ext\Ext')){

### Что искать в классах что все вырезал?
А) "function parameterClass"
Б) " * @AirTable\"

/**
 * @AirTable\Label:<Название модуля>
 * @AirTable\Description:<Название модуля (описание)>
 * @AirTable\Section:<web || file || user || help || content || extension>
 * @AirTable\Position:<50>
 */
