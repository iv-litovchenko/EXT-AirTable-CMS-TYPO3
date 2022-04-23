<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

use Litovchenko\AirTable\Domain\Model\Fields\FlagDisabled;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialDisabled extends FlagDisabled
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Специальное: активность',
		'_propertyAnnotations' => [
			'items' => 'userFunc:items',
		],
		'_fields' => [
			'disabled' => [
				'type' => 'SpecialDisabled',
				'name' => 'Активность',
				'show' => 1,
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'position' => '*|access|3'
			]
		]
	];
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['items'] = [];
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['items'][] = [
			0=>'Выключен',
			1=>1
		];
    }
	
    /**
     * @modify array
     */
    public static function postBuildConfiguration($model, $table, $field)
    {
		$GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['disabled'] = 'disabled';
	}
}