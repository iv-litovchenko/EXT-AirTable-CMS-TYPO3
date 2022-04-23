<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Utility\BaseUtility;

// <Базовый класс для поля>
abstract class AbstractField
{
    const REQPREFIXCURRENTFIELD = 'prop_';
    const REQPREFIXCURRENTFIELDATTR = 'attr_';
	
	const EXPORT = true;
	const IMPORT = true;
	const SECTION = 'dataFields';
	const TABDEFAULT = 7; // props
	
    /**
     * @return array
     */
    public static function databaseDefinitions($model, $table, $field)
    {
        return [$table=>[
			$field => 'varchar(255) DEFAULT \'\' NOT NULL'
		]];
    }
	
    /**
     * @modify array $GLOBALS['TCA']
     */
    public static function buildConfiguration($model, $table, $field)
    {
		$databaseDefinitionsAr = get_called_class()::databaseDefinitions($model,$table,$field);
		$label = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Label');
		if($label == ''){
			$label = '[Название колонки не задано]';
		}
		
		$annotationFieldSub = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field',1);
		$GLOBALS['TCA'][$table]['columns'][$field]['AirTable.Class'] = get_called_class();
		$GLOBALS['TCA'][$table]['columns'][$field]['AirTable.ClassWithSub'] = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationFieldSub;
		// $GLOBALS['TCA'][$table]['columns'][$field]['AirTable.MySql'] = $databaseDefinitionsAr[$table][$field];
		$GLOBALS['TCA'][$table]['columns'][$field]['label'] = $label;
		
		if(BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\LiveSearch') == 1){
			$GLOBALS['TCA'][$table]['ctrl']['searchFields'] .= ','.$field;
		}
		if(BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Description') != ''){
			$GLOBALS['TCA'][$table]['columns'][$field]['description'] = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Description');
		}
		if(BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Required') == 1){
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['eval'] = 'required';
		}
		if(BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\ReadOnly') == 1){
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['readOnly'] = 1;
		}
		if(BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Placeholder') != ''){
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['placeholder'] = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Placeholder');
		}
		if(BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Default') != ''){
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['default'] = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Default');
		}
		if(BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\OnChangeReload') == 1){
			$GLOBALS['TCA'][$table]['columns'][$field]['onChange'] = 'reload';
		}
		if(!empty(BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\DisplayCond'))){
			$GLOBALS['TCA'][$table]['columns'][$field]['displayCond'] = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\DisplayCond');
		}
		
		// Todo - пока нет поддержки для редактирования записей в корне для групп пользователей "не админов"
		if(BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\Exclude') == 1){
			$GLOBALS['TCA'][$table]['columns'][$field]['exclude'] = 1;
		}
		
		// Eav (tx_data, tx_data_category) - стандартные поля DisplayCond
		// Для TCEForm
		$annotationFieldDataTypeConditionUse = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\DataTypeConditionUse');
		$annotationFieldDataTypeConditionUse = explode(',',$annotationFieldDataTypeConditionUse);
		if(in_array($table,$annotationFieldDataTypeConditionUse)){
			$GLOBALS['TCA'][$table]['columns'][$field]['displayCond'] = 'USER:'.$model.'->isVisibleDisplayConditionMatcher:'.$table.':'.$field;
		}
		
	}
	
    /**
     * @return array errors
     */
    public static function buildConfigurationCheck($model, $table, $field)
    {
		// Список ошибок
		$arError = [];
		
		// Проверка на наличие символов в верхнем регистре...
		if(preg_match('/[A-Z]$/', $field)){
			$arError['uppercase'] = '<li>Поле (свойство) содержит символы в верхнем регистре <code>'.$field.'</code></li>';
		}
		
		// Eav attr
		$annotationEavAttr = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\EavAttr');
		if($annotationEavAttr == true){
			// if(strlen($field)>64) {
			// 	$arError['strlen_64'] = '<li>Длинна название поля (свойство) превышает 64 символа <code>'.$field.'</code></li>';
			// }
			
			// Eav attr
			#$annotationEavAttrExt = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\EavAttrExt');
			#$extTest = explode(".",$field);
			
			// Класс колонки
			$annotationField = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field');
			$fieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationField;
			$reqPrefix = $fieldClass::REQPREFIXCURRENTFIELDATTR; // attr_
			if(!preg_match('/^'.$reqPrefix.'entity_(.*)$/is',$field,$match)){	
				$arError['eav_error'] = '<li>Формат ключа атрибута не соответствует шаблону: <code>'.$reqPrefix.'entity_[name]_ext_[name]_attr_[name]</code></li>';
				
			} # else {
				#if(!in_array(trim($extTest[0].'.'.$extTest[1]),['entity.pages','entity.tt_content','entity.tx_data'])){
				#	$arError['eav_error_1'] = '<li>Не найдена сущность для атрибута (допустипые значения: <code>[entity.pages || entity.tt_content || entity.tx_data].[ext.***].[attr.***]</code>) - 1 значение</li>';
				#}
				#if(trim($extTest[3]) != $annotationEavAttrExt){
				#	$arError['eav_error_2'] = '<li>Не правильно указано название расширения <code>[entity.***].[ext.***].[attr.***]</code>, при регистрации атрибута - 2 значение</li>';
				#}
				#$arexTest = explode(".",$field);
				#if(trim($arexTest[4]) != '_attr_'){
				#	// $arError['eav_error_3'] = '<li>Название атрибута не содержит префикс "attr." <code>[entity.***].[ext.***].[attr.***]</code>, - 3 значение</li>';
				#	// $arError['eav_error_3'] = '<li>Название атрибута не содержит префикс "attr." <code>[ext_***]_[attr_***]</code></li>';
				#}
			#}
		
		// Обычные
		} else {
			
			// Класс колонки
			$annotationField = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field');
			$fieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationField;
			
			$sections = ['baseFields','dataFields','mediaFields','relationalFields'];
			switch($fieldClass::SECTION){
				case 'baseFields':
					unset($sections[0]);
				break;
				case 'dataFields':
					unset($sections[1]);
				break;
				case 'mediaFields':
					unset($sections[2]);
				case 'relationalFields':
					unset($sections[3]);
				break;
			}
			foreach($sections as $k => $v){
				if(isset($model::$TYPO3[$v][$field])){
					$arError['req_prefix'] = '<li>Поле необходимо регестрировать в секции <code>['.$fieldClass::SECTION.']</code>!</li>';
				}
			}
			
			if(!preg_match('/^'.$fieldClass::REQPREFIXCURRENTFIELD.'(.*)/is',$field)){
				$arError['req_prefix'] = '<li>Поле (свойство) не содержит обязательный префикс <code>'.$fieldClass::REQPREFIXCURRENTFIELD.'</code></li>';
			}
			
			if(strlen($field)>48) {
				$arError['strlen_48'] = '<li>Длинна название поля (свойства) превышает 48 символов <code>'.$field.'</code></li>';
			}
			
			// Проверка позиции
			$annotationPosition = BaseUtility::getClassFieldAnnotationValueNew($model, $field, 'AirTable\Field\Position');
			$userRTypes = $model::baseRTypes();
			$userTabs = $model::baseTabs();
			
			// Проверяем при параметре "RType"
			if(BaseUtility::hasSpecialField($model,'RType') == true && !is_array($annotationPosition)){
				$arError['position_rtype'] = '<li>Поле (свойство) содержит ошибки в настройках параметра 
				<code>"@AirTable\Field\Position:'.htmlspecialchars('<struct|tab|num>').'"</code>.
				Параметр не определен при включенном трейте <code>"RType"</code>.</li>';
				
			}elseif(is_array($annotationPosition)){
				foreach($annotationPosition as $kA => $vA){
					$tab = current(explode(',',$vA));
					$num = end(explode(',',$vA));
					//if($kA == '*'){
					//	continue;
					//} else {
						if($kA != '*' && !isset($userRTypes[$kA])){
							$arError['position_struct_'.$kA] = '<li>Поле (свойство) содержит ошибки в настройках параметра 
							<code>"@AirTable\Field\Position:'.htmlspecialchars('<struct|tab|num>').'"</code>.
							Указанная структура <code>"'.$kA.'"</code> <!--(тип записи)--> не определена в модели!</li>';
						}
						if(!isset($userTabs[$tab]) && $tab != 'extended'){
							$arError['position_tab_'.$tab] = '<li>Поле (свойство) содержит ошибки в настройках параметра 
							<code>"@AirTable\Field\Position:'.htmlspecialchars('<struct|tab|num>').'"</code>.
							Указанная вкладка <code>"'.$tab.'"</code> <!--(таб)--> не определена в модели!</li>';
						}
					//}
				}
			}
		}
		
		return $arError;
	}
	
	// listController func
	public static function listControllerSqlBuilder(&$obj, &$q, $table, $field, $config){
		return $q;
	}
	
	// listController func
	public static function listControllerSqlBuilderOrder(&$obj, $table, $field, $config){
		return true;
	}
	
	// listController func
	public static function listControllerHtmlFilter(&$obj, $table, $field, $config, &$uriBuilder){
		return '<input name="" class="form-control btn-sm" value="" disabled>';
	}
	
	// listController func
	public static function listControllerHtmlTh(&$obj, $table, $field, $config){
		return '<th>TH.'.$field.'</th>';
	}
	
	// listController func
	public static function listControllerHtmlTd(&$obj, $table, $field, $config, $row, &$uriBuilder){
		return '<td>TD.'.$field.'</td>';
	}
	
	// exportController
	public static function exportControllerRow(&$rowData, $class, $table, $field, $config, $row){
		$rowData[$field] = $row[$field];
	}
	
	// importController
	public static function importControllerRow(&$rowData, $class, $table, $field, $config, $row){
		$rowData[$field] = $row[$field];
	}
}
