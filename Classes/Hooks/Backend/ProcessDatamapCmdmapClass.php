<?php
namespace Litovchenko\AirTable\Hooks\Backend;

use TYPO3\CMS\Backend\Form\FormDataProviderInterface;
use TYPO3\CMS\Backend\Utility;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use Litovchenko\AirTable\Domain\Model\Content\Pages;
use Litovchenko\AirTable\Domain\Model\Content\TtContent;
use Litovchenko\AirTable\Domain\Model\SysFluxSetting;
use Litovchenko\AirTable\Utility\BaseUtility;

class ProcessDatamapCmdmapClass implements FormDataProviderInterface
{
	/**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'Hooks',
		'name' => 'Устанавливаем при любом сохранении "backend_layout"="flux__grid"',
		'description' => 'В нашем случае почему-то "flux__"', // Исправилось как-то "flux__grid"
		'onlyBackend' => [
			'TYPO3_CONF_VARS|SC_OPTIONS|t3lib/class.t3lib_tcemain.php|processDatamapClass',
			'TYPO3_CONF_VARS|SC_OPTIONS|t3lib/class.t3lib_tcemain.php|processCmdmapClass'
		]
	];
	
	public function processDatamap_preProcessFieldArray(array &$fieldArray, $table, $id, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj)
	{
		if($table == 'pages') 
		{
			// $fieldArray['geolng'] = 01.001; // for testing purposes
			// var_dump($fieldArray); die();
			
			if($fieldArray['tx_fed_page_controller_action'] == ''){
				$fieldArray['backend_layout'] = '';
			}
			
			if(trim($fieldArray['tx_fed_page_controller_action']) != ''){
				$fieldArray['backend_layout'] = 'flux__grid';
			}
			
			// if(trim($fieldArray['tx_fed_page_controller_action_sub']) != ''){
			// 	$fieldArray['backend_layout_next_level'] = 'flux__grid'; // for testing purposes
			// } elseif(trim($fieldArray['tx_fed_page_controller_action_sub']) == ''){
			// 	if($fieldArray['backend_layout_next_level'] == 'flux__grid'){
			// 		$fieldArray['backend_layout_next_level'] = ''; // for testing purposes
			// 	}
			// }
		}
	}
	
	public function processDatamap_afterDatabaseOperations($status, $table, $id, array $fieldArray, \TYPO3\CMS\Core\DataHandling\DataHandler &$pObj) 
	{
		if(isset($fieldArray['tx_fed_page_flexform']) || isset($fieldArray['pi_flexform']) || isset($fieldArray['prop_flexform']))
		{
			if(isset($fieldArray['tx_fed_page_flexform'])){
				$signature = Pages::recSelect('first',$id);
				$signature = $signature['tx_fed_page_controller_action'];
				$flexformKey = 'tx_fed_page_flexform';
				
			}elseif(isset($fieldArray['pi_flexform'])){
				$row = TtContent::recSelect('first',$id);
				$signature = ($row['CType'] == 'list') ? $row['list_type'] : $row['CType'];
				$flexformKey = 'pi_flexform';
				
			}elseif(isset($fieldArray['prop_flexform'])){
				// $signature = $fieldArray['CType'];
				// $flexformKey = 'prop_flexform';
				
			}
			
			// Выполняем только для наших сигнатур
			if($status == 'new' or $status = 'update')
			{
				if(strstr($signature,'_pages_') || strstr($signature,'_elements_') || strstr($signature,'_gridelements_') || strstr($signature,'_plugins_'))
				{
					self::flashMessage('System ['.$table.'] CMD', 'processDatamap_afterDatabaseOperations: ' . "".$signature);
					
					// Конвертируем
					$flexformService = GeneralUtility::makeInstance(FlexFormService::class);
					$flexformData = $flexformService->convertFlexFormContentToArray($fieldArray[$flexformKey]);
					$flexformData = BaseUtility::convertArrayToDot($flexformData);
					
					// Читаем ключи
					$k = 100;
					$idList = [];
					foreach($flexformData as $kFluxSetting => $vFluxSetting){
						
						// Ищем ключ
						$filter = [];
						$filter['where.10'] = ['source_table','=',$table];
						$filter['where.20'] = ['source_field','=',$flexformKey];
						$filter['where.30'] = ['source_signature','=',$signature];
						$filter['where.40'] = ['source_record','=',$id];
						$filter['where.50'] = ['settingkey','=',$kFluxSetting];
						$rowSetting = SysFluxSetting::recSelect('first',$filter);
						
						// UPDATE:: Если существует - обновляем
						if($rowSetting['uid'] > 0){
							$data = [];
							$data['source_sorting'] = $k;
							$data['settingvalue'] = $vFluxSetting;
							SysFluxSetting::recUpdate($rowSetting['uid'], $data);
							$idList[] = $rowSetting['uid'];
							
						// INSERT:: Если не существует - вставляем
						} else {
							$data = [];
							$data['source_table'] = $table;
							$data['source_field'] = $flexformKey;
							$data['source_signature'] = $signature;
							$data['source_record'] = $id;
							$data['source_sorting'] = $k;
							$data['settingkey'] = $kFluxSetting;
							$data['settingvalue'] = $vFluxSetting;
							$idList[] = SysFluxSetting::recInsert($data);
						}
						$k += 10;
					}
					
					// Удаляем ключи, которых нет в форме
					# $filter = [];
					# $filter['where.10'] = ['source_table','=',$table];
					# $filter['where.20'] = ['source_signature','=',$signature];
					# $filter['where.30'] = ['source_record','=',$id];
					# $filter['whereNotIn'] = ['uid',$idList];
					# SysFluxSetting::recDelete($filter);
				}
			}
		}
	}
	
	// #6 Наполнение данными формы
    public function addData(array $result)
    {
		if(isset($result['databaseRow']['tx_fed_page_flexform']) || isset($result['databaseRow']['pi_flexform']) || isset($result['databaseRow']['prop_flexform']))
		{
			if(isset($result['databaseRow']['tx_fed_page_flexform'])){
				$signature = Pages::recSelect('first',$result['databaseRow']['uid']);
				$signature = $signature['tx_fed_page_controller_action'];
				$flexformKey = 'tx_fed_page_flexform';
				$table = 'pages';
				
			}elseif(isset($result['databaseRow']['pi_flexform'])){
				$row = TtContent::recSelect('first',$result['databaseRow']['uid']);
				$signature = ($row['CType'] == 'list') ? $row['list_type'] : $row['CType'];
				$flexformKey = 'pi_flexform';
				$table = 'tt_content';
				
			}elseif(isset($result['databaseRow']['prop_flexform'])){
				// $signature = $result['databaseRow']['CType'];
				// $flexformKey = 'prop_flexform';
				
			}
			
			// Выполняем только для наших сигнатур
			if(strstr($signature,'_pages_') || strstr($signature,'_elements_') || strstr($signature,'_gridelements_') || strstr($signature,'_plugins_'))
			{
				self::flashMessage('System ['.$table.'] CMD', 'addData: ' . "".$signature);
				
				// Конвертируем в доты...
				$databaseRowFlexArray = BaseUtility::convertArrayToDot($result['databaseRow'][$flexformKey]);
				
				// Извлекаем данные (настройки)
				$filter = [];
				$filter['select'] = ['settingkey','settingvalue'];
				$filter['where.10'] = ['source_table','=',$table];
				$filter['where.20'] = ['source_field','=',$flexformKey];
				$filter['where.30'] = ['source_signature','=',$signature];
				$filter['where.40'] = ['source_record','=',$result['databaseRow']['uid']];
				$rowsSettings = SysFluxSetting::recSelect('get',$filter);
				
				// Производим замену
				foreach($databaseRowFlexArray as $k => $v)
				{
					foreach($rowsSettings as $k2 => $v2)
					{
						// data.options.lDEF.settings#hellow1.vDEF
						// <flux:field.input name="settings.hellow1" label="Hellow (1)" />
						$findOne = 'lDEF.'.str_replace('.','#',$v2['settingkey']).'.vDEF';
						if(strstr($k,$findOne)){
							$databaseRowFlexArray[$k] = $v2['settingvalue'];
							continue;
						}
						
						// data.options3.lDEF.settings#sectionObjectAsClass2.el.607c2b9bc6e71391898681.custom.el.hellowa.vDEF
						// <flux:form.section name="settings.sectionObjectAsClass2" label="Telephone numbers 2">
						$temp = explode('.',$k);
						if(in_array('el', $temp)){
							$findTwo = [];
							$addToArray = false;
							foreach($temp as $fK => $fV){
								if($fV == 'lDEF'){
									$addToArray = true;
									continue;
								}
								if($addToArray == true){
									if($fV == 'el'){
										continue;
									}
									if($fV == 'vDEF'){
										continue;
									}
									$findTwo[] = $fV;
								}
							}
							$findTwo = implode('.',$findTwo);
							if(str_replace('#','.',$findTwo) == $v2['settingkey']){
								$databaseRowFlexArray[$k] = $v2['settingvalue'];
								continue;
							}
						}
					}
					#$result['databaseRow'][$flexformKey]['data'] = self::array_replace_key($result['databaseRow'][$flexformKey]['data'], $v['settingkey'], $v['settingvalue']);
				}
				
				// Конвертируем из дотов...
				$databaseRowFlexArray = BaseUtility::convertDotToArray($databaseRowFlexArray);
				$result['databaseRow'][$flexformKey] = $databaseRowFlexArray;
				#print "<pre>";
				#print_r($databaseRowFlexArray);
				#exit();
			}
		}
		return $result;
	}
	
	// Обзод и замена значения в массиве
	public static function array_replace_key($Array, $Find, $Replace)
	{
		if(is_array($Array)){
			foreach($Array as $Key=>$Val) {
				if(is_array($Array[$Key]) && !isset($Array[$Key]['vDEF']) && !isset($Array[$Key]['el'])){
					$Array[$Key] = self::array_replace_key($Array[$Key], $Find, $Replace);
				}else{
					// print $Key.'<br />';
					if($Key == $Find && isset($Array[$Key]['vDEF'])) 
					{
						$Array[$Key]['vDEF'] = $Replace;
					}
					if($Key == $Find && isset($Array[$Key]['el'])) 
					{
						print $Find;
						exit();
						$Array[$Key]['el'] = 1;
					}
				}
			}
		}
		return $Array;
	}
	

	// Сообщение...
    public function flashMessage($title = '', $msg, $type = FlashMessage::INFO)
    {
		#$message = GeneralUtility::makeInstance(FlashMessage::class,
		# 	$msg,
		# 	$title, // header is optional
		# 	$type,
		# 	TRUE // whether message should be stored in session
		#);
		
		/** @var $flashMessageService FlashMessageService */
		#$flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
		#$flashMessageService->getMessageQueueByIdentifier()->enqueue($message);
	}
}