<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\AbstractField;
use Litovchenko\AirTable\Utility\BaseUtility;

class Folder extends AbstractField
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Выбор папки',
		'_propertyAnnotations' => [
			'minItems' => 'int',
			'maxItems' => 'int'
		]
	];
	
	const EXPORT = false;
	
	const IMPORT = false;
		
    /**
     * @return string
     */
    public static function databaseDefinitions($model, $table, $field)
    {
		$minitems = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\MinItems');
		$maxitems = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\MaxItems');
		if($minitems > 1 || $maxitems > 1){
			return [$table=>[
				$field => 'text'
			]];
		} else {
			return [$table=>[
				$field => 'varchar(2048) DEFAULT \'\' NOT NULL'
			]];
		}
    }

    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model,$table,$field);
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'group';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['internal_type'] = 'folder';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['minitems'] = 0;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['maxitems'] = 1;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['size'] = 1;
		
		// Минимальное кол-во
		$required = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Required');
		if(!empty($required)){
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['minitems'] = 1;
		}
		
		// Минимальное кол-во (2)
		$minitems = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\MinItems');
		if(!empty($minitems) && $minitems > 0){
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['minitems'] = $minitems;
		}
		
		// Максимальное кол-во
		$maxitems = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\MaxItems');
		if(!empty($maxitems) && $maxitems > 0){
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['maxitems'] = $maxitems;
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['size'] = 10;
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['autoSizeMax'] = 10;
		}
    }
	
	// listController func
	#public static function listControllerSqlBuilder(&$obj, &$q, $table, $field, $config){
	#}
	
	// listController func
	#public static function listControllerSqlBuilderOrder(){
	#	return true;
	#}
	
	// listController func
	#public static function listControllerHtmlFilter(&$obj, $table, $field, $config){
	#}
	
	// listController func
	public static function listControllerHtmlTh(&$obj, $table, $field, $config){
		return \Litovchenko\AirTable\Domain\Model\Fields\Input::listControllerHtmlTh($obj, $table, $field, $config);
	}
	
	// listController func
	public static function listControllerHtmlTd(&$obj, $table, $field, $config, $row, &$uriBuilder){
		$backendLink = \Litovchenko\AirTable\Utility\BaseUtility::getModuleUrl(
			'record_edit', [
				'columnsOnly' => $field,
				'edit['.$table.']['.$row['uid'].']' => 'edit',
				'returnUrl' => \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('REQUEST_URI')
			]
		);
		if(!empty($row[$field])){
			return '<td nowrap style="vertical-align: top;"><a href="'.$backendLink.'">'.str_replace(',','<br />',$row[$field]).'</a></td>';
		} else {
			return '<td nowrap style="vertical-align: top;"><a href="'.$backendLink.'"><code style="'.STYLE_EMPTY_FIELD.'">Заполнить</code></a></td>';
		}
	}
}