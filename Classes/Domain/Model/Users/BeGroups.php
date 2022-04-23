<?php
namespace Litovchenko\AirTable\Domain\Model\Users;

class BeGroups extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
	/**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 				=> 'BackendModelCrudOverride',
		'keyInDatabase'			=> 'be_groups',
		'name' 					=> 'Backend пользователи (группы)',
		'description' 			=> 'Регистрация модели в системе',
		'dataFields' => [
			'title' => [
				'type' => 'Input',
				'show' => 1,
				'doNotSqlAnalyze' => 1
			],
			'description' => [
				'type' => 'Text',
				'show' => 1,
				'doNotSqlAnalyze' => 1
			],
			'hidden' => [
				'type' => 'FlagDisabled',
				'show' => 1,
				'doNotSqlAnalyze' => 1
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
	}
}
?>