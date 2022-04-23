<?php
namespace Litovchenko\AirTable\Domain\Model\Tests;

class ExampleTable7 extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelCrud',
		'name' => 'Таблица 7 (Полиморфные связи P_M:M)',
		'baseFields' => [
			'title',
			'disabled',
			'deleted',
			'sorting'
		],
		'dataFields' => [],
		'relationalFields' => [
			#'proptblref_exampletable_row' => [
			#	'type' => 'Rel_Poly_MToM_Inverse',
			#	'name' => 'Основная таблица',
			#	'foreignModel' => 'Litovchenko\AirTable\Domain\Model\Tests\ExampleTable',
			#	'foreignKey' => 'proptblref_exampletable7_rows',
			#]
		]
	];
}
?>