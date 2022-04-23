<?php
namespace Litovchenko\AirTable\Domain\Model\Users;

class FeUsers extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 				=> 'BackendModelCrudOverride',
		'keyInDatabase'			=> 'fe_users',
		'name' 					=> 'Frontend пользователи',
		'description' 			=> 'Регистрация модели в системе',
		'dataFields' => [
			'pid' => [ // -- NEW FIELD --
				'type' => 'Input.InvisibleInt',
				'name' => 'PID',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'doNotCheck' => 1
			],
			'username' => [
				'type' => 'Input',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'name' => [
				'type' => 'Input',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'email' => [
				'type' => 'Input.Email',
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
			'disable' => [
				'type' => 'FlagDisabled',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'starttime' => [
				'type' => 'Date.DateTime',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'endtime' => [
				'type' => 'Date.DateTime',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'tx_extbase_type' => [
				'type' => 'Switcher.Int',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
		],
		'mediaFields' => [
			'image' => [
				'type' => 'Media_1.Image',
				'show' => 1,
				'doNotSqlAnalyze' => 1
			],
		],
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