<?php
namespace Litovchenko\AirTable\Domain\Model\Fal;

class SysFilemounts extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 				=> 'BackendModelCrudOverride',
		'keyInDatabase'			=> 'sys_filemounts',
		'name' 					=> 'Точка монтирования файлов',
		'description' 			=> 'Регистрация модели в системе',
		'dataFields' => [
			'title' => [
				'type' => 'Input',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'description' => [
				'type' => 'Text',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'hidden' => [
				'type' => 'FlagDisabled',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'path' => [
				'type' => 'Enum',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'read_only' => [
				'type' => 'Flag',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
		],
		'relationalFields' => [
			'base' => [
				'type' => 'Rel_MTo1',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Fal\SysFileStorage',
				'foreignKey' => 'filemounts',
				'doNotSqlAnalyze' => 1,
				'show' => 1
			],
		]
	];
	
	/**
	* Переопределение массива настроек таблицы
	* @configuration (TCA array)
	* @return array
	*/
    public static function postBuildConfiguration(&$configuration = [])
    {
	}
}
?>