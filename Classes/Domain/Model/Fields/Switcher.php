<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\AbstractField;
use Litovchenko\AirTable\Utility\BaseUtility;

class Switcher extends AbstractField
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Логический переключатель Int (между набором представленных вариантов)',
		'description' 	=> 'По умолчанию "пустая строка"',
		'incEav' 		=> 1,
		'_propertyAnnotations' => [
			'items' => 'userFunc:items',
			'itemsProcFunc' => 'string',
			'itemsModel' => 'string',
			'itemsWhere' => 'array',
		]
	];
	
    /**
     * @return string
     */
    public static function databaseDefinitions($model, $table, $field)
    {
		$RenderType = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field',true);
		switch(strtolower($RenderType)){
			default:
				return [$table=>[
					$field => 'varchar(255) DEFAULT \'\' NOT NULL'
				]];
			break;
			case 'int':
				return [$table=>[
					$field => 'int(11) DEFAULT \'0\' NOT NULL'
				]];
			break;
		}
    }

    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model,$table,$field);
		$Items = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Items');
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'select'; // radio
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['renderType'] = 'selectSingle';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['maxitems'] = 1;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['items'] = [];
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['items'] = $Items;
		
		// Значение по умолчанию
		$default = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Default');
		if(!empty($default)){
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['default'] = $default;
		} else {
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['default'] = 0;
		}
		
		// itemsProcFunc
		if(BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\ItemsProcFunc') != ''){
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['itemsProcFunc'] = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\ItemsProcFunc');
			if(isset($GLOBALS['TCA'][$table]['columns'][$field]['config']['items']['ItemsProcFunc'])){
				unset($GLOBALS['TCA'][$table]['columns'][$field]['config']['items']['ItemsProcFunc']);
			}
		}
		
		// foreign_table
		// foreign_table_where
		$itemsModel = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\ItemsModel');
		$itemsWhere = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\ItemsWhere');
		if($itemsModel != ''){
			$itemsTable = BaseUtility::getTableNameFromClass($itemsModel);
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table'] = $itemsTable;
			unset($GLOBALS['TCA'][$table]['columns'][$field]['config']['items']['ItemsModel']);
			if($itemsWhere != ''){
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table_where'] = $itemsWhere;
				unset($GLOBALS['TCA'][$table]['columns'][$field]['config']['items']['ItemsWhere']);
			}
		}
    }
	
	// listController func
	public static function listControllerSqlBuilder(&$obj, &$q, $table, $field, $config){
		// $q->addSelect($table.'.'.$field);
		$paramFilter = BaseUtility::_POSTuc('form1Apply','form1Field_'.$field,'');
		
		// Включая (логика OR):
		$q = $q->where(function($q) use ($table,$field,$paramFilter){
			foreach($paramFilter as $k => $v){
				$temp = explode('|',$v);
				if($v == 'INCLUDE_EMPTY'){
					$q->orWhere($table.'.'.$field,'=','');
				}
				if($temp[0] == 'INCLUDE'){
					$q->orWhere($table.'.'.$field,'=',$temp[1]);
				}
			}
		});
		
		// Исключая (логика AND):
		$q = $q->where(function($q) use ($table,$field,$paramFilter){
			foreach($paramFilter as $k => $v){
				$temp = explode('|',$v);
				if($v == 'EXCLUDE_EMPTY'){
					$q->where($table.'.'.$field,'!=','');
					}
				if($temp[0] == 'EXCLUDE'){
					$q->where($table.'.'.$field,'!=',$temp[1]);
				}
			}
		});
		
		return $q;
	}
	
	// listController func
	public static function listControllerSqlBuilderOrder(){
		return true;
	}
	
	// listController func
	public static function listControllerHtmlFilter(&$obj, $table, $field, $config, &$uriBuilder){
		$paramFilter = BaseUtility::_POSTuc('form1Apply','form1Field_'.$field,'');
		
		/*
		$checkbox = '';
		$items = $config['config']['items'];
		foreach($items as $k => $v){
			if(trim($v[1]) != ''){
				$checkbox .= '
				<div style="margin-left: 15px;">
				<label class="btn btn-default btn-sm" style="margin: 0; margin-bottom: 5px; padding: 0 7px 0 7px; width: 100%; text-align: left; cursor: pointer;">
					<input type="checkbox" name="tab1['.$field.']" class="btn btn-default btn-sm" style="margin-top: 0px;" value="= '.$v[1].'" '.($paramFilter == '= '.$v[1]?'checked':'').'> 
					= '.$v[0].'
				</label>
				</div>
				';
			}
		}
		*/
		
		$items = $config['config']['items'];
		$select = '<div style="">';
		
		// tx_data
		$dataType = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('dataType');
		$sysEavAttrSelected = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected');
		if($field == 'RType' && isset($dataType)) {
			$select .= '<select name="form1Field_'.$field.'[]" class="form-control btn-sm" disabled>';
				$select .= '<option selected>'.$dataType.'</option>';
			$select .= '</select>';
			
		} elseif($field == 'entity_type' && isset($sysEavAttrSelected)) {
			$select .= '<select name="form1Field_'.$field.'[]" class="form-control btn-sm" disabled>';
				$select .= '<option selected>'.$sysEavAttrSelected.'</option>';
			$select .= '</select>';
			
		} else {
			$select .= '<select name="form1Field_'.$field.'[]" class="form-control btn-sm" size="'.((count($items)*2)+5).'" multiple>';
			$select .= '<optgroup label="Включая (логика OR):">';
			$select .= '<option value="INCLUDE_EMPTY" '.(in_array('INCLUDE_EMPTY',$paramFilter)?'selected':'').'>Значение не выбрано</option>';
			foreach($items as $k => $v){
				if(trim($v[1]) != ''){
					$select .= '<option value="INCLUDE|'.$v[1].'" '.(in_array('INCLUDE|'.$v[1],$paramFilter)?'selected':'').'><!--['.$v[1].']--> '.$GLOBALS['LANG']->sL($v[0]).'</option>';
				}
			}
			$select .= '</optgroup>';
			$select .= '<optgroup label="Исключая (логика AND):">';
			$select .= '<option value="EXCLUDE_EMPTY" '.(in_array('EXCLUDE_EMPTY',$paramFilter)?'selected':'').'>Значение не выбрано</option>';
			foreach($items as $k => $v){
				if(trim($v[1]) != ''){
					$select .= '<option value="EXCLUDE|'.$v[1].'" '.(in_array('EXCLUDE|'.$v[1],$paramFilter)?'selected':'').'><!--['.$v[1].']--> '.$GLOBALS['LANG']->sL($v[0]).'</option>';
				}
			}
			$select .= '</optgroup>';
			$select .= '</select>';
		}
		
		$select .= '</div>';

		return $select;
		/* return '
			<!--
			<label class="btn btn-default btn-sm" style="margin: 0; margin-bottom: 5px; padding: 0 7px 0 7px; width: 100%; text-align: left; cursor: pointer;">
				<input type="radio" name="tab1['.$field.']" class="btn btn-default btn-sm" style="margin-top: 0px;" value="" '.($paramFilter == ''?'checked':'').'> 
				Любое значение
			</label>
			<label class="btn btn-default btn-sm" style="margin: 0; margin-bottom: 5px; padding: 0 7px 0 7px; width: 100%; text-align: left; cursor: pointer;">
				<input type="radio" name="tab1['.$field.']" class="btn btn-default btn-sm" style="margin-top: 0px;" value="EMPTY" '.($paramFilter == 'EMPTY'?'checked':'').'> 
				Значение не выбрано (пустое поле)
			</label>
			<label class="btn btn-default btn-sm" style="margin: 0; margin-bottom: 5px; padding: 0 7px 0 7px; width: 100%; text-align: left; cursor: pointer;">
				<input type="radio" name="tab1['.$field.']" class="btn btn-default btn-sm" style="margin-top: 0px;" value="NOT EMPTY" '.($paramFilter == 'NOT EMPTY'?'checked':'').'> 
				Значение выбрано (не пустое поле)
			</label>
			<label class="btn btn-default btn-sm" style="margin: 0; margin-bottom: 5px; padding: 0 7px 0 7px; width: 100%; text-align: left; cursor: pointer;">
				<input type="radio" name="tab1['.$field.']" class="btn btn-default btn-sm" style="margin-top: 0px;" value="VALUE LIST" '.($paramFilter == 'VALUE LIST'?'checked':'').'> 
				Выбрать со следующими значениями:
			</label>
			<label class="btn btn-default btn-sm" style="margin: 0; margin-bottom: 5px; padding: 0 7px 0 7px; width: 100%; text-align: left; cursor: pointer;">
				<input type="radio" name="tab1['.$field.']" class="btn btn-default btn-sm" style="margin-top: 0px;" value="NOT VALUE LIST" '.($paramFilter == 'NOT VALUE LIST'?'checked':'').'> 
				Ни одно из существующих значений
			</label>
			-->
		';
		*/
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
		if(trim($row[$field]) != ''){
			$temp = BaseUtility::getTcaFieldItem($table,$field,$row[$field]);
			$item_n = $temp[0];
			$item_v = $temp[1];
			if($item_n == '' && $item_v == ''){
				return '<td nowrap style="vertical-align: top;"><a href="'.$backendLink.'"><code>['.$row[$field].']</code></a></td>';
			} else {
				$debugStr = '';
				if($GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] == true){
					$debugStr = '<code>['.$item_v.']</code> ';
				}
				return '<td nowrap style="vertical-align: top;"><a href="'.$backendLink.'">'.$debugStr.$GLOBALS['LANG']->sL($item_n).'</a></td>';
			}
		} else {
			return '<td nowrap style="vertical-align: top;"><a href="'.$backendLink.'"><code style="'.STYLE_EMPTY_FIELD.'">Значение не выбрано</code></a></td>';
		}
	}

}
