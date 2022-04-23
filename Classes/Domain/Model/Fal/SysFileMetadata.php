<?php
namespace Litovchenko\AirTable\Domain\Model\Fal;

class SysFileMetadata extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 				=> 'BackendModelCrudOverride',
		'keyInDatabase'			=> 'sys_file_metadata',
		'name' 					=> 'Файл (описание)',
		'description' 			=> 'Регистрация модели в системе',
		'defaultListTypeRender' => 3,
		'dataFields' => [
			'fileinfo' => [
				'type' => 'SpecialFalFileInfo',
				'name' => 'Префью',
				'show' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1
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
		],
		'relationalFields' => [
			'file' => [
				'type' => 'Rel_1To1_Inverse',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Fal\SysFile',
				'foreignKey' => 'metadata',
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1,
				'show' => 1,
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
		// Общие настройки
		// $configuration['ctrl']['hideTable'] = 0;
		// $configuration['columns']['file']['config']['foreign_table_where'] = ' AND sys_file.uid = ###REC_FIELD_file###' ;
		// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_file_metadata', 'file', '', '');
	}
	
}
?>