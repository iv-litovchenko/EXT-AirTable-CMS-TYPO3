<?php
namespace Litovchenko\AirTable\Domain\Model\Content;

class DataTypeGroup extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendModelCrud',
		'keyInDatabase' => 'tx_data_type_group',
		'name' => 'Материал (группа)',
		'description' => '',
		'baseFields' => [
			'uidkey' => ['readOnly' => 1],
			'title' => ['readOnly' => 1],
			'sorting' => ['readOnly' => 1]
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
		$configuration['ctrl']['readOnly'] = 1;
	}
}
?>