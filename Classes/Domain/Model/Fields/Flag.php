<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\AbstractField;
use Litovchenko\AirTable\Utility\BaseUtility;

class Flag extends AbstractField
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Логический выключатель (флаг да/нет)',
		'description' 	=> 'По умолчанию "0"',
		'incEav' 		=> 1,
		'_propertyAnnotations' => [
			'items' => 'userFunc:items',
		]
	];
	
    /**
     * @return string
     */
    public static function databaseDefinitions($model, $table, $field)
    {
        return [$table=>[
			$field => 'int(11) DEFAULT \'0\' NOT NULL'
		]];
    }

    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model,$table,$field);
		$Items = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Items');
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'check';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['items'] = [];
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['items'] = $Items;
		
		// Значение по умолчанию
		$default = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Default');
		if(!empty($default)){
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['default'] = $default;
		} else {
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['default'] = 0;
		}
    }
	
	// listController func
	public static function listControllerSqlBuilder(&$obj, &$q, $table, $field, $config){
		// $q->addSelect($table.'.'.$field);
		$paramFilter = BaseUtility::_POSTuc('form1Apply','form1Field_'.$field,'');
		switch($paramFilter){
			//case 'any': break;
			case '= 1': 
				return $q->where($table.'.'.$field,'=',1); 
			break;
			case '= 0': 
				return $q->where($table.'.'.$field,'=',0); 
			break;
			case '!= 0 AND != 1':
				return $q->where($table.'.'.$field,'<>',0)->where($table.'.'.$field,'<>',1); 
			break;
		}
		return $q;
	}
	
	// listController func
	public static function listControllerSqlBuilderOrder(){
		return true;
	}
	
	// listController func
	public static function listControllerHtmlFilter(&$obj, $table, $field, $config, &$uriBuilder){
		$paramFilter = BaseUtility::_POSTuc('form1Apply','form1Field_'.$field,'');
		return '
			<select name="form1Field_'.$field.'" class="form-control btn-sm">
				<option value="" '.($paramFilter == ''?'selected':'').'>Любое значение</option>
				<option value="= 1" '.($paramFilter == '= 1'?'selected':'').'>= 1</option>
				<option value="= 0" '.($paramFilter == '= 0'?'selected':'').'>= 0</option>
				<option value="!= 0 AND != 1" '.($paramFilter == '!= 0 AND != 1'?'selected':'').'>!= 0 AND != 1</option>
			</select>
		';
	}
	
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
		if($row[$field] == 1){
			$temp = BaseUtility::getTcaFieldItem($table,$field,1);
			$item_1 = $temp[0];
			$debugStr = '';
			if($GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] == true){
				$debugStr = '<code>[1]</code> ';
			}
			return '<td nowrap style="vertical-align: top;"><a href="'.$backendLink.'">'.$debugStr.$GLOBALS['LANG']->sL($item_1).'</a></td>';
		} else {
			$debugStr = '';
			if($GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] == true){
				$debugStr = '<code>[0]</code> ';
			}
			return '<td nowrap style="vertical-align: top;"><a href="'.$backendLink.'">'.$debugStr.'-</a></td>';
		}
	}
}
