<?php
namespace Litovchenko\AirTable\Domain\Model\Ext;

class ExtExampleTable3 extends \Litovchenko\AirTable\Domain\Model\Tests\ExampleTable3
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
		'baseFields' => [
			'RType' => [
				'items' => [
					4 => 'Тип 4',
					5 => 'Тип 5'
				]
			],
			'alt_title' => [
				'required' => 1,
			],
			'date_update',
			'date_start',
			'date_end'
		],
		'dataFields' => [
			'prop_tx_airtable_newfield' => [
				'type' => 'Text',
				'name' => 'Тестовое поле 2',
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