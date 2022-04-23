<?php
namespace Litovchenko\AirTable\Domain\Model\Traits\Specific;

use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Backend\Utility;
use TYPO3\CMS\Core\Database;
use Litovchenko\AirTable\Utility\BaseUtility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Функции после операции в БД // todo
 * updateAction()
 * beforeUpdateAction()
 * newAction()
 * beforeNewAction()
 *
 * https://www.andrerinas.de/tutorials/typo3-alle-aftersave-hooks-im-ueberblick-62-76lts.html
 * $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['extkey'] = 'Vendor\\Extension\\Hook\\TCEmainHook';
 * $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass']['extkey'] = 'Vendor\\Extension\\Hook\\TCEmainHook';
 *
 * public function processCmdmap_preProcess($command, $table, $id, $value, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj) {}
 * public function processCmdmap_postProcess($command, $table, $id, $value, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj) {}
 * public function processCmdmap_deleteAction($table, $id, $recordToDelete, $recordWasDeleted=NULL, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj) {}
 * public function processDatamap_preProcessFieldArray(array &$fieldArray, $table, $id, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj) {}
 * public function processDatamap_postProcessFieldArray($status, $table, $id, array &$fieldArray, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj) {}
 * public function processDatamap_afterDatabaseOperations($status, $table, $id, array $fieldArray, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj) {}
 * public function processDatamap_afterAllOperations(\TYPO3\CMS\Core\DataHandling\DataHandler &$pObj) {}
	
	_load_before
	_load_after
	_save_before
	_save_after
	_save_commit_after
	_delete_before
	_delete_after
	_delete_commit_after
	_четкий
	
     * Record event (before / after) - // Todo https://laravel.ru/posts/338
     * @return '';
    public static function cmdEvent($command, $when, &$table, $id, &$fieldArray)
    {
        $command = 'insert || update || delete';
        if ($when == 'before') {
            //
        } else {
            //
        }
    }
 */

trait TCEmainHook // DataHandlerHook
{
	// Сообщение...
    public function flashMessage($title = '', $msg, $type = FlashMessage::INFO)
    {
		$message = GeneralUtility::makeInstance(FlashMessage::class,
		 	$msg,
		 	$title, // header is optional
		 	$type,
		 	TRUE // whether message should be stored in session
		);
		
		/** @var $flashMessageService FlashMessageService */
		#$flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
		#$flashMessageService->getMessageQueueByIdentifier()->enqueue($message);
	}
	
	// Перед выполнением команды copy, move, delete, undelete, localize, copyToLanguage, inlineLocalizeSynchronize, version
	public function processCmdmap_preProcess($command, $table, $id, $value, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj) 
	{
		$class = get_called_class();
		if($GLOBALS['TCA'][$table]['ctrl']['AirTable.Class'] == $class){
			self::flashMessage('System ['.$table.'] CMD', '#A processCmdmap_preProcess COMMAND: ' . "".$command);
			
			// $table = '';
			// $fieldArray['title'] = '1345'.$status;
			#if($command == 'delete'){
			#	#$class::cmdDelete('before', $table, $id, $fieldArray); // self::flashMessage('Удаление записи (до)');
			#}
		}
	}
	
	// После выполнением команды copy, move, delete, undelete, localize, copyToLanguage, inlineLocalizeSynchronize, version
	public function processCmdmap_postProcess($command, $table, $id, $value, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj) 
	{
		$class = get_called_class();
		if($GLOBALS['TCA'][$table]['ctrl']['AirTable.Class'] == $class){
			self::flashMessage('System ['.$table.'] CMD', '#B processCmdmap_postProcess COMMAND: ' . "".$command);
			
			// $table = '';
			// $fieldArray['title'] = '1345'.$status;
			#if($command == 'delete'){
			#	#$class::cmdDelete('after', $table, $id, $fieldArray); // self::flashMessage('Удаление записи (после)');
			#}
		}
	}
	
	// После удаления записи...
	public function processCmdmap_deleteAction($table, $id, $recordToDelete, $recordWasDeleted=NULL, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj) 
	{
		$class = get_called_class();
		if($GLOBALS['TCA'][$table]['ctrl']['AirTable.Class'] == $class){
			self::flashMessage('System ['.$table.'] CMD DELETE', '#C processCmdmap_deleteAction '.serialize($fieldArray));
		}
	}
	
	// #1 Перед сохранением в базе (1)
    public function processDatamap_preProcessFieldArray(array &$fieldArray, $table, $id, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj)
    {
		#$table = '';
		#$fieldArray['title'] = '1345 789 preProcess';
		$class = get_called_class();
		if($GLOBALS['TCA'][$table]['ctrl']['AirTable.Class'] == $class){
			self::flashMessage('#1 processDatamap_preProcessFieldArray', $table.' = '.serialize($fieldArray));
			#$class::cmdEvent('BEFORE OR AD', $table, $id, $fieldArray);
		}
		
		// Eav attr
		foreach($fieldArray as $k => $v){
			if(strstr($k,'attr_entity_')){
				// unset($fieldArray[$k]);
			}
		}
	}
	
	// #2 Перед сохранением в базе (1)
    public function processDatamap_postProcessFieldArray($status, $table, $id, array &$fieldArray, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj)
    {
		#$table = '';
		#$fieldArray['header'] = '1345 postProcess' . $status;
		
		$class = get_called_class();
		if($GLOBALS['TCA'][$table]['ctrl']['AirTable.Class'] == $class){
			self::flashMessage('#2 processDatamap_postProcessFieldArray', $table.' STATUS ['.$status.']= '.serialize($fieldArray));
			#$class::cmdEvent('new or update BEFORE OR AD', $table, $id, $fieldArray);
		}
		
		// Eav attr
		foreach($fieldArray as $k => $v){
			if(strstr($k,'attr_entity_')){
				// unset($fieldArray[$k]);
			}
		}
	}
	
	// #3 После выполнения операции...
	public function processDatamap_afterDatabaseOperations($status, $table, $id, array $fieldArray, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj) 
	{
		$class = get_called_class();
		if($GLOBALS['TCA'][$table]['ctrl']['AirTable.Class'] == $class){
			self::flashMessage('#3 processDatamap_afterDatabaseOperations', $table.' STATUS ['.$status.']= '.serialize($fieldArray));
			
			// Обновляем или вставляем свойства EAV...
			if(($status == 'new' or $status = 'update')){ // && !empty($fieldArray['propref_attributes'])
				$i = 1;
				$flexFormArray = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($fieldArray['propref_attributes']);
				// $flexFormArray['data']['sDEF']['lDEF']['settings.orderBy']['vDEF'] = 'title';
				// $flexFormArray['data']['sDEF']['lDEF']['settings.orderDirection']['vDEF'] = 'desc';
				
				foreach($flexFormArray['data']['sDEF']['lDEF'] as $attr_key => $attr_value){
					
					#$temp = explode("___",$sheet);
					#$entity_type = $temp[0];
					#$entity_sign = $temp[1];
					
					$fieldType = $GLOBALS['ENTITY_TYPES'][$table]['field']; // RType;
					$rowData = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord($table,$id,$fieldType,'',false);
					$entity_type = $table;
					$entity_sign = $rowData[$fieldType]; // RType
					if($entity_type != '' && $entity_sign != ''){
							
						// Обходим массив полей во вкладке
						// foreach($lDEF['lDEF'] as $attr_key => $attr_value){
						
							// Ищем ключ
							$row = \Litovchenko\AirTable\Domain\Model\Eav\SysAttribute::recSelect('first', function($q) use ($entity_type, $entity_sign, $attr_key){
								$q->where('entity_type', '=', $entity_type.'___'.$entity_sign);
								$q->where('attr_key', '=', $attr_key);
								$q->limit(1);
							});
							
							// Ищем связь
							if($row['attr_key'] != '' && $row['entity_type'] != ''){
								$attr_id = $row['uid'];
								$rowMm = \Litovchenko\AirTable\Domain\Model\Eav\SysValue::recSelect('first', function($q) use ($id, $attr_id){
									#$q->where('tablename', '=', $table);
									#$q->where('fieldname', '=', 'propref_attributes');
									$q->select('uid');
									$q->where('entity_row_id', '=', $id);
									$q->where('sys_attribute_row_id', '=', $attr_id);
									$q->limit(1);
								});
								
								// Update
								if($rowMm['uid'] > 0 && !empty($attr_value['vDEF'])){
									\Litovchenko\AirTable\Domain\Model\Eav\SysValue::recUpdate($rowMm['uid'], [
										#'tablename'=>$table,
										#'fieldname'=>'propref_attributes',
										'sorting'=>$i,
										'entity_row_id'=>$id,
										'sys_attribute_row_id'=>$attr_id,
										'attr_value'=>$attr_value['vDEF'],
										
										// Дубликаты для удобной фильтрации
										'duplicate_attr_key' => $row['attr_key'],
										'duplicate_entity_type' => $row['entity_type']
									]);
										
								// Insert
								} elseif(!empty($attr_value['vDEF'])) {
									\Litovchenko\AirTable\Domain\Model\Eav\SysValue::recInsert([
										#'tablename'=>$table,
										#'fieldname'=>'propref_attributes',
										'sorting'=>$i,
										'entity_row_id'=>$id,
										'sys_attribute_row_id'=>$attr_id,
										'attr_value'=>$attr_value['vDEF'],
										
										// Дубликаты для удобной фильтрации
										'duplicate_attr_key' => $row['attr_key'],
										'duplicate_entity_type' => $row['entity_type']
									]);
								
								// Delete
								} else {
									$filter = [];
									#$filter['where.10'] = ['tablename',$table];
									#$filter['where.20'] = ['fieldname','propref_attributes'];
									$filter['where.30'] = ['entity_row_id',$id];
									$filter['where.40'] = ['sys_attribute_row_id',$attr_id];
									\Litovchenko\AirTable\Domain\Model\Eav\SysValue::recDelete($filter);
								}
								
								$i++;
							}
							
							
						}
					#}
					
					#$xmlKeyProperty = str_replace('_SOBAKA_','@',$flexFormKey);
					#$xmlKeyProperty = str_replace('_SUB_','-',$xmlKeyProperty);
					# $xmlKeyProperty = $flexFormKey;
				}
			}
		}
	}
	
	// #4 После выполнения любой операции (в т.ч. сброса кэша)...
	public function processDatamap_afterAllOperations(\TYPO3\CMS\Core\DataHandling\DataHandler &$pObj) 
	{
		#self::flashMessage('#4 processDatamap_afterAllOperations');
		#exit();
	}
	
	// #5 Генератор Flex-формы
	// Отказался! -> Сделал 
	/*
	public function parseDataStructureByIdentifierPostProcess(array $dataStructure, array $identifier)
	{		
		// && $identifier['dataStructureKey'] === 'news_pi1,list'
		#if ($identifier['type'] === 'tca' && $identifier['tableName'] === 'tt_content' && $identifier['fieldName'] === 'propref_attributes'){
		#	$file = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/typo3conf/ext/air_table/Configuration/FlexForms/Example.xml';
		#	$content = file_get_contents($file);
		#	if ($content) {
		#		$flexFormArray = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($content);
		#		$dataStructure['sheets']['extraEntry'] = $flexFormArray;
		#	}
		#	$dataStructure = [];
		#}
		
		$class = get_called_class();
		if($GLOBALS['TCA'][$identifier['tableName']]['ctrl']['AirTable.Class'] == $class){
			if($identifier['type'] === 'tca' && $identifier['fieldName'] == "propref_attributes"){
				self::flashMessage('FLEXFORM CREATE (parseDataStructureByIdentifierPostProcess)', $identifier['tableName']);
			
				$dataStructure = [];
				#$fields = \Litovchenko\AirTable\Utility\BaseUtility::getClassAllFieldsNew('Litovchenko\AirTable\Domain\Model\SysFlexFormProps');
				$fields = $GLOBALS['TCA']['sys_attribute']['columns'];
				foreach($fields as $k => $v){
					// page@ext-air-table@prop-field-input
					$isEavAttr = $GLOBALS['TCA']['sys_attribute']['columns'][$k]['IsEavAttr'];
					$eavAttrBelong = $GLOBALS['TCA']['sys_attribute']['columns'][$k]['EavAttrBelong'];
					if($isEavAttr == true){ // skip uid, pid
						if($identifier['tableName'] == $eavAttrBelong){ // Принадлежность
							$dataStructure['sheets']['sDEF']['ROOT']['el'][$k]['TCEforms'] 
							= $GLOBALS['TCA']['sys_attribute']['columns'][$k];
						}
					}
				}
			}
		}
		
		return $dataStructure;
	}
	*/
	
	// #6 Наполнение данными формы
    public function addData(array $result)
    {
		// $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['formDataGroup']['tcaDatabaseRecord']
        #if ($result['tableName'] === 'tt_content') {
		#	if ($result['command'] === 'new') {
		#		$result['databaseRow']['header'] = "NEW HEADER";
		#		$result['databaseRow']['bodytext'] = "NEW BODYTEXT";
		#	}
        #}
		
		$table = $result['tableName'];
		$id = $result['databaseRow']['uid'];
		$class = get_called_class();
		if($GLOBALS['TCA'][$table]['ctrl']['AirTable.Class'] == $class){
			if(isset($GLOBALS['TCA'][$table]['columns']['propref_attributes'])){
				self::flashMessage('SELECT DATA FLEX FORM', $table);
				
				// Затираем данные (что бы не использовалась дата XML)
				if(isset($result['databaseRow']['propref_attributes']['data']['sDEF']['lDEF'])){
					$result['databaseRow']['propref_attributes']['data']['sDEF']['lDEF'] = [];
				}
				
				// Выбираем данные
				$rows = \Litovchenko\AirTable\Domain\Model\Eav\SysValue::recSelect('get', function($q) use ($table, $id){
					$q->where('entity_row_id', '=', $id);
					$q->with('sys_attribute_row_id_func');
				});
				
				#print "<pre>";
				#print_r($result['databaseRow']);
				
				// Вставляем данные
				if($rows){
					foreach($rows as $k => $v){
						#$fieldName = 'field_test';
						#$result['databaseRow']['propref_attributes']['data']['sDEF']['lDEF'][$fieldName]['vDEF'] = "TEXT 100";
						#$result['databaseRow']['propref_attributes']['data']['sDEF']['lDEF'][$fieldName.'_2']['vDEF'] = "TEXT 200";
						#$result['databaseRow']['propref_attributes']['data']['sDEF']['lDEF'][$fieldName.'_3']['vDEF'] = "TEXT 300";
						#$eav_key = 'entity_'.$v['sys_attribute_row_id_func']['entity_type'].'_'.$v['sys_attribute_row_id_func']['attr_key'];
						
						$entity_type_with_sign = $v['sys_attribute_row_id_func']['entity_type'];
						$temp = explode("___",$entity_type_with_sign);
						$entity_type = $temp[0];
						$entity_sign = $temp[1];
						
						$fieldType = $GLOBALS['ENTITY_TYPES'][$entity_type]['field']; // RType
						$attr_type = $v['sys_attribute_row_id_func'][$fieldType]; // RType
						$attr_key = $v['sys_attribute_row_id_func']['attr_key'];
						$attr_value = $v['attr_value'];
						
						$fieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\\'.current(explode(".",$attr_type));
						if(class_exists($fieldClass) && method_exists($fieldClass,'flexFormValueConvert')) {
							$value = $fieldClass::flexFormValueConvert($attr_value);
						} else {
							$value = $attr_value;
						}
						
						#$xmlKeyProperty = str_replace('@','_SOBAKA_',$eav_key);
						#$xmlKeyProperty = str_replace('-','_SUB_',$xmlKeyProperty);
						#$xmlKeyProperty = $eav_key;
						
						if(!empty($value)){
							$result['databaseRow']['propref_attributes']['data']['sDEF']['lDEF'][$attr_key]['vDEF'] = $value;
						} else {
							$result['databaseRow']['propref_attributes']['data']['sDEF']['lDEF'][$attr_key]['vDEF'] = '';
						}
					}
				}
				
				#print "<pre>";
				#print_r($result['databaseRow']);
				#exit();
			}
		}
		
        return $result;
    }
	
}