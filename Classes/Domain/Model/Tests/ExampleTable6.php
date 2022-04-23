<?php
namespace Litovchenko\AirTable\Domain\Model\Tests;

class ExampleTable6 extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelCrud',
		'name' => 'Таблица 6 (Полиморфные связи P_1:M)',
		'baseFields' => [
			'title',
			'disabled',
			'deleted',
			'sorting',
			'foreign_table',
			'foreign_field',
			'foreign_uid',
			'foreign_sortby'
		],
		'dataFields' => [],
		'relationalFields' => [
			'proprefinv_exampletable' => [
				'type' => 'Rel_Poly_1ToM_Inverse',
				'name' => 'Основная таблица',
				'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable',
				'foreignKey' => 'propref_exampletable6',
			]
		]
	];
}
?>