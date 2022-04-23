<?php
namespace Litovchenko\AirTable\Service;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use Litovchenko\AirTable\Utility\BaseUtility;

class DatabaseSchemaService
{
    /**
     * A slot method to inject the required mysql fulltext definition
     * to schema migration
     *
     * @param array $sqlString
     * @return array
     */
    public function addMysqlTableAndFields(array $sqlString)
    {
		$fields = [];
		$allClasses = BaseUtility::getLoaderClasses2();
		$models = array_merge((array)$allClasses['BackendModelCrud'],(array)$allClasses['BackendModelCrudOverride'],(array)$allClasses['BackendModelExtending']);
		foreach($models as $class) {
			$extensionKey = BaseUtility::getExtNameFromClassPath($class);
			$signature = BaseUtility::getTableNameFromClass($class);
			
			// $class_parents = class_parents($class);
			// if(in_array('Litovchenko\AirTable\Domain\Model\AbstractModel',$class_parents)){
				$table = BaseUtility::getTableNameFromClass($class);
				
				//$fields = [];
				//$fields[] = 'sys_export int(11) DEFAULT \'0\' NOT NULL'; // Добавил для колонки заглушки для "Analyze Database Structure" 
				//$fields[] = 'sys_import int(11) DEFAULT \'0\' NOT NULL'; // Добавил для колонки заглушки для "Analyze Database Structure" 
				
				$userFields = BaseUtility::getClassAllFieldsNew($class);
				foreach($userFields as $kField => $vField){
					$annotationField = BaseUtility::getClassFieldAnnotationValueNew($class,$vField,'AirTable\Field');
					$annotationDoNotCheck = BaseUtility::getClassFieldAnnotationValueNew($class,$vField,'AirTable\Field\DoNotCheck');
					$annotationDoNotSqlAnalyze = BaseUtility::getClassFieldAnnotationValueNew($class,$vField,'AirTable\Field\DoNotSqlAnalyze');
					$fieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationField;
					if(class_exists($fieldClass) && method_exists($fieldClass,'buildConfiguration') && method_exists($fieldClass,'buildConfigurationCheck')){
						// Делаем проверку
						$arError = $fieldClass::buildConfigurationCheck($class,$table,$vField);
						if(count($arError) == 0 || $annotationDoNotCheck == 1){
							// Выбираем данные о MySql поле
							if(method_exists($fieldClass, 'databaseDefinitions')){
								$databaseDefinitionsAr = $fieldClass::databaseDefinitions($class,$table,$vField);
								foreach($databaseDefinitionsAr as $tName => $fAr){
									foreach($fAr as $fK => $fV){
										// Таким образом исключаем возможности ошибок (в основном от полей со связями в которые есть ошибки про создании) 
										if(trim($tName) != '' && trim($fK) != '' && trim($fV) != ''){
											#if($fK == 'uid'){
											#	continue;
											#}
											#if($fK == 'pid'){
											#	continue;
											#}
											#if($fK == 'importprocess'){
											#	continue;
											#}
											#if($fK == 'importolduid'){
											#	continue;
											#}
											if($annotationDoNotSqlAnalyze == 1){
												continue;
											}
											
											// Eav attr
											$annotationEavAttr = BaseUtility::getClassFieldAnnotationValueNew($class,$fK,'AirTable\Field\EavAttr');
											if($annotationEavAttr == true){
												continue;
											}
											
											$fields[$extensionKey][$tName][$fK] = "\t".$fK." ".$fV; // 'test int(11) DEFAULT \'0\' NOT NULL'
										}
									}
								}
							}
						}
						
						# if(count($arError) > 0 && $annotationDoNotCheck != 1 && $annotationDoNotSqlAnalyze != 1){
						# 	BaseUtility::fileWrite($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/4.txt',$class.'|'.$table.'|'.$vField.print_r($arError,true)."\n");
						# }
					}
				// }
			}
		}
		
		#print "<pre>";
		#print_r($fields);
		#exit();
				
		// $sqlString[] = LF . 'CREATE TABLE ' . $table . ' (' . LF . \implode(',' . LF, $fields) . LF . ');' . LF;
		$extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('air_table');
		$virgule = ',';
		foreach($models as $class) {
			$extensionKey = BaseUtility::getExtNameFromClassPath($class);
			$signature = BaseUtility::getTableNameFromClass($class);
				
			// $class_parents = class_parents($class);
			// if(in_array('Litovchenko\AirTable\Domain\Model\AbstractModel',$class_parents)){
				$table = BaseUtility::getTableNameFromClass($class);
				if($table == 'sys_mm'){
					$sqlContent = file_get_contents($extPath.'Resources/Private/SqlSysMm.sql');
					$virgule = ',';
					
				} elseif(
					$class::$TYPO3['thisIs'] == 'BackendModelCrudOverride' || 
					(
						property_exists(get_parent_class($class),'TYPO3') && 
						get_parent_class($class)::$TYPO3['thisIs'] == 'BackendModelCrudOverride'
					)
				) {
					$sqlContent = file_get_contents($extPath.'Resources/Private/SqlModelCrudOverride.sql');
					$virgule = ',';
					
				} elseif(
					$class::$TYPO3['thisIs'] == 'BackendModelCrud' || 
					(
						property_exists(get_parent_class($class),'TYPO3') && 
						get_parent_class($class)::$TYPO3['thisIs'] == 'BackendModelCrud'
					)
				) {
					$sqlContent = file_get_contents($extPath.'Resources/Private/SqlModelCrud.sql');
					$virgule = ',';
					
				#} elseif($class::$TYPO3['thisIs'] == 'BackendModelExtending') {
				#	$sqlContent = file_get_contents($extPath.'Resources/Private/SqlModelExtending.sql');
				#	$virgule = ',';
					
				} else {
					
				}
				
				$sqlContent = str_replace('###TABLE###', $table, $sqlContent);
				if(count($fields[$extensionKey][$table])>0){
					$send = str_replace("###COLUMNS###", implode(",\n", $fields[$extensionKey][$table]).$virgule, $sqlContent);
					$sqlString[$extensionKey][$table.'_AirTableSql'] = $send;
				} else {
					$send = str_replace("###COLUMNS###", "", $sqlContent);
					$sqlString[$extensionKey][$table.'_AirTableSql'] = $send;
				}
			// }
		}

		foreach($sqlString as $extensionKey => $v){
			$ext_tables_sql_content = "";
			foreach($v as $k2 => $v2){
				if(strstr($k2,'_AirTableSql')){
					$ext_tables_sql_content .= $v2."\n\n\n";
				}
			}
			BaseUtility::fileReWrite($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/'.$extensionKey.'/ext_tables.sql',$ext_tables_sql_content);
		}
		
		// BaseUtility::fileReWrite($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/1.txt',print_r($GLOBALS['TCA'],true));
		BaseUtility::fileReWrite($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3temp/ext_tables.sql',print_r($sqlString,true));
        // return [$sqlString];
    }
}
