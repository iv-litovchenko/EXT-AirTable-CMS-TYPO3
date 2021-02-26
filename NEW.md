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
1) Вот такие аннотации:  * @AirTable\AccessCustomPermOptions\Key2:<Name Two>
2) Загрузка классов и их анализ 1 раз
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
	
	9) После можно будет поудалять аннотации
