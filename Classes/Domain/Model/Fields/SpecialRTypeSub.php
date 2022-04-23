<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\SwitchInt;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialRTypeSub extends Switcher
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Специальное: подтип записи // Todo',
		'_propertyAnnotations' => [
			'items' => 'userFunc:items',
			'itemsProcFunc' => 'string',
			'itemsModel' => 'string',
			'itemsWhere' => 'array',
		],
		'_fields' => [
			'RTypeSub' => [
				'type' => 'SpecialRTypeSub',
				'name' => 'Подтип записи // Todo',
				'show' => 1,
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'dataTypeConditionUse' => 'tx_data',
				'position' => '*|main|1'
			]
		]
	];
	
    /**
     * @return string
     */
    public static function databaseDefinitions($model, $table, $field)
    {
		return [$table=>[
			$field => 'varchar(255) DEFAULT \'\' NOT NULL'
		]];
    }
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
		$GLOBALS['TCA'][$table]['columns']['RTypeSub']['config']['type'] = 'select';
		$GLOBALS['TCA'][$table]['columns']['RTypeSub']['config']['renderType'] = 'selectSingle';
		$GLOBALS['TCA'][$table]['columns']['RTypeSub']['config']['items'] = [];
		$GLOBALS['TCA'][$table]['columns']['RTypeSub']['onChange'] = 'reload';
		unset($GLOBALS['TCA'][$table]['columns']['RTypeSub']['config']['default']);
    }
}