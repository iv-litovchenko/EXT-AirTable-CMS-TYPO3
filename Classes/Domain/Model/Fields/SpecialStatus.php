<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

use Litovchenko\AirTable\Domain\Model\Fields\SwitcherStatus;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialStatus extends SwitcherStatus
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Специальное: доступность',
		'_propertyAnnotations' => [
			'items' => 'userFunc:items',
			'itemsProcFunc' => 'string',
			'itemsModel' => 'string',
			'itemsWhere' => 'array',
		],
		'_fields' => [
			'status' => [
				'type' => 'SpecialStatus',
				'name' => 'Доступность (статус активности)',
				'show' => 1,
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'dataTypeConditionUse' => 'tx_data,tx_data_category',
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
			0=>'Удален в корзину',
			1=>-3
		];
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['items'][] = [
			0=>'Черновик',
			1=>-2
		];
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['items'][] = [
			0=>'На рассмотрении',
			1=>-1
		];
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['items'][] = [
			0=>'Включено (активно)',
			1=>0
		];
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['items'][] = [
			0=>'Выключено (не активно)',
			1=>1
		];
    }
	
    /**
     * @modify array
     */
    public static function postBuildConfiguration($model, $table, $field)
    {
		$GLOBALS['TCA'][$table]['ctrl']['enablecolumns']['status'] = 'status';
	}
}