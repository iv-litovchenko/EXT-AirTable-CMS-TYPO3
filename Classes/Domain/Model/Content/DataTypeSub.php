<?php
namespace Litovchenko\AirTable\Domain\Model\Content;

class DataTypeSub extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelCrud',
		'keyInDatabase' => 'tx_data_type_sub',
		'name' => 'Материал (подтип)',
		'description' => '',
		'baseFields' => [
			'title',
			'sorting'
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
?>