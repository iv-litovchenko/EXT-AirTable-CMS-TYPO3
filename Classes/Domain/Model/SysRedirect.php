<?php
namespace Litovchenko\AirTable\Domain\Model;

class SysRedirect extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendModelCrudOverride',
		'keyInDatabase'	=> 'sys_redirect',
		'name' 			=> 'Редиректы в системе',
		'description' 	=> 'Регистрация модели в системе',
		'dataFields' => [
			'source_host' => [
				'type' => 'Input',
				'doNotSqlAnalyze' => 1,
				'show' => 1,
			],
			'source_path' => [
				'type' => 'Input',
				'doNotSqlAnalyze' => 1,
				'show' => 1,
			],
			'target' => [
				'type' => 'Input.Link',
				'doNotSqlAnalyze' => 1,
				'show' => 1,
			],
			'target_statuscode' => [
				'type' => 'Switcher.Int',
				'doNotSqlAnalyze' => 1,
				'show' => 1,
			],
			'disabled' => [
				'type' => 'FlagDisabled',
				'doNotSqlAnalyze' => 1,
				'show' => 1,
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