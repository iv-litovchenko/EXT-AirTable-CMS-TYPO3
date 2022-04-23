<?php
namespace Litovchenko\AirTable\Domain\Model\Tests;

class TestModel4Category extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelCrud',
		'name' => 'Тест категоризации: CategoryRow (категория)',
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