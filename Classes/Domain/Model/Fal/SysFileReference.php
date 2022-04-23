<?php
namespace Litovchenko\AirTable\Domain\Model\Fal;

class SysFileReference extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 				=> 'BackendModelCrudOverride',
		'keyInDatabase'			=> 'sys_file_reference',
		'name' 					=> 'Ссылка на файл',
		'description' 			=> 'Регистрация модели в системе',
		'dataFields' => [
			'pid' => [ // -- NEW FIELD --
				'type' => 'Input.InvisibleInt',
				'name' => 'PID',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'doNotCheck' => 1
			],
			'title' => [
				'type' => 'Input',
				'show' => 1,
				'liveSearch' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'description' => [
				'type' => 'Text',
				'show' => 1,
				'liveSearch' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'hidden' => [
				'type' => 'FlagDisabled',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'tablenames' => [
				'type' => 'Input',
				'name' => 'POLY.1 Таблица',
				'show' => 1,
				'readOnly' => 0,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'fieldname' => [
				'type' => 'Input',
				'name' => 'POLY.2 Колонка',
				'show' => 1,
				'readOnly' => 0,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'uid_foreign' => [
				'type' => 'Input.Number',
				'name' => 'POLY.3 Запись (ID)',
				'show' => 1,
				'readOnly' => 0,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			],
			'sorting_foreign' => [
				'type' => 'Input.Number',
				'name' => 'POLY.4 Сортировка',
				'show' => 1,
				'readOnly' => 0,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
			]
		],
		'relationalFields' => [
			'uid_local' => [
				'type' => 'Rel_MTo1.Large',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Fal\SysFile',
				'foreignKey' => 'reference',
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1,
				'show' => 1
			],
		]
	];
	
	// Alias
	protected $maps = [
		# 'idUser' => 'crdate',
		# 'tablenames' => 'tablenames2',
		# 'uid_local' => 'sys_file_row_id'
	];

	protected $visible = [
		# 'idUser'
	];

	protected $appends = [
		# 'idUser'
	];
	
    /**
     * Get configuration (mutator).
     * SysFileStorage.
     *
     * @param  xml  $value
     * @return array
     */
	public function getIdUserAttribute($value)
	{
		return 123;
    }
	
	/**
	* Переопределение массива настроек таблицы
	* @configuration (TCA array)
	* @return array
	*/
    public static function postBuildConfiguration(&$configuration = [])
    {
		// Общие настройки
		// $configuration['ctrl']['hideTable'] = 0;
		// $configuration['columns']['uid_local']['config']['hideSuggest'] = false;
		// $configuration['palettes']['filePalette']['isHiddenPalette'] = 0;
		
		// Сортировка
		$temp1 = $configuration['columns']['tablenames'];
		$temp2 = $configuration['columns']['fieldname'];
		$temp3 = $configuration['columns']['uid_foreign'];
		$temp4 = $configuration['columns']['sorting_foreign'];
		$temp5 = $configuration['columns']['uid_local'];
		unset($configuration['columns']['tablenames']);
		unset($configuration['columns']['fieldname']);
		unset($configuration['columns']['uid_foreign']);
		unset($configuration['columns']['sorting_foreign']);
		unset($configuration['columns']['uid_local']);
		$configuration['columns']['tablenames'] = $temp1;
		$configuration['columns']['fieldname'] = $temp2;
		$configuration['columns']['uid_foreign'] = $temp3;
		$configuration['columns']['sorting_foreign'] = $temp4;
		$configuration['columns']['uid_local'] = $temp5;
		
		// Todo - решить что делать с этими колонками,
		// Зависит от того как оформим полиморфизм
		// Запись, к которой привязана ссылка на файл
		// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_file_reference', 'tablenames', '', '');
		// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_file_reference', 'fieldname', '', '');
		// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_file_reference', 'uid_foreign', '', '');
		// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_file_reference', 'sorting_foreign', '', '');
	}

}
?>