<?php
namespace Litovchenko\AirTable\Domain\Model\Ext;

class ExtExampleTable1 extends \Litovchenko\AirTable\Domain\Model\Tests\ExampleTable1
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelExtending',
		'tabs' => [
			'newtab' => 'EXT:AirTable New Tab (###COUNT###)',
		],
		'dataFields' => [
			'prop_tx_airtable_newfield' => [
				'type' => 'Text',
				'name' => 'Тестовое поле',
				'position' => '*|newtab|100',
			]
		],
		'relationalFields' => [
			#'proprefinv_exampletable' => [
			#	'type' => 'Rel_1To1_Inverse',
			#	'name' => 'Основная таблица',
			#	'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable',
			#	'foreignKey' => 'propref_exampletable1',
			#]
		]
	];
}
?>