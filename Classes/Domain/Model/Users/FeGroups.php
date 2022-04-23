<?php
namespace Litovchenko\AirTable\Domain\Model\Users;

class FeGroups extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
	/**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 				=> 'BackendModelCrudOverride',
		'keyInDatabase'			=> 'fe_groups',
		'name' 					=> 'Frontend пользователи (группы)',
		'description' 			=> 'Регистрация модели в системе',
		'dataFields' => [
			'pid' => [ // -- NEW FIELD --
				'type' => 'Input.InvisibleInt',
				'name' => 'PID',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'doNotCheck' => 1,
			],
			'title' => [
				'type' => 'Input',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1,
			],
			'description' => [
				'type' => 'Text',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1,
			],
			'hidden' => [
				'type' => 'FlagDisabled',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1,
			],
			'tx_extbase_type' => [
				'type' => 'Switcher.Int',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1,
			]
		]
	];
	
	/**
	* Переопределение массива настроек таблицы
	* @configuration (TCA array)
	* @return array
	*/
    public static function postBuildConfiguration(&$configuration = [])
    {
		// Разрешаем создание записей в корне дерева страниц сайта
		$configuration['ctrl']['rootLevel'] = -1;
	}
}
?>