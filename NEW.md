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
# Остановился на функции "getClassAnnotationValueNew"
1) Вот такие аннотации:  * @AirTable\AccessCustomPermOptions\Key2:<Name Two> их обработчик
2) Загрузка классов и их анализ сделаем 1 раз в глобальную переменную с разбивкой по "thisIs"
3) Типизация "thisIs"
4) Table-name
5) Как быть с трейтами в моделях?
6) Убрать абстрактыне классы
7) Просмотреть везде где идет обработка ublic static function parameterClassDescription  Например explode (особенно уделить POSITION полей)...      /**
     * @return array
     */
    public static function parameterPosition($class, $property, $keyAnnotation, $value)
    {
		$keyRealName = end(explode('\\',$keyAnnotation));
        return [$keyRealName => $value];
    }
8)
Убрать функции:     /**
     * @return string
     */
    public static function parameterClassDescription($class, $keyAnnotation, $value)
    {
        return $value;
    }
	
	9) Решить как быть с EXT-моделей
	10) После можно будет поудалять аннотации
	11) 11 TYPO3 для элементов содержимого
	12) Как быть с абстрактными классами хелпера и виджета (из-за регистр.аргумент)?

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
