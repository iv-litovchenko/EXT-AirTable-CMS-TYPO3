<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\AbstractField;
use Litovchenko\AirTable\Utility\BaseUtility;

class Enum extends AbstractField
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Выбор значений из списка',
		'description' 	=> 'По умолчанию "пустая строка", в названии значений допускается использование символов',
		'incEav' 		=> 1,
		'_propertyAnnotations' => [
			'items' => 'userFunc:items',
			'itemsProcFunc' => 'string',
			'itemsModel' => 'string',
			'itemsWhere' => 'string',
		]
	];
	
    /**
     * @return string
     */
    public static function databaseDefinitions($model, $table, $field)
    {
        return [$table=>[
			$field => 'text'
		]];
    }

    /**
     * @modify array
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model,$table,$field);
		$Items = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Items');
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'select';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['renderType'] = 'selectCheckBox'; // selectMultipleSideBySide
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['items'] = [];
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['items'] = $Items;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['size'] = 10;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['autoSizeMax'] = 10;
		
		// Значение по умолчанию
		$default = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Default');
		if(!empty($default)){
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['default'] = $default;
		} else {
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['default'] = '';
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
				
		// SELECT * FROM `tx_air_table_test`
		// WHERE NOT FIND_IN_SET('', field_enum) AND deleted = 0
		// $q->where(new Expression('FIND_IN_SET(:status, status)'))->addParams([':status' => 1]);

		// Включая (логика OR):
		$q = $q->where(function($q) use ($table,$field,$paramFilter){
			foreach($paramFilter as $k => $v){
				$temp = explode('|',$v);
				if($v == 'INCLUDE_EMPTY_OR'){
					$q->orWhere($table.'.'.$field,'=','');
				}
				if($temp[0] == 'INCLUDE_OR'){
					$q->orWhereRaw('FIND_IN_SET(?,'.$table.'.'.$field.')', [$temp[1]]);
				}
			}
		});
		
		// Включая (логика AND):
		$q = $q->where(function($q) use ($table,$field,$paramFilter){
			foreach($paramFilter as $k => $v){
				$temp = explode('|',$v);
				if($v == 'INCLUDE_EMPTY_AND'){
					$q->where($table.'.'.$field,'=','');
				}
				if($temp[0] == 'INCLUDE_AND'){
					$q->whereRaw('FIND_IN_SET(?,'.$table.'.'.$field.')', [$temp[1]]);
				}
			}
		});
		
		// Исключая (логика AND)
		$q = $q->where(function($q) use ($table,$field,$paramFilter){
			foreach($paramFilter as $k => $v){
				$temp = explode('|',$v);
				if($v == 'EXCLUDE_EMPTY_AND'){
					$q->where($table.'.'.$field,'!=','');
				}
				if($temp[0] == 'EXCLUDE_AND'){
					$q->whereRaw('NOT FIND_IN_SET(?,'.$table.'.'.$field.')', [$temp[1]]);
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
		$select .= '<select name="form1Field_'.$field.'[]" class="form-control btn-sm" size="'.((count($items)*3)+7).'" multiple>';
		
		$select .= '<optgroup label="Включая (логика OR):">';
		$select .= '<option value="INCLUDE_EMPTY_OR" '.(in_array('INCLUDE_EMPTY_OR',$paramFilter)?'selected':'').'>Значения не выбраны (пусто)</option>';
		foreach($items as $k => $v){
			if(trim($v[1]) != ''){
				$select .= '<option value="INCLUDE_OR|'.$v[1].'" '.(in_array('INCLUDE_OR|'.$v[1],$paramFilter)?'selected':'').'>['.$v[1].'] '.$GLOBALS['LANG']->sL($v[0]).'</option>';
			}
		}
		$select .= '</optgroup>';
		$select .= '<optgroup label="Включая (логика AND):">';
		$select .= '<option value="INCLUDE_EMPTY_AND" '.(in_array('INCLUDE_EMPTY_AND',$paramFilter)?'selected':'').'>Значения не выбраны (пусто)</option>';
		foreach($items as $k => $v){
			if(trim($v[1]) != ''){
				$select .= '<option value="INCLUDE_AND|'.$v[1].'" '.(in_array('INCLUDE_AND|'.$v[1],$paramFilter)?'selected':'').'>['.$v[1].'] '.$GLOBALS['LANG']->sL($v[0]).'</option>';
			}
		}
		$select .= '</optgroup>';
		/*
		$select .= '<optgroup label="Исключая (логика OR):">';
		$select .= '<option value="EXCLUDE_EMPTY_OR" '.(in_array('EXCLUDE_EMPTY_OR',$paramFilterValueList)?'selected':'').'>Значения не выбраны</option>';
		foreach($items as $k => $v){
			if(trim($v[1]) != ''){
				$select .= '<option value="EXCLUDE_OR|'.$v[1].'" '.(in_array('EXCLUDE_OR|'.$v[1],$paramFilterValueList)?'selected':'').'>['.$v[1].'] '.$v[0].'</option>';
			}
		}
		$select .= '</optgroup>';
		*/
		$select .= '<optgroup label="Исключая (логика AND):">';
		$select .= '<option value="EXCLUDE_EMPTY_AND" '.(in_array('EXCLUDE_EMPTY_AND',$paramFilter)?'selected':'').'>Значения не выбраны (пусто)</option>';
		foreach($items as $k => $v){
			if(trim($v[1]) != ''){
				$select .= '<option value="EXCLUDE_AND|'.$v[1].'" '.(in_array('EXCLUDE_AND|'.$v[1],$paramFilter)?'selected':'').'>['.$v[1].'] '.$GLOBALS['LANG']->sL($v[0]).'</option>';
			}
		}
		$select .= '</optgroup>';
		$select .= '</select>';
		$select .= '</div>';
		
		return $select;
		/* return '
			<!--
			<label class="btn btn-default btn-sm" style="margin: 0; margin-bottom: 5px; padding: 0 7px 0 7px; width: 100%; text-align: left; cursor: pointer;">
				<input type="radio" name="tab1['.$field.']" class="btn btn-default btn-sm" style="margin-top: 0px;" value="" '.($paramFilter == ''?'checked':'').'> 
				Любые значения
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
				Содержит следующие значения:
			</label>
			<label class="btn btn-default btn-sm" style="margin: 0; margin-bottom: 5px; padding: 0 7px 0 7px; width: 100%; text-align: left; cursor: pointer;">
				<input type="radio" name="tab1['.$field.']" class="btn btn-default btn-sm" style="margin-top: 0px;" value="NOT VALUE LIST" '.($paramFilter == 'NOT VALUE LIST'?'checked':'').'> 
				Ни одно из существующих значений
			</label>
			-->
		'; */
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
			$temp = [];
			$value = explode(',',$row[$field]);
			$items = $config['config']['items'];
			foreach($value as $k => $v){
				$flag = 0;
				$item_v = $v;
				$item_n = '';
				foreach($items as $k2 => $v2){
					if($v == $v2[1]){
						$flag = 1;
						$item_v = $v2[1];
						$item_n = $v2[0];
					}
				}
				if($flag == 1){
					$debugStr = '';
					if($GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] == true){
						$debugStr = '<code>['.$item_v.']</code> ';
					}
					$temp[] = $debugStr.$GLOBALS['LANG']->sL($item_n);
				}else{
					$temp[] = '<code>['.$item_v.']</code>';
				}
			}
			return '<td nowrap style="vertical-align: top;"><a href="'.$backendLink.'">'.implode('<br />',$temp).'</a></td>';
		} else {
			return '<td nowrap style="vertical-align: top;"><a href="'.$backendLink.'"><code style="'.STYLE_EMPTY_FIELD.'">Значения не выбраны</code></a></td>';
		}
	}
}
