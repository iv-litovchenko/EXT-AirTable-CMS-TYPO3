<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\Input;
use Litovchenko\AirTable\Utility\BaseUtility;

class SpecialTitle extends Input
{
	const SECTION = 'baseFields';
	
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'BackendField',
		'name' => 'Специальное: название',
		'_propertyAnnotations' => [
			'size' => 'string',
			'max' => 'string'
		],
		'_fields' => [
			'title' => [
				'type' => 'SpecialTitle',
				'name' => 'Название',
				'liveSearch' => 1,
				'required' => 0,
				'doNotCheck' => 1,
				'selectMinimizeInc' => 1,
				'show' => 1,
				'position' => '*|main|2'
			]
		]
	];
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
    }
	
    /**
     * @modify array
     */
    public static function postBuildConfiguration($model, $table, $field)
    {
		$GLOBALS['TCA'][$table]['ctrl']['label'] = "title"; // Поля заголовка (для списка)
	}
}