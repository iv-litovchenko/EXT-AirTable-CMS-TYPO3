<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\AbstractField;
use Litovchenko\AirTable\Utility\BaseUtility;

class Input extends AbstractField
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 	=> 'BackendField',
		'name' 		=> 'Строка',
		'subTypes' 	=> 'Int,Number,Float,Link,Color,Email,Password,InvisibleInt,Invisible',
		'incEav' 	=> 1,
		'_propertyAnnotations' => [
			'size' => 'string',
			'max' => 'string'
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
			case 'float':
				return [$table=>[
					$field => 'float(2) DEFAULT \'0\' NOT NULL'
				]];
			break;
			case 'int':
			case 'passthroughint':
			case 'invisibleint':
				return [$table=>[
					$field => 'int(11) DEFAULT \'0\' NOT NULL'
				]];
			break;
			case 'number':
				return [$table=>[
					$field => 'tinyint(4) unsigned DEFAULT \'0\' NOT NULL'
				]];
			break;
			case 'link':
			case 'slug':
				return [$table=>[
					$field => 'varchar(2048) DEFAULT \'\' NOT NULL'
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
		
		$RenderType = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field',true);
		switch(strtolower($RenderType)){
			default:
			case 'string':
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] .= ',trim';
			break;
			case 'alpha': // только символы
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = 'trim,alpha';
			break;
			case 'alphanum': // только символы и цифры
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = 'trim,alphanum';
			break;
			case 'alphanumx': // только символы, цифры, подчеркивание и тире
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = 'trim,alphanumx';
			break;
			case 'float': // Число дробное (допускается отрицательное)
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = 'double2';
			break;
			case 'int': // Число целое (допускается отрицательное)
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = 'int';
			break;
			case 'number': // Число целое
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = 'num';
			break;
			case 'link': // Ссылка
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['renderType'] = 'inputLink';
			break;
			case 'email': // Электронный адрес
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = 'trim,email';
			break;
			case 'password': // Пароль
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = 'trim,password';
			break;
			case 'color': // Цвет
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'input';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['renderType'] = 'colorpicker';
			break;
			case 'slug': // Slug-значение
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'slug';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] .= ',uniqueInPid';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['fallbackCharacter'] = '-';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['prependSlash'] = true;
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['generatorOptions']['fields'] = ['uid','title'];
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['generatorOptions']['fieldSeparator'] = '-';
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['generatorOptions']['prefixParentPageSlug'] = true;
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['generatorOptions']['replacements'] = ['/' => ''];
			break;
			
			case 'passthrough': // Сквозное поле (тип Varchar)
			case 'invisible':
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'passthrough';
			break;
			case 'passthroughint': // Сквозное поле (тип Int)
			case 'invisibleint':
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'passthrough';
			break;
		}
		
		// Значение по умолчанию
		$default = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Default');
		if(!empty($default)){
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['default'] = $default;
		}
		
		// Max
		$max = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Max');
		if(!empty($max)){
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['max'] = $max;
		}
		
		// Size
		$size = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Size');
		if(!empty($size)){
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['size'] = $size;
		}
	}
	
	// listController func
	public static function listControllerSqlBuilder(&$obj, &$q, $table, $field, $config){
		$confMain = $config['AirTable.Class'];
		$conf = end(explode("\\",$config['AirTable.ClassWithSub']));
		if($confMain == 'Litovchenko\AirTable\Domain\Model\Fields\SpecialUid'
		or $confMain == 'Litovchenko\AirTable\Domain\Model\Fields\SpecialPid'
		or strtolower($conf) == "int"
		or strtolower($conf) == "passthroughint"
		or strtolower($conf) == "number"
		or strtolower($conf) == "float"){
			// $q->addSelect($table.'.'.$field);
			$paramFilter_select = BaseUtility::_POSTuc('form1Apply','form1Field_'.$field,'');
			$paramFilter_input = BaseUtility::_POSTuc('form1Apply','form1FieldValue_'.$field,'');
			$paramFilter_input = trim($paramFilter_input);
			switch($paramFilter_select){
				// case 'any': break;
				case 'EMPTY': 
					return $q->where($table.'.'.$field,'=',0); 
				break;
				case 'NOT EMPTY': 
					return $q->where($table.'.'.$field,'<>',0); 
				break;
				case '=': 
					return $q->where($table.'.'.$field,'=',$paramFilter_input); 
				break;
				case '!=': 
					return $q->where($table.'.'.$field,'<>',$paramFilter_input); 
				break;
				case '<': 
					return $q->where($table.'.'.$field,'<',$paramFilter_input); 
				break;
				case '<=': 
					return $q->where($table.'.'.$field,'<=',$paramFilter_input); 
				break;
				case '>': 
					return $q->where($table.'.'.$field,'>',$paramFilter_input); 
				break;
				case '>=': 
					return $q->where($table.'.'.$field,'>=',$paramFilter_input); 
				break;
				case 'IN': 
					$array = explode(',',$paramFilter_input);
					return $q->whereIn($table.'.'.$field,$array); 
				break;
				case 'NOT IN': 
					$array = explode(',',$paramFilter_input);
					return $q->whereNotIn($table.'.'.$field,$array); 
				break;
				case 'BETWEEN': 
					$array = explode(',', $paramFilter_input);
					// $array[0] start
					// $array[1] end
					return $q->whereBetween($table.'.'.$field,[$array[0],$array[1]]); 
				break;
				case 'NOT BETWEEN': 
					$array = explode(',', $paramFilter_input);
					// $array[0] start
					// $array[1] end
					return $q->whereNotBetween($table.'.'.$field,[$array[0],$array[1]]); 
				break;
			}
			return $q;
		} else {
			// $q->addSelect($table.'.'.$field);
			$paramFilter_select = BaseUtility::_POSTuc('form1Apply','form1Field_'.$field,'');
			$paramFilter_input = BaseUtility::_POSTuc('form1Apply','form1FieldValue_'.$field,'');
			$paramFilter_input = trim($paramFilter_input);
			switch($paramFilter_select){
				// case 'any': break;
				case 'EMPTY': 		
					return $q->where($table.'.'.$field,'=',''); 
				break;
				case 'NOT EMPTY': 	
					return $q->where($table.'.'.$field,'<>',''); 
				break;
				case '=': 			
					return $q->where($table.'.'.$field,'=',$paramFilter_input); 
				break;
				case '!=': 			
					return $q->where($table.'.'.$field,'<>',$paramFilter_input); 
				break;
				case 'LIKE': 		
					return $q->where($table.'.'.$field,'like',$paramFilter_input); 
				break;
				case 'NOT LIKE':	
					return $q->where($table.'.'.$field,'not like',$paramFilter_input); 
				break;
				case 'REGEXP': 		
				return $q->where($table.'.'.$field,'regexp',$paramFilter_input); 
				break;
				case 'NOT REGEXP': 	
					return $q->where($table.'.'.$field,'not regexp',$paramFilter_input); 
				break;
			}
			return $q;
		}
	}
	
	// listController func
	public static function listControllerSqlBuilderOrder(){
		return true;
	}
	
	// listController func
	public static function listControllerHtmlFilter(&$obj, $table, $field, $config, &$uriBuilder){
		$confMain = $config['AirTable.Class'];
		$conf = end(explode("\\",$config['AirTable.ClassWithSub']));
		if($confMain == 'Litovchenko\AirTable\Domain\Model\Fields\SpecialUid'
		or $confMain == 'Litovchenko\AirTable\Domain\Model\Fields\SpecialPid'
		or strtolower($conf) == "int"
		or strtolower($conf) == "passthroughint"
		or strtolower($conf) == "number"
		or strtolower($conf) == "float"){
			$paramFilter_select = BaseUtility::_POSTuc('form1Apply','form1Field_'.$field,'');
			$paramFilter_input = BaseUtility::_POSTuc('form1Apply','form1FieldValue_'.$field,'');
			$paramFilter_input = htmlspecialchars($paramFilter_input);
			$paramFilter_input = stripslashes($paramFilter_input);
			return '
				<table width="100%" class="" style="margin-bottom: 0;">
				<tr>
				<td width="30%">
					<select name="form1Field_'.$field.'" class="form-control btn-sm">
						<option value="" '.($paramFilter_select == ''?'selected':'').'>Любое значение</option>
						<option value="EMPTY" '.($paramFilter_select == 'EMPTY'?'selected':'').'>Не заполнено (ноль)</option>
						<option value="NOT EMPTY" '.($paramFilter_select == 'NOT EMPTY'?'selected':'').'>Заполнено (не ноль)</option>
						<option value="=" '.($paramFilter_select == '='?'selected':'').'>=</option>
						<option value="!=" '.($paramFilter_select == '!='?'selected':'').'>!=</option>
						<option value="<" '.($paramFilter_select == '<'?'selected':'').'>&lt;</option>
						<option value="<=" '.($paramFilter_select == '<='?'selected':'').'>&lt;=</option>
						<option value=">" '.($paramFilter_select == '>'?'selected':'').'>&gt;</option>
						<option value=">=" '.($paramFilter_select == '>='?'selected':'').'>&gt;=</option>
						<option value="IN" '.($paramFilter_select == 'IN'?'selected':'').'>IN (значения через ",")</option>
						<option value="NOT IN" '.($paramFilter_select == 'NOT IN'?'selected':'').'>NOT IN (значения через ",")</option>
						<option value="BETWEEN" '.($paramFilter_select == 'BETWEEN'?'selected':'').'>BETWEEN (два значения через ",")</option>
						<option value="NOT BETWEEN" '.($paramFilter_select == 'NOT BETWEEN'?'selected':'').'>NOT BETWEEN (два значения через ",")</option>
					</select>
				</td>
				<td width="70%" style="padding-left: 5px;">
					<input name="form1FieldValue_'.$field.'" class="form-control btn-sm" value="'.$paramFilter_input.'" placeholder="Введите значение">
				</td>
				</tr>
				</table>
			';
		} else {
			$paramFilter_select = BaseUtility::_POSTuc('form1Apply','form1Field_'.$field,'');
			$paramFilter_input = BaseUtility::_POSTuc('form1Apply','form1FieldValue_'.$field,'');
			$paramFilter_input = htmlspecialchars($paramFilter_input);
			$paramFilter_input = stripslashes($paramFilter_input);
			return '
				<table width="100%" class="" style="margin-bottom: 0;">
				<tr>
				<td width="30%">
					<select name="form1Field_'.$field.'" class="form-control btn-sm">
						<option value="" '.($paramFilter_select == ''?'selected':'').'>Любое значение</option>
						<option value="EMPTY" '.($paramFilter_select == 'EMPTY'?'selected':'').'>Не заполнено (пустое)</option>
						<option value="NOT EMPTY" '.($paramFilter_select == 'NOT EMPTY'?'selected':'').'>Заполнено (не пустое)</option>
						<option value="=" '.($paramFilter_select == '='?'selected':'').'>=</option>
						<option value="!=" '.($paramFilter_select == '!='?'selected':'').'>!=</option>
						<option value="LIKE" '.($paramFilter_select == 'LIKE'?'selected':'').'>LIKE</option>
						<option value="NOT LIKE" '.($paramFilter_select == 'NOT LIKE'?'selected':'').'>NOT LIKE</option>
						<option value="REGEXP" '.($paramFilter_select == 'REGEXP'?'selected':'').'>REGEXP</option>
						<option value="NOT REGEXP" '.($paramFilter_select == 'NOT REGEXP'?'selected':'').'>NOT REGEXP</option>
					</select>
				</td>
				<td width="70%" style="padding-left: 5px;">
					<input name="form1FieldValue_'.$field.'" class="form-control btn-sm" value="'.$paramFilter_input.'" placeholder="Введите значение">
				</td>
				</tr>
				</table>
			';
		}
	}
	
	// listController func
	public static function listControllerHtmlTh(&$obj, $table, $field, $config){
		$debugStr = '';
		if($GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] == true){
			$debugStr = '<code style="color: #b4b2b2;">'.BaseUtility::getClassNameWithoutNamespace($config['AirTable.Class']).'.'.$field.'</code>';
		}
		$checkedAndDisabled = '';
		if($field == 'uid' || $field == 'pid' || $field == 'RType'){
			$checkedAndDisabled = ' checked disabled ';
		}
		// Ранее было padding-left: 32px;
		return '<th nowrap style="vertical-align: top; padding: 2px; border-top: none;">
			<div style="position: relative;">
			<label class="btn btn-default btn-sm" style="width: 100%; margin: 0; padding: 0 5px 0 5px; text-align: left; cursor: pointer;">
				<input type="checkbox" class="btn btn-default btn-sm" style="margin-top: 0px;" name="field_uid" value="'.$field.'" onClick="toggleFields(this,\'field_uid\');" '.$checkedAndDisabled.' disabled_ZZZ>
				<b>'.$GLOBALS['LANG']->sL($config['label']).'</b>
			</label><br />
			'.$debugStr.'
			</div>
		</th>';
		
		// Старый вариант (немного другое оформление)
		/*
		return '<th nowrap style="border-top: none;">
			<div style="position: relative; margin-left: 26px;">
			<label class="btn btn-default btn-sm" style="position: absolute; min-width: 24px; top: -4px; left: -30px; margin: 0; padding: 0 5px 0 5px; cursor: pointer;">
				<input type="checkbox" class="btn btn-default btn-sm" style="margin-top: 0px;" name="field_uid" value="'.$field.'" onClick="toggleFields(this,\'field_uid\');" '.$checkedAndDisabled.' disabled_ZZZ>
			</label>
			'.$GLOBALS['LANG']->sL($config['label']).'<br />
			'.$debugStr.'
			</div>
		</th>';
		*/
		
		// return '<th nowrap>'.$config['label'].'</th>';
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
		if(trim($row[$field]) == ''){
			$rowValue = '<code style="'.STYLE_EMPTY_FIELD.'">Заполнить</code>';
		} else {
			$rowValue = htmlspecialchars($row[$field]);
		}
		#if($field == 'uid'){
		#	return '<td nowrap style="vertical-align: top;"><a href="'.$backendLink.'">'.$rowValue.'</a></td>';
		#} else {
			return '<td nowrap style="vertical-align: top;"><a href="'.$backendLink.'">'.$rowValue.'</a></td>';
		#}
	}
}
