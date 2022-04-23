<?php
namespace Litovchenko\AirTable\Domain\Model\Users;

class BeUsers extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
	/**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 				=> 'BackendModelCrudOverride',
		'keyInDatabase'			=> 'be_users',
		'name' 					=> 'Backend пользователи',
		'description' 			=> 'Регистрация модели в системе',
		'dataFields' => [
			'username' => [
				'type' => 'Input',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'realName' => [
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
			'admin' => [
				'type' => 'Switcher.Int',
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
			]
		],
		'mediaFields' => [
			'avatar' => [
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
	}
}
?>