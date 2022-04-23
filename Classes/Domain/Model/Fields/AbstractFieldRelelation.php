<?php
namespace Litovchenko\AirTable\Domain\Model\Fields;
use Litovchenko\AirTable\Domain\Model\Fields\AbstractField;
use Litovchenko\AirTable\Utility\BaseUtility;

// Базовый класс для поля связи между таблицами>
abstract class AbstractFieldRelelation extends AbstractField
{
	const REQPREFIXCURRENTFIELD = 'propref_';
    const REQPREFIXCURRENTFIELDATTR = 'attrref_';
	const REQPREFIXFOREIGNFIELD = 'proprefinv_';
    const REQPREFIXFOREIGNCURRENTFIELDATTR = 'attrrefinv_';
	
	const EXPORT = true;
	const IMPORT = false;
	const SECTION = 'relationalFields';
	const TABDEFAULT = 9; // rels
	
    /**
     * @return string
     */
    public static function databaseDefinitions($model, $table, $field)
    {
        return [];
    }
	
    /**
     * @modify array $GLOBALS['TCA']
     */
    public static function buildConfiguration($model, $table, $field)
    {
		parent::buildConfiguration($model, $table, $field);
		
		// Filter
		/*
		if($filters = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\ForeignFilter'))
		{
			/ *
			Array
			(
				[1\UserFunc] => Litovchenko\AirTable\Domain\Model\Tests\ExampleTable->doFilter
				[1\UserFunc\Parameter\AllowedPagePid] => 10
				[1\UserFunc\Parameter\AllowedPageDoktype] => 1
				[2\UserFunc] => Litovchenko\AirTable\Domain\Model\Tests\ExampleTable->doFilter2
				[2\UserFunc\Parameter\AllowedPagePid2] => 10
				[2\UserFunc\Parameter\AllowedPageDoktype2] => 1
			)			
			* /
			$filtersAr = [];
			foreach($filters as $k => $v){
				$realKey = current(explode("\UserFunc",$k));
				if(strstr($k,'\UserFunc\Parameter\\')){
					$realKey2 = end(explode("\UserFunc\Parameter\\",$k));
					$filtersAr[$realKey]['parameters'][$realKey2] = $v;
				} else {
					$filtersAr[$realKey]['userFunc'] = $v;
				}
			}
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['filter'] = $filtersAr;
			#$GLOBALS['TCA'][$table]['columns'][$field]['config']['filter'][] = [
				#'userFunc' => 'TYPO3\\CMS\\Core\\Resource\\Filter\\FileExtensionFilter->filterInlineChildren',
				#'parameters' => [
				#	'allowedFileExtensions' => $allowedFileExtensions,
				#	'disallowedFileExtensions' => $disallowedFileExtensions
				#]
			#];
		}
		*/
		
		// ForeignWhere (сделать только для tx_data!)
		if($fTw = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\ForeignWhere'))
		{
			$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_table_where'] = $fTw;
		}
		
		// ForeignDefaults (сделать только для tx_data!)
		if($fRd = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\ForeignWhere'))
		{
			foreach($fRd as $k => $v){
				$GLOBALS['TCA'][$table]['columns'][$field]['config']['foreign_record_defaults'][$k] = $v;
			}
		}
		
	}
	
    /**
     * @modify array
     */
    public static function postBuildConfiguration($model, $table, $field)
    {
		// Внешние названия
		#$annotationForeignInverse = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\ForeignInverse');
		#if($annotationForeignInverse == true){
		#	$annotationField = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field');
		#	$fieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationField;
		#	if(class_exists($fieldClass.'_Inverse')){
		#		$_foreignModelName = self::getForeignModelName($model, $field); // foreign model
		#		$_foreignTableName = self::getForeignTableName($model, $field); // foreign table
		#		$_foreignFieldName = self::getForeignFieldName($model, $field); // foreign field
		#		parent::buildConfiguration($_foreignModelName, $_foreignTableName, $_foreignFieldName);
		#	}
		#}
	}
	
    /**
     * @return array
     */
    public static function buildConfigurationError($model, $table, $field, $arError = [])
    {
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['type'] = 'user';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['renderType'] = 'fieldErrorDisplay';
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['parameters']['field'] = $field;
		$GLOBALS['TCA'][$table]['columns'][$field]['config']['parameters']['message'] = $arError;
	}
	
    /**
     * @model
     * @field
     */
    public static function isDoubleRelation($model, $field)
    {
		$fM = self::getForeignModelName($model, $field);
		$fF = self::getForeignFieldName($model, $field);
		$annotationFieldForeign = BaseUtility::getClassFieldAnnotationValueNew($fM,$fF,'AirTable\Field');
		if($annotationFieldForeign != '' && $model != $fM){
			return true;
		}
		return false;
	}
	
    /**
     * @model
     * @field
     */
    public static function getForeignModelName($model, $field)
    {
		return BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\ForeignModel');
	}
	
    /**
     * @model
     * @field
     */
    public static function getForeignTableName($model, $field)
    {
		return BaseUtility::getTableNameFromClass(self::getForeignModelName($model,$field)); // table
	}
	
    /**
     * @model
     * @field
     */
    public static function getForeignFieldName($model, $field)
    {
		return BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\ForeignKey'); // field
	}
	
	// Проверка полей связи (для Eav)
	public static function withEavChech($model, $table, $field, $str)
	{
		// Eav attr
		$annotationEavAttr = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field\EavAttr');
		if($annotationEavAttr == true){
			$fieldArex = explode("_attr_",$field);
			return $fieldArex[0].'_attr_'.$str;
		} else {
			return $str;
		}
	}
	
	// Проверка полей связи
	public static function buildConfigurationCheck($model, $table, $field)
	{
		// Некоторые поля пропускаем!
		// Сделал "@AirTable\Field\DoNotCheck:<1>"
		#if($field == 'be_users_id'){
		#	return [];
		#}
		#if($field == 'category_items'){
		#	return [];
		#}
		#if($field == 'category_id'){
		#	return [];
		#}
		#if($field == 'parent_id'){
		#	return [];
		#}
		#if($field == 'tt_content_items'){
		#	return [];
		#}

		// Список ошибок
		$arError = parent::buildConfigurationCheck($model, $table, $field);
		
		// Внешние названия
		$_foreignModelName = self::getForeignModelName($model, $field); // foreign model
		$_foreignTableName = self::getForeignTableName($model, $field); // foreign table
		$_foreignFieldName = self::getForeignFieldName($model, $field); // foreign field
		
		// Класс колонки
		$annotationField = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field');
		$fieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationField;
		
		// Проверка 1 (основная): Модель не найдена
		if(!class_exists($_foreignModelName)){
			$arError[0] = '<li>Параметры связи: обязательный параметр <code>"foreignModel"</code> 
			содержит связь к несуществующей модели <code>"'.$_foreignModelName.'"</code></li>';
	
		// Проверка 2 (основная): Внешний ключ - не найден
		} elseif($model != $_foreignModelName && $_foreignFieldName == ''){
			$arError[0] = '<li>Параметры связи: обязательный параметр <code>"foreignKey"</code> 
			не задан!</li>';
		
		// Проверка 3 (основная): Внешний ключ - префикс
		// propref_ // proprefinv_ 
		// proprefinv_ // propref_
		} elseif($model != $_foreignModelName && !preg_match('/^'.$fieldClass::REQPREFIXFOREIGNFIELD.'/is',$_foreignFieldName)){
			$arError[0] = '<li>Параметры связи: внешний ключ <code>"foreignKey"</code> 
			не содержит обязательный префикс <code>"'.$fieldClass::REQPREFIXFOREIGNFIELD.'"</code></li>';
		
		} else {
		
			// Проверка 4: Если уже существует внешний ключ...
			// ForeignKey (собираем внешние ключи)
			// Первый ключ всегда прав! Последующие уже не правы!
			/*
			Array
			(
				[tx_airtableexamples_dm_exampletable1] => Array
					(
						[proprefinv_exampletable] => Array
							(
								[tx_airtableexamples_dm_exampletable.propref_exampletable1] => 1		-> ПРАВ!
								[tx_airtableexamples_dm_exampletable.propref_exampletable1c] => 1		-> НЕ ПРАВ!
							)

						[proprefinv_exampletableb] => Array
							(
								[tx_airtableexamples_dm_exampletable.propref_exampletable1b] => 1
							)

					)

				[tx_airtableexamples_dm_exampletable2] => Array
					(
						[proprefinv_exampletable] => Array
							(
								[tx_airtableexamples_dm_exampletable.propref_exampletable2] => 1
							)

						[proprefinv_exampletableb] => Array
							(
								[tx_airtableexamples_dm_exampletable.propref_exampletable2b] => 1
							)

						[proprefinv_exampletablec] => Array
							(
								[tx_airtableexamples_dm_exampletable.propref_exampletable2c] => 1
							)

						[proprefinv_exampletabled] => Array
							(
								[tx_airtableexamples_dm_exampletable.propref_exampletable2d] => 1
							)

					)

			*/
			if($_foreignTableName != ''){
				$GLOBALS['Litovchenko.AirTable.VarCache.ForeignFields'][$_foreignTableName][$_foreignFieldName][$table.'.'.$field] = 1;
			}
			if(count($GLOBALS['Litovchenko.AirTable.VarCache.ForeignFields'][$_foreignTableName][$_foreignFieldName]) > 1
			&& key($GLOBALS['Litovchenko.AirTable.VarCache.ForeignFields'][$_foreignTableName][$_foreignFieldName]) != $table.'.'.$field
			&& $_foreignTableName != ''){
				$arError[0] = '<li>Параметры связи: не допускается повторное использование внешнего ключа 
				<code>"@AirTable\Field\ForeignKey:"</code> : <code>"'.$_foreignFieldName.'"</code></li>';
				#print $table.'.'.$field;
				#print $arError[0];
				#print key($GLOBALS['Litovchenko.AirTable.VarCache.ForeignFields'][$_foreignTableName][$_foreignFieldName]).'<br />';
				#print "<pre>";
				#print_r($GLOBALS['Litovchenko.AirTable.VarCache.ForeignFields']);
			
			} else {
			
				// Проверка 5: Если есть двухсторонняя связь - проверяем
				$annotationForeignField = BaseUtility::getClassFieldAnnotationValueNew($model,$field,'AirTable\Field');
				if(preg_match('/_Inverse$/is',$annotationForeignField))
				{
					// Проверка А - ключ не найден...
					$annotationForeignField = BaseUtility::getClassFieldAnnotationValueNew($_foreignModelName,$_foreignFieldName,'AirTable\Field');
					if($annotationForeignField == ''){
						$arError[0] = '<li>Параметры связи (ошибка в установлении двухсторонней связи): 
						не правильно указаны внешняя модель <code>"foreignKey"</code>: <code>'.$_foreignModelName.'</code>
						и(или) ключ <code>"foreignKey"</code>: <code>'.$_foreignFieldName.'</code></li>';
					
					// Проверка Б - если название столбца отлично от значения столбца в основной таблице
					} else {
						$foreignFieldName = self::getForeignFieldName($_foreignModelName, $_foreignFieldName); // foreign field
						if($field != $foreignFieldName){
							$arError[0] = '<li>Параметры связи (ошибка в установлении двухсторонней связи): 
							название свойства <code>'.$field.'</code> не соответствует названию внешнего ключа <code>'.$foreignFieldName.'</code>
							внешней таблицы</li>';
						}
					}
				}
				
			}
		}
		
		#print $this->field."<br />";
		#print $currentPostfix."<br />";
		#print $foreignField."<br />";
		#print $foreignPostfix."<br />";
		
		return $arError;
	}
	
	// listController func
	public static function listControllerSqlBuilder(&$obj, &$q, $table, $field, $config)
	{
		
		// $q->addSelect($table.'.'.$field);
		$paramFilter_select = BaseUtility::_POSTuc('form1Apply','form1Field_'.$field,'');
		$paramFilter_input = BaseUtility::_POSTuc('form1Apply','form1FieldValue_'.$field,'');

		#switch($config['AirTable.Class']){
		#	case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_1To1':
		#	case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_1ToM':
		#	case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_MTo1':
		#	case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_MToM':
		#	case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_1To1_Inverse':
		#	case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_1ToM_Inverse':
		#	case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_MTo1_Inverse':
		#	case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_MToM_Inverse':
		#	case 'REL_Morph': // Todo
		/*
			// See: https://stackoverflow.com/questions/57713453/how-to-fix-error-please-use-wherehasmorph-for-morphto-relationships-when-us
			
			// Todo
			Comment::whereHasMorph('commentable', [Post::class, Video::class], function($query){
				$query->where('title', 'foo');
			})->get();
			
			select * from "comments"
			where (
			  (
				"commentable_type" = 'App\Post' and exists (
				  select * from "posts" 
				  where "comments"."commentable_id" = "posts"."id" and "title" = 'foo'
				)
			  ) or (
				"commentable_type" = 'App\Video' and exists (
				  select * from "videos" 
				  where "comments"."commentable_id" = "videos"."id" and "title" = 'foo'
				)
			  )
			)
		*/
				switch($paramFilter_select){
					default:
						// 
					break;
					case 'R1b': 
						$q = $q->has($field, '>', 1);
					break;
					case 'R0': 
					case 'R1': 
					case 'R2': 
					case 'R3': 
					case 'R4': 
					case 'R5': 
					case 'R5+':
						$countReference = preg_replace('/^R/is','',$paramFilter_select);
						if($countReference == '5+'){
							$countReference = preg_replace('/\+$/is','',$countReference);
							$q = $q->has($field, '>', intval($countReference));
						} else {
							$q = $q->has($field, '=', intval($countReference));
						}
					break;
					case 'REL_INCLUDE_uid':
						switch($config['AirTable.Class']){
							//+
							default:
								$q = $q->whereHas($field, function($q) use ($table, $paramFilter_input) {
									$q->whereIn('uid',  explode(',', $paramFilter_input));
								});
							break;
							//+
							case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_MToM':
							case 'Litovchenko\AirTable\Domain\Model\Fields\Eav':
								$q = $q->whereHas($field, function($q) use ($table, $paramFilter_input) {
									$q->whereIn('uid_foreign',  explode(',', $paramFilter_input));
								});
							break;
							//+
							case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_MToM_Inverse':
								$q = $q->whereHas($field, function($q) use ($table, $paramFilter_input) {
									$q->whereIn('uid_local',  explode(',', $paramFilter_input));
								});
							break;
						}
					break;
					case 'REL_EXCLUDE_uid':
						switch($config['AirTable.Class']){
							//+
							default:
								$q = $q->whereDoesntHave($field, function($q) use ($table, $paramFilter_input) {
									$q->whereIn('uid',  explode(',', $paramFilter_input));
								});
							break;
							//+
							case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_MToM':
							case 'Litovchenko\AirTable\Domain\Model\Fields\Eav':
								$q = $q->whereDoesntHave($field, function($q) use ($table, $paramFilter_input) {
									$q->whereIn('uid_foreign',  explode(',', $paramFilter_input));
								});
							break;
							//+
							case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_MToM_Inverse':
								$q = $q->whereDoesntHave($field, function($q) use ($table, $paramFilter_input) {
									$q->whereIn('uid_local',  explode(',', $paramFilter_input));
								});
							break;
						}
					break;
				}
			#break;
		#}
		return $q;
	}
	
	// listController func
	public static function listControllerSqlBuilderWith(&$obj, &$q, $table, $field, $config)
	{
		#switch($config['AirTable.Class']){
			#case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_MTo1':
			#	return $q;
			#break;
			#default:
				return $q->with($field); 
			#break;
		#}
	}
	
	// listController func
	public static function listControllerSqlBuilderOrder(&$obj, $table, $field, $config){
		switch($config['AirTable.Class']){
			case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_1To1_Inverse':
			case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_1ToM_Inverse':
			case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_MTo1':
				return true;
			break;
		}
		return false;
	}
	
	// listController func
	public static function listControllerHtmlFilter(&$obj, $table, $field, $config, &$uriBuilder){
		$paramFilter_select = BaseUtility::_POSTuc('form1Apply','form1Field_'.$field,'');
		$paramFilter_input = BaseUtility::_POSTuc('form1Apply','form1FieldValue_'.$field,'');

		/*
		Array
		(
			[route] => /AirTableTxairtabletabcontent/AirTableList0/
			[token] => eaf81a00a1b23613af6669b314391a90f3a0643b
			[paramExt] => air_table
			[paramSubDomain] => Examples6
			[paramClass] => Litovchenko\AirTable\Examples6\Model\ExampleDish
			[tx_airtable_airtabletxairtabletabcontent_airtablelist0] => Array
				(
					[action] => list
					[controller] => List
				)

		)
		*/
		
		// vars
		$class = $GLOBALS['TCA'][$table]['ctrl']['AirTable.Class'];
		$annotationRelationType = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field');
		$annotationForeignModel = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignModel');
		$foreignTable = BaseUtility::getTableNameFromClass($annotationForeignModel);
		
		// icon
		$typeicon_classes = $GLOBALS['TCA'][$foreignTable]['ctrl']['typeicon_classes']['default'];
		$icon = $obj->iconFactory->getIcon($typeicon_classes, \TYPO3\CMS\Core\Imaging\Icon::SIZE_SMALL);
		
		// url
		$getArgs = [
			'paramExt' => BaseUtility::getExtNameFromClassPath($annotationForeignModel),
			'paramSubDomain' => BaseUtility::getSubDomainNameFromClassPath($annotationForeignModel),
			'paramClass' => $annotationForeignModel,
			'recordSelection' => 1, // Сообщаем что это выбор записей
			'recordSelectionFieldname' => 'form1FieldValue_'.$field, // Сообщаем информацию о поле в которое вставлять записи
		];
		$url = $uriBuilder->setArguments($getArgs)->uriFor('list', []);
		
		$hideOption = '';
		switch($config['AirTable.Class']){
			case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_1To1':
			case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_1To1_Inverse':
			case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_1ToM_Inverse':
			case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_MTo1':
			case 'Litovchenko\AirTable\Domain\Model\Fields\Rel_Poly_1To1':
				$hideOption = 'display: none;';
			break;
		}
		
		// sys_attribute
		$disabled = '';
		$sysEavAttrSelected = \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('sysEavAttrSelected');
		if($field == 'propref_entity' && isset($sysEavAttrSelected)){
			return '<select name="form1Field_'.$field.'" class="form-control btn-sm" disabled>
						<option selected>'.$sysEavAttrSelected.'</option>
					</select>';
		}
		
		return '
			<table width="100%" class="" style="margin-bottom: 0;">
			<tr>
			<td width="30%">
				<select name="form1Field_'.$field.'" class="form-control btn-sm">
					<option disabled>'.$foreignTable.'</option>
					<option value="" '.($paramFilter_select == ''?'selected':'').'>Любое значение</option>
					<option value="REL_INCLUDE_uid" '.($paramFilter_select == 'REL_INCLUDE_uid'?'selected':'').'>Включая список ID (через ",")</option>
					<option value="REL_EXCLUDE_uid" '.($paramFilter_select == 'REL_EXCLUDE_uid'?'selected':'').'>Исключая список ID (через ",")</option>
					<option value="R0" '.($paramFilter_select == 'R0'?'selected':'').'>Кол-во: 0 связей</option>
					<option value="R1" '.($paramFilter_select == 'R1'?'selected':'').'>Кол-во: 1 связь</option>
					<option value="R1b" '.($paramFilter_select == 'R1b'?'selected':'').'>Кол-во: > 1 связи</option>
					<option value="R2" '.($paramFilter_select == 'R2'?'selected':'').' style="'.$hideOption.'">Кол-во: 2 связи</option>
					<option value="R3" '.($paramFilter_select == 'R3'?'selected':'').' style="'.$hideOption.'">Кол-во: 3 связи</option>
					<option value="R4" '.($paramFilter_select == 'R4'?'selected':'').' style="'.$hideOption.'">Кол-во: 4 связи</option>
					<option value="R5" '.($paramFilter_select == 'R5'?'selected':'').' style="'.$hideOption.'">Кол-во: 5 связей</option>
					<option value="R5+" '.($paramFilter_select == 'R5+'?'selected':'').' style="'.$hideOption.'">Кол-во: более 5 связей</option>
					<optgroup label="Для категорий">
						<option value="TODO" '.($paramFilter_select == 'TODO'?'selected':'').' disabled>Стартовый ID, включая подкатегории (1 уровень)</option>
						<option value="TODO" '.($paramFilter_select == 'TODO'?'selected':'').' disabled>Стартовый ID, включая подкатегории (2 уровеня)</option>
						<option value="TODO" '.($paramFilter_select == 'TODO'?'selected':'').' disabled>Стартовый ID, включая подкатегории (3 уровеня)</option>
						<option value="TODO" '.($paramFilter_select == 'TODO'?'selected':'').' disabled>Стартовый ID, включая подкатегории (4 уровеня)</option>
						<option value="TODO" '.($paramFilter_select == 'TODO'?'selected':'').' disabled>Стартовый ID, включая подкатегории (без ограничений)</option>
					</optgroup>
				</select>
			</td>
			<td width="65%" style="padding-left: 5px;">
				<input name="form1FieldValue_'.$field.'" class="form-control btn-sm" value="'.$paramFilter_input.'" placeholder="Выберите значения">
			</td>
			<td width="20" style="padding-left: 5px;" nowrap>
				<a onclick="window.open(\''.$url.'\');" class="btn btn-default btn-sm" title="'.$foreignTable.'">
					'.$icon.'
				</a>
			</td>
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

		$fieldInRow = $field;
		$rowArray = [];
		if(isset($row[$fieldInRow][0])){
			if(!empty($row[$fieldInRow])){
				$rowArray = $row[$fieldInRow];
			}
		} else {
			if(!empty($row[$fieldInRow])){
				$rowArray[0] = $row[$fieldInRow];
			}
		}
		
		// vars
		$class = $GLOBALS['TCA'][$table]['ctrl']['AirTable.Class'];
		$annotationRelationType = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field');
		$annotationForeignModel = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignModel');
		$foreignTable = BaseUtility::getTableNameFromClass($annotationForeignModel);
		
		// tca
		$label = $GLOBALS['TCA'][$foreignTable]['ctrl']['label'];
		$deleted = $GLOBALS['TCA'][$foreignTable]['ctrl']['delete'];
		
		$j = 1;
		$content = '';
		foreach($rowArray as $k => $v){
			// editLink
			$editLink = '';
			if($j == 1){
				$editIcon = $obj->iconFactory->getIcon('actions-system-pagemodule-open', \TYPO3\CMS\Core\Imaging\Icon::SIZE_SMALL);
				$editLink = '<td width="29" nowrap style="vertical-align: top; padding: 2px; border-left: 0;" rowspan="'.count($rowArray).'">
					<a href="'.$backendLink.'" class="btn btn-default btn-sm" style="width: 25px;">
						'.$editIcon.'
					</a>
				</td>';
			}
			$s = '';
			if(preg_match('/_Inverse$/is',$config['AirTable.Class'])){
				// &#8601;(вхд.)
				# $s = '&#8601; ';
			} else {
				// &#8599;(исхд.)
				# $s = '&#8599; ';
			}
			
			// Eav
			$contentEav = '';
			if(isset($v['uidkey']) && isset($v['pivot'])){
				$contentEav = '';
				$contentEav .= '<td nowrap style="border-right: 0;">'.$v['uidkey'].'</td>';
				$contentEav .= '<td nowrap style="border-right: 0;">=</td>';
				$contentEav .= '<td nowrap style="border-right: 0;">'.$v['pivot']['prop_value'].'</td>';
			}
			
			if($v[$deleted] == 1) {
				$icon = $obj->getIconForRecord($foreignTable, $v, \TYPO3\CMS\Core\Imaging\Icon::SIZE_SMALL);
				$content .='
				<tr>
					'.$editLink.'
					<td width="29" nowrap style="vertical-align: top; padding:0; '.HIDE_CHECKBOX.'">
					<div style="position: relative; width: 29px;">
					<label class="btn btn-default btn-sm" style="position: absolute; top: 2px; left: 2px; margin: 0; padding: 0 5px 0 5px; cursor: pointer;">
						<input type="checkbox" class="btn btn-default btn-sm" name="foo_'.$field.'" style="margin-top: 0px;">
					</label>
					</div>
					</td>
					<!--
					<td width="20" nowrap style="'.HIDE_CHECKBOX_TD_NEXT.'">
					<a href="?" class="btn btn-default btn-sm" style="padding-left: 7px; padding-right: 7px;">
						<i class="fa fa-reorder" aria-hidden="true"></i>
					</a>
					</td>
					-->
					<!--<td width="20" nowrap>'.$config['AirTable.Class'].'</td>-->
					<!--<td width="20" nowrap>'.$j.'/'.count($rowArray).'</td>-->
					<td width="20" nowrap><nobr><span style="background: #f00; opacity: 0.5;">'.$icon.' '.$v['uid'].'</span></nobr></td>
					<td nowrap style="border-right: 0; background: #f00; opacity: 0.5;">'.$s.' '.$v[$label].'</td>
					'.$contentEav.'
				</tr>';
			} else {
				$icon = $obj->iconFactory->getIconForRecord($foreignTable, $v, \TYPO3\CMS\Core\Imaging\Icon::SIZE_SMALL);
				$content .='
				<tr>
					'.$editLink.'
					<td width="29" nowrap style="vertical-align: top; padding:0; '.HIDE_CHECKBOX.'">
					<div style="position: relative; width: 29px;">
					<label class="btn btn-default btn-sm" style="position: absolute; top: 2px; left: 2px; margin: 0; padding: 0 5px 0 5px; cursor: pointer;">
						<input type="checkbox" class="btn btn-default btn-sm" name="foo_'.$field.'" style="margin-top: 0px;">
					</label>
					</div>
					</td>
					<!--
					<td width="20" nowrap style="'.HIDE_CHECKBOX_TD_NEXT.'">
					<a href="?" class="btn btn-default btn-sm" style="padding-left: 7px; padding-right: 7px;">
						<i class="fa fa-reorder" aria-hidden="true"></i>
					</a>
					</td>
					-->
					<!--<td width="20" nowrap>'.$config['AirTable.Class'].'</td>-->
					<!--<td width="20" nowrap>'.$j.'/'.count($rowArray).'</td>-->
					<td width="20" nowrap><nobr><span style="">'.$icon.' '.$v['uid'].'</span></nobr></td>
					<td nowrap style="border-right: 0;">'.$s.' '.$v[$label].'</td>
					'.$contentEav.'
				</tr>';
			}
			$j++;
		}
		if($content == NULL){
			return '
				<td style="vertical-align: top; border-right: 0;" nowrap><a href="'.$backendLink.'"><code style="'.STYLE_EMPTY_FIELD.'">Установить связь</code></a></td>
			';
		}else{
			if(preg_match('/_Inverse$/is',$config['AirTable.Class'])){
				$color = ''; // orange
			} else {
				$color = ''; // green
			}
			return '<td style="vertical-align: top; padding: 0;">
				<table class="t3tablerel table table-striped table-bordered table-hover" style="margin-top: -1px; margin-bottom: -1px; border: 0; color: '.$color.'">
				<!--
				<thead>
				<tr>
					<td><b>ID</b></td>
					<td><b>Ссылка</b></td>
				</tr>
				</thead>
				-->
				'.$content.'
				</table>
			</td>';
		}
	}
	
	// exportController
	public static function exportControllerRow(&$rowData, $class, $table, $field, $config, $row){
		$annotationForeignModel = BaseUtility::getClassFieldAnnotationValueNew($class,$field,'AirTable\Field\ForeignModel');
		$foreignTable = BaseUtility::getTableNameFromClass($annotationForeignModel);
		$label = $GLOBALS['TCA'][$foreignTable]['ctrl']['label'];
		$disabled = $GLOBALS['TCA'][$foreignTable]['ctrl']['enablecolumns']['disabled'];
		$deleted = $GLOBALS['TCA'][$foreignTable]['ctrl']['delete'];
		$sorting = $GLOBALS['TCA'][$foreignTable]['ctrl']['sortby'];

		$fieldInRow = $field;
		$rowArray = [];
		if(isset($row[$fieldInRow][0])){
			if(!empty($row[$fieldInRow])){
				$rowArray = $row[$fieldInRow];
			}
		} else {
			if(!empty($row[$fieldInRow])){
				$rowArray[0] = $row[$fieldInRow];
			}
		}
		
		foreach($rowArray as $k => $v){
			$rowData[$field][] = '[A]'.$v['uid'];
			#if($label != ''){
			#	$rowData[$field.'.'.$label][] = $v[$label];
			#}
			#if($disabled != ''){
			#	$rowData[$field.'.'.$disabled][] = $v[$disabled];
			#}
			#if($deleted != ''){
			#	$rowData[$field.'.'.$deleted][] = $v[$deleted];
			#}
			#if($sorting != ''){
			#	$rowData[$field.'.'.$sorting][] = $v[$sorting];
			#}
		}
		
		$rowData[$field] = implode("\n",$rowData[$field]);
		#if($label != ''){
		#	$rowData[$field.'.'.$label] = implode("\n",$rowData[$field.'.'.$label]);
		#}
		#if($disabled != ''){
		#	$rowData[$field.'.'.$disabled] = implode("\n",$rowData[$field.'.'.$disabled]);
		#}
		#if($deleted != ''){
		#	$rowData[$field.'.'.$deleted] = implode("\n",$rowData[$field.'.'.$deleted]);
		#}
		#if($sorting != ''){
		#	$rowData[$field.'.'.$sorting] = implode("\n",$rowData[$field.'.'.$sorting]);
		#}
	}
	
	// importController
	public static function importControllerRow(&$rowData, $class, $table, $field, $config, $row){
		#default: 
		#	$modelName::refAttach($recordId, $kData, $vArex[0]); // true || false // (id-текущей, поле связи, id-связи)
		#break;
		#case 'D': 
		#	$modelName::refDetach($recordId, $kData, trim($vArex,'D')); // true || false // (id-текущей, поле связи, id-связи) 
		#break;
		#case 'T':  
		#	$modelName::refDetach($recordId, $kData); // true || false // (id-текущей, поле связи) 
		#break;
		if(!empty($row[$field])){
			#$idListAttach = [];
			#$idListDetach = [];
			$fieldInRow = $field;
			$relList = explode(chr(10),$row[$field]);
			
			foreach($relList as $k => $v){
				if(preg_match('/^\[T\](.*)/is',$v,$match)){
					$uids = $class::refCollection($field,$row['uid']);
					$class::refDetach($field,$row['uid'],$uids);
				
				}elseif(preg_match('/^\[A\](.*)/is',$v,$match)){
					$class::refAttach($field,$row['uid'],$match[1]);
				
				}elseif(preg_match('/^\[D\](.*)/is',$v,$match)){
					$class::refDetach($field,$row['uid'],$match[1]);
				}
			}
		}
		// $rowData[$field] = 1;
		// $rowData[$field] = '';
	}
	
	// Автоматизированная выборка связей 
	// public function refProvider($field){}
	
	// Создание связей
	// public function refAttach($field, $idTwo = null, $data = []){}
	
	// Удаление связей
	// public function refDetach($field, $idTwo = null, $data = []){}
	
}