<?php
namespace Litovchenko\AirTable\Domain\Model\Fal;

class SysFileCategory extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 				=> 'BackendModelCrud',
		'keyInDatabase'			=> 'sys_file_category',
		'name' 					=> 'Todo // Файл (категория) - это и будут коллекции',
		'defaultListTypeRender' => 3,
		'baseFields' => [
			'title',
			'propref_parent',
		],
		'dataFields' => [
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
		// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('sys_file', 'metadata', '', 'after:storage');
	}
}
?>