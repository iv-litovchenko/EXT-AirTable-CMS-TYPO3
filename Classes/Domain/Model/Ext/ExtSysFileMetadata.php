<?php
namespace Litovchenko\AirTable\Domain\Model\Ext;

class ExtSysFileMetadata extends \Litovchenko\AirTable\Domain\Model\Fal\SysFileMetadata
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelExtending',
		'description' => 'Добавляем поля в таблицу файлов',
		'dataFields' => [
			'prop_tx_airtable_field_test' => [
				'type'=>'Input',
				'name'=>'Ext:AirTable -> Input'
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