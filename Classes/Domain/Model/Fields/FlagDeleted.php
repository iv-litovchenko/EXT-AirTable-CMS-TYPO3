<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\Flag;
use Litovchenko\AirTable\Utility\BaseUtility;

class FlagDeleted extends Flag
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Служебное',
		'_propertyAnnotations' => [
			'items' => 'userFunc:items',
		]
	];
	
    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model,$table,$field);
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'passthrough';
	}
	// listController func
	#public static function listControllerHtmlTh(&$obj, $table, $field, $config){
	#	return '';
	#}
	
	// listController func
	#public static function listControllerHtmlTd(&$obj, $table, $field, $config, $row, &$uriBuilder){
	#	return '';
	#}
}