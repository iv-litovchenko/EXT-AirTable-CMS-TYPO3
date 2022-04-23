<?php
namespace Litovchenko\AirTable\Domain\Model\Tests;

class ExampleTableCategory extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelCrud',
		'name' => 'Таблица с примерами (категория)',
		'description' => 'Пример записей-категорий (.mm-вариант)',
		'baseFields' => [
			'RType',
			'title',
			'disabled',
			'deleted',
			'sorting',
			'propref_parent'
		],
		'dataFields' => []
	];
	
}
?>