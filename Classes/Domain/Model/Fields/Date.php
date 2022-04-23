<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\AbstractField;
use Litovchenko\AirTable\Utility\BaseUtility;

class Date extends AbstractField
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendField',
		'name' 			=> 'Дата, время',
		'subTypes' 		=> 'DateTime,Time,Timesec,Year',
		'incEav' 		=> 1
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
		
		$RenderType = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field',true);
		switch(strtolower($RenderType)){
			default:
			case 'date':
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
				if (version_compare(TYPO3_version, '8.0.0', '>=')){
					$GLOBALS['TCA'][$table]['columns'][$field]['config']['renderType'] = 'inputDateTime';
				}
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = 'date';
			break;
			case 'datetime':
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
				if (version_compare(TYPO3_version, '8.0.0', '>=')){
					$GLOBALS['TCA'][$table]['columns'][$field]['config']['renderType'] = 'inputDateTime';
				}
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = 'datetime';
			break;
			case 'time':
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
				if (version_compare(TYPO3_version, '8.0.0', '>=')){
					$GLOBALS['TCA'][$table]['columns'][$field]['config']['renderType'] = 'inputDateTime';
				}
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = 'time';
			break;
			case 'timesec':
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
				if (version_compare(TYPO3_version, '8.0.0', '>=')){
					$GLOBALS['TCA'][$table]['columns'][$field]['config']['renderType'] = 'inputDateTime';
				}
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = 'timesec';
			break;
			case 'year':
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = 'year';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['min'] = 4;
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['max'] = 4;
			break;
		}
    }
	
	// listController func
	public static function listControllerSqlBuilder(&$obj, &$q, $table, $field, $config){
		// $q->addSelect($table.'.'.$field);
		$paramFilter_select = BaseUtility::_POSTuc('form1Apply','form1Field_'.$field,'');
		$paramFilter_value = BaseUtility::_POSTuc('form1Apply','form1FieldValue_'.$field,'');
		
		$dateStart = $paramFilter_value['start'];
		$dateEnd = $paramFilter_value['end'];
		
		// + 3600 // 01:00
		// + 53139 // 14:45:39

		#$timestamp = strtotime('01:00') - strtotime('00:00');
		#print $timestamp . '<hr />';

		#$timestamp = strtotime('14:45:39') - strtotime('00:00:00');
		#print $timestamp . '<hr />';

		$conf = end(explode("\\",$config['AirTable.ClassWithSub']));
		switch(strtolower($conf)){
			default:
			case 'date': 
				
				// + 1560632400 // 16-06-2019
				$dateParseStart = date_parse_from_format('d-m-Y', $dateStart['d'].'-'.$dateStart['m'].'-'.$dateStart['Y']); // 16-06-2019
				$timestampStart = mktime(0, 0, 0, $dateParseStart['month'], $dateParseStart['day'], $dateParseStart['year']);
				
				// + 1560632400 // 16-06-2019
				$dateParseEnd = date_parse_from_format('d-m-Y', $dateEnd['d'].'-'.$dateEnd['m'].'-'.$dateEnd['Y']); // 16-06-2019
				$timestampEnd = mktime(0, 0, 0, $dateParseEnd['month'], $dateParseEnd['day'], $dateParseEnd['year']);
		
			break;
			case 'datetime':
				
				// + 1560685500 // 14:45 16-06-2019
				$dateParseStart = date_parse_from_format('H:i d-m-Y', $dateStart['H'].':'.$dateStart['i'].' '.$dateStart['d'].'-'.$dateStart['m'].'-'.$dateStart['Y']); // 14:45 16-06-2019
				$timestampStart = mktime($dateParseStart['hour'], $dateParseStart['minute'], 0, $dateParseStart['month'], $dateParseStart['day'], $dateParseStart['year']);
				
				// + 1560685500 // 14:45 16-06-2019
				$dateParseEnd = date_parse_from_format('H:i d-m-Y', $dateStart['H'].':'.$dateStart['i'].' '.$dateEnd['d'].'-'.$dateEnd['m'].'-'.$dateEnd['Y']); // 14:45 16-06-2019
				$timestampEnd = mktime($dateParseEnd['hour'], $dateParseEnd['minute'], 0, $dateParseEnd['month'], $dateParseEnd['day'], $dateParseEnd['year']);
				
			break;
			case 'time':
				$timestampStart = strtotime($dateStart['H'].':'.$dateStart['i']) - strtotime('00:00'); // + 3600 // 01:00
				$timestampEnd = strtotime($dateEnd['H'].':'.$dateEnd['i']) - strtotime('00:00'); // + 3600 // 01:00
			break;
			case 'timesec':
				$timestampStart = strtotime($dateStart['H'].':'.$dateStart['i'].':'.$dateStart['s']) - strtotime('00:00:00'); // + 53139 // 14:45:39
				$timestampEnd = strtotime($dateEnd['H'].':'.$dateEnd['i'].':'.$dateEnd['s']) - strtotime('00:00:00'); // + 53139 // 14:45:39
			break;
			case 'year':
				$timestampStart = $dateStart['Y'];
				$timestampEnd = $dateEnd['Y'];
			break;
		}
		
		switch($paramFilter_select){
			// case 'any': break;
			case 'EMPTY': 
				return $q->where($table.'.'.$field,'=',0); 
			break;
			case 'NOT EMPTY': 
				return $q->where($table.'.'.$field,'<>',0); 
			break;
			case '=': 
				return $q->where($table.'.'.$field,'=',$timestampStart); 
			break;
			case '!=': 
				return $q->where($table.'.'.$field,'<>',$timestampStart); 
			break;
			case '<': 
				return $q->where($table.'.'.$field,'<',$timestampStart); 
			break;
			case '<=': 
				return $q->where($table.'.'.$field,'<=',$timestampStart); 
			break;
			case '>': 
				return $q->where($table.'.'.$field,'>',$timestampStart); 
			break;
			case '>=': 
				return $q->where($table.'.'.$field,'>=',$timestampStart); 
			break;
			case 'BETWEEN':
				return $q->whereBetween($table.'.'.$field,[$timestampStart,$timestampEnd]); 
			break;
			case 'NOT BETWEEN':
				return $q->whereNotBetween($table.'.'.$field,[$timestampStart,$timestampEnd]); 
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
		$paramFilter_select = BaseUtility::_POSTuc('form1Apply','form1Field_'.$field,'');
		$paramFilter_value = BaseUtility::_POSTuc('form1Apply','form1FieldValue_'.$field,'');
		
		if(is_array($paramFilter_value['start'])){
			$paramFilter_value['start']['H'] = stripslashes(htmlspecialchars($paramFilter_value['start']['H']));
			$paramFilter_value['start']['i'] = stripslashes(htmlspecialchars($paramFilter_value['start']['i']));
			$paramFilter_value['start']['s'] = stripslashes(htmlspecialchars($paramFilter_value['start']['s']));
			$paramFilter_value['start']['d'] = stripslashes(htmlspecialchars($paramFilter_value['start']['d']));
			$paramFilter_value['start']['m'] = stripslashes(htmlspecialchars($paramFilter_value['start']['m']));
			$paramFilter_value['start']['Y'] = stripslashes(htmlspecialchars($paramFilter_value['start']['Y']));
		}
		if(is_array($paramFilter_value['end'])){
			$paramFilter_value['end']['H'] = stripslashes(htmlspecialchars($paramFilter_value['end']['H']));
			$paramFilter_value['end']['i'] = stripslashes(htmlspecialchars($paramFilter_value['end']['i']));
			$paramFilter_value['end']['s'] = stripslashes(htmlspecialchars($paramFilter_value['end']['s']));
			$paramFilter_value['end']['d'] = stripslashes(htmlspecialchars($paramFilter_value['end']['d']));
			$paramFilter_value['end']['m'] = stripslashes(htmlspecialchars($paramFilter_value['end']['m']));
			$paramFilter_value['end']['Y'] = stripslashes(htmlspecialchars($paramFilter_value['end']['Y']));
		}
		
		$disabled_1 = 0;
		$disabled_2 = 0;
		$disabled_3 = 0;
		$disabled_4 = 0;
		$disabled_5 = 0;
		$disabled_6 = 0;

		$conf = end(explode("\\",$config['AirTable.ClassWithSub']));
		switch(strtolower($conf)){
			default:
			case 'date':
				$disabled_1 = 1;
				$disabled_2 = 1;
				$disabled_3 = 1;
				$paramFilter_input = htmlspecialchars($paramFilter_input);
				$paramFilter_input = stripslashes($paramFilter_input);
			break;
			case 'datetime':
				$disabled_3 = 1;
			break;
			case 'time':
				$disabled_3 = 1;
				$disabled_4 = 1;
				$disabled_5 = 1;
				$disabled_6 = 1;
			break;
			case 'timesec':
				$disabled_4 = 1;
				$disabled_5 = 1;
				$disabled_6 = 1;
			break;
			case 'year':
				$disabled_1 = 1;
				$disabled_2 = 1;
				$disabled_3 = 1;
				$disabled_4 = 1;
				$disabled_5 = 1;
			break;
		}

		return '
			<table class="table table-striped table-bordered table-hover" style="margin-bottom: 0;">
			<tr class="active">
				<th colspan="7">Перевести на "FROM_UNIXTIME(date_create, \'%*\') = ?"</th>
			</tr>
			<tr class="active">
				<th>Условие</th>
				<th>Час</th>
				<th>Мин.</th>
				<th>Сек.</th>
				<th>Ден.</th>
				<th>Мес.</th>
				<th>Год</th>
			</tr>
			<tr>
				<td>
				<select name="form1Field_'.$field.'" class="form-control btn-sm" style="margin-bottom: 5px;">
					<option value="" '.($paramFilter_select == ''?'selected':'').'>Любое значение</option>
					<option value="EMPTY" '.($paramFilter_select == 'EMPTY'?'selected':'').'>Не заполнено (значение не указана)</option>
					<option value="NOT EMPTY" '.($paramFilter_select == 'NOT EMPTY'?'selected':'').'>Заполнено (значение указано)</option>
					<option value="=" '.($paramFilter_select == '='?'selected':'').'>=</option>
					<option value="!=" '.($paramFilter_select == '!='?'selected':'').'>!=</option>
					<option value="<" '.($paramFilter_select == '<'?'selected':'').'>&lt;</option>
					<option value="<=" '.($paramFilter_select == '<='?'selected':'').'>&lt;=</option>
					<option value=">" '.($paramFilter_select == '>'?'selected':'').'>&gt;</option>
					<option value=">=" '.($paramFilter_select == '>='?'selected':'').'>&gt;=</option>
					<option value="BETWEEN" '.($paramFilter_select == 'BETWEEN'?'selected':'').'>BETWEEN (стартовое значение, конечное значение)</option>
					<option value="NOT BETWEEN" '.($paramFilter_select == 'NOT BETWEEN'?'selected':'').'>NOT BETWEEN (стартовое значение, конечное значение)</option>
				</select>
				</td>
				<td><input name="form1FieldValue_'.$field.'[start][H]" value="'.$paramFilter_value['start']['H'].'" maxlength="2" class="form-control btn-sm" '.(($disabled_1 == 1)?'disabled':'').' placeholder="23" style="min-width: auto;"></td>
				<td><input name="form1FieldValue_'.$field.'[start][i]" value="'.$paramFilter_value['start']['i'].'" maxlength="2" class="form-control btn-sm" '.(($disabled_2 == 1)?'disabled':'').' placeholder="59" style="min-width: auto;"></td>
				<td><input name="form1FieldValue_'.$field.'[start][s]" value="'.$paramFilter_value['start']['s'].'" maxlength="2" class="form-control btn-sm" '.(($disabled_3 == 1)?'disabled':'').' placeholder="59" style="min-width: auto;"></td>
				<td><input name="form1FieldValue_'.$field.'[start][d]" value="'.$paramFilter_value['start']['d'].'" maxlength="2" class="form-control btn-sm" '.(($disabled_4 == 1)?'disabled':'').' placeholder="08" style="min-width: auto;"></td>
				<td><input name="form1FieldValue_'.$field.'[start][m]" value="'.$paramFilter_value['start']['m'].'" maxlength="2" class="form-control btn-sm" '.(($disabled_5 == 1)?'disabled':'').' placeholder="06" style="min-width: auto;"></td>
				<td><input name="form1FieldValue_'.$field.'[start][Y]" value="'.$paramFilter_value['start']['Y'].'" maxlength="4" class="form-control btn-sm" '.(($disabled_6 == 1)?'disabled':'').' placeholder="1989" style="min-width: auto;"></td>
			</tr>
			<tr>
				<td></td>
				<td><input name="form1FieldValue_'.$field.'[end][H]" value="'.$paramFilter_value['end']['H'].'" maxlength="2" class="form-control btn-sm" '.(($disabled_1 == 1)?'disabled':'').' placeholder="23" style="min-width: auto;"></td>
				<td><input name="form1FieldValue_'.$field.'[end][i]" value="'.$paramFilter_value['end']['i'].'" maxlength="2" class="form-control btn-sm" '.(($disabled_2 == 1)?'disabled':'').' placeholder="59" style="min-width: auto;"></td>
				<td><input name="form1FieldValue_'.$field.'[end][s]" value="'.$paramFilter_value['end']['s'].'" maxlength="2" class="form-control btn-sm" '.(($disabled_3 == 1)?'disabled':'').' placeholder="59" style="min-width: auto;"></td>
				<td><input name="form1FieldValue_'.$field.'[end][d]" value="'.$paramFilter_value['end']['d'].'" maxlength="2" class="form-control btn-sm" '.(($disabled_4 == 1)?'disabled':'').' placeholder="08" style="min-width: auto;"></td>
				<td><input name="form1FieldValue_'.$field.'[end][m]" value="'.$paramFilter_value['end']['m'].'" maxlength="2" class="form-control btn-sm" '.(($disabled_5 == 1)?'disabled':'').' placeholder="06" style="min-width: auto;"></td>
				<td><input name="form1FieldValue_'.$field.'[end][Y]" value="'.$paramFilter_value['end']['Y'].'" maxlength="4" class="form-control btn-sm" '.(($disabled_6 == 1)?'disabled':'').' placeholder="1989" style="min-width: auto;"></td>
			</tr>
			</table>
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
		$conf = end(explode("\\",$config['AirTable.ClassWithSub']));
		switch(strtolower($conf)){
			default:
			case 'date': 		$d = date('d-m-Y',$row[$field]); 						break;
			case 'datetime': 	$d = date('H:i d-m-Y',$row[$field]); 					break;
			case 'time': 		$d = date("H:i", mktime(0, 0, $row[$field])); 			break;
			case 'timesec': 	$d = date("H:i:s", mktime(0, 0, $row[$field])); 		break;
			case 'year': 		$d = $row[$field]; 										break;
		}
		if($row[$field] > 0){
			return '<td nowrap style="vertical-align: top;"><a href="'.$backendLink.'">'.$d.'</a></td>';
		} else {
			return '<td nowrap style="vertical-align: top;"><a href="'.$backendLink.'"><code style="'.STYLE_EMPTY_FIELD.'">Заполнить</code></a></td>';
		}
	}
}
