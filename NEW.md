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
		'thisIs' 		=> 'frontendNewContentElement', ->>>>>>>>>Переименовать! Зделать здесь разбивку на типы...
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

1) Загрузка классов и их анализ сделаем 1 раз в глобальную переменную с разбивкой по "thisIs"
2) Table-name
3) Как быть с трейтами в моделях?
4) Убрать абстрактыне классы
5) Просмотреть везде где идет обработка ublic static function parameterClassDescription  Например explode (особенно уделить POSITION полей)...      /**
     * @return array
     */
    public static function parameterPosition($class, $property, $keyAnnotation, $value)
    {
		$keyRealName = end(explode('\\',$keyAnnotation));
        return [$keyRealName => $value];
    }
6)
Убрать функции:     /**
     * @return string
     */
    public static function parameterClassDescription($class, $keyAnnotation, $value)
    {
        return $value;
    }
	
	7) Решить как быть с EXT-моделей
	8) После можно будет поудалять аннотации

```
    /**
     * @return string
     */
    public static function parameterClassLabel($class, $keyAnnotation, $value)
    {
		if(strstr($class,'Domain\Model\Ext\Ext')){
			$class_parents = class_parents($class);
			return BaseUtility::getClassAnnotationValueNew(current($class_parents),'AirTable\Label') . '. '.$value;
		} else {
			return $value;
		}
    }


1 Остановился на виджетах Как быть с абстрактными классами хелпера и виджета (из-за регистр.аргумент)?
2 Остановился на хелперах Как быть с абстрактными классами хелпера и виджета (из-за регистр.аргумент)?
3 Остановился на типе элемента контента 
