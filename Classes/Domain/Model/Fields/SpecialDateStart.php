<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

use Litovchenko\AirTable\Domain\Model\Fields\DateTime;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialDateStart extends Date
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Специальное: начало активности записи',
		'_fields' => [
			'date_start' => [
				'type' => 'SpecialDateStart',
				'name' => 'Начало активности записи',
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'position' => '*|access|1'
			]
		]
	];
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = 'datetime';
    }
	
    /**
     * @modify array
     */
    public static function postBuildConfiguration($model, $table, $field)
    {
		$GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['starttime'] = 'date_start';
	}
}