<?php
namespace Litovchenko\AirTable\Domain\Model\Ext;

class ExtPages extends \Litovchenko\AirTable\Domain\Model\Content\Pages
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelExtending',
		'description' => 'Добавляем поля в таблицу страниц',
		'dataFields' => [
			'prop_tx_airtable_field_1' => [
				'type'=>'Input',
				'name'=>'Ext:AirTable -> Input 1',
				'position' => '1|extended|0',
			],
			'prop_tx_airtable_field_2' => [
				'type'=>'Input',
				'name'=>'Ext:AirTable -> Input 2',
				'position' => '1|extended|0',
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