<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\InputNumber;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialPid extends Input
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Специальное: PID',
		'_propertyAnnotations' => [
			'size' => 'string',
			'max' => 'string'
		],
		'_fields' => [
			'pid' => [
				'type' => 'SpecialPid',
				'name' => 'PID',
				'show' => 1,
				'readOnly' => 1,
				'doNotCheck' => 1,
				'doNotSqlAnalyze' => 1,
				'selectMinimizeInc' => 1,
				'position' => '*|main|0'
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
		// Возможность размещять записи в любом месте дерева страниц
		#if(BaseUtility::getClassAnnotationValueNew($class,'AirTable\PidAny') == 1){
			\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages($table);
			$GLOBALS['TCA'][$table]['ctrl']['hideTable'] = 0;
			$GLOBALS['TCA'][$table]['ctrl']['rootLevel'] = -1;
		#} else {
		#	$GLOBALS['TCA'][$table]['ctrl']['hideTable'] = 0;
		#	$GLOBALS['TCA'][$table]['ctrl']['rootLevel'] = 1;
		#}
	}
}