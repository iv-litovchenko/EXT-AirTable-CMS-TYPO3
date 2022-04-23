<?php
namespace Litovchenko\AirTable\Domain\Model\Tests;

class TestModel4 extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelCrud',
		'name' => 'Тест категоризации: ParentRow + CategoryRow',
		'baseFields' => [
			'RType',
			'title',
			'disabled',
			'deleted',
			'sorting',
			'propref_parent',
			'propref_category',
		],
		'dataFields' => []
	];
	
	/**
	* Переопределение массива настроек таблицы
	* @configuration (TCA array)
	* @return array
	*/
    public static function postBuildConfiguration(&$configuration = [])
    {
		//$configuration['ctrl']['...'] = 1;
	}
}