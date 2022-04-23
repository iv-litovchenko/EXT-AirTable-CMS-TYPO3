<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

use Litovchenko\AirTable\Domain\Model\Fields\Flag;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialDeleted extends Flag
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Специальное: корзина',
		'_propertyAnnotations' => [
			'items' => 'userFunc:items',
		],
		'_fields' => [
			'deleted' => [
				'type' => 'SpecialDeleted',
				'name' => 'Корзина',
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'position' => '*|access|4'
			]
		]
	];
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'passthrough';
    }
	
    /**
     * @modify array
     */
    public static function postBuildConfiguration($model, $table, $field)
    {
		$GLOBALS['TCA'][$table]['ctrl']['delete'] = 'deleted';
	}
}