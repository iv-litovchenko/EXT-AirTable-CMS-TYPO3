<?php
namespace Litovchenko\AirTable\Controller\Modules;

use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use Illuminate\Database\Capsule\Manager as DB;
use Litovchenko\AirTable\Utility\BaseUtility;

class SqlController extends ActionController
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendModule',
		'name' 			=> 'Обновление ext_tables.sql',
		'description' 	=> '',
		'access' 		=> 'systemMaintainer',
		'section'		=> 'unseen',
		'position'		=> '300'
	];

    /**
     * Main action for administration
     */
    public function indexAction()
    {
		// (ext_tables.sql) в TYPO3 v11 убрали Signal slots
		// if(TYPO3_REQUESTTYPE_INSTALL === (TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_INSTALL)){
		#if(TYPO3_MODE === 'BE'){
			\Litovchenko\AirTable\Service\DatabaseSchemaService::addMysqlTableAndFields([]);
		#}
		
		/*
		// Обновление "DataType"
		\Litovchenko\AirTable\Domain\Model\Content\DataType::truncate();
		$j = 1;
		$typo3conf_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/'; // typo3conf/ext path
		foreach (glob($typo3conf_path."*") as $filename) {
			$extName = basename($filename);
			if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded($extName)){
				if(file_exists($filename.'/Configuration/DataTypeList.php')){
					$temp = require($filename.'/Configuration/DataTypeList.php');
					if(!empty($temp)){
						foreach($temp as $k => $v){
							$keyNumHash = intval(BaseUtility::numHash($k,8));
							$data = [];
							$data['uid'] = $keyNumHash;
							$data['uidkey'] = $k;
							$data['RType'] = 2;
							$data['title'] = $v['name'];
							$data['service_note'] = $v['description'];
							$data['props_default'] = implode(',',$v['baseFields']);
							$data['props_default_cat'] = implode(',',$v['baseCatFields']);
							$data['propref_group'] = intval(BaseUtility::numHash($v['settings']['group'],8));
							$data['prop_add_new_button'] = intval($v['settings']['fastRecording']);
							$data['sorting'] = $j;
							\Litovchenko\AirTable\Domain\Model\Content\DataType::recInsert($data);
							$j++;
						}
					}
				}
			}
		}
		
		// Обновление "DataTypeGroup"
		\Litovchenko\AirTable\Domain\Model\Content\DataTypeGroup::truncate();
		$j = 1;
		$typo3conf_path = $GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/typo3conf/ext/'; // typo3conf/ext path
		foreach (glob($typo3conf_path."*") as $filename) {
			$extName = basename($filename);
			if(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded($extName)){
				if(file_exists($filename.'/Configuration/DataTypeGroupList.php')){
					$temp = require($filename.'/Configuration/DataTypeGroupList.php');
					if(!empty($temp)){
						foreach($temp as $k => $v){
							$keyNumHash = intval(BaseUtility::numHash($k,8));
							$data = [];
							$data['uid'] = $keyNumHash;
							$data['uidkey'] = $k;
							$data['title'] = $v;
							$data['sorting'] = $j;
							\Litovchenko\AirTable\Domain\Model\Content\DataTypeGroup::recInsert($data);
							$j++;
						}
					}
				}
			}
		}
		
		// Обновление "SysEntity"
		\Litovchenko\AirTable\Domain\Model\Eav\SysEntity::truncate();
		$j = 1;
		$allClasses = BaseUtility::getLoaderClasses2();
		foreach($allClasses as $classes) {
			foreach($classes as $class){
				$thisIs = $class::$TYPO3['thisIs'];
				$_isEntity = \Litovchenko\AirTable\Utility\BaseUtility::$entityAnnotations[$thisIs]['_isEntity'];
				if($_isEntity == 1){
					$signature = BaseUtility::getTableNameFromClass($class);
					$keyNumHash = intval(BaseUtility::numHash($signature,8));
					$data = [];
					$data['uid'] = $keyNumHash;
					$data['uidkey'] = $signature;
					$data['RType'] = $thisIs;
					$data['title'] = $class::$TYPO3['name'];
					$data['prop_ext'] = BaseUtility::getExtNameFromClassPath($class);
					$data['sorting'] = $j;
					\Litovchenko\AirTable\Domain\Model\Eav\SysEntity::recInsert($data);
					$j++;
				}
			}
		}
		
		// Обновление "SysAttribute"
		\Litovchenko\AirTable\Domain\Model\Eav\SysAttribute::truncate();
		\Litovchenko\AirTable\Domain\Model\Eav\SysAttributeOption::truncate();
		$j = 1;
		$allClasses = BaseUtility::getLoaderClasses2();
		foreach($allClasses as $classes) {
			foreach($classes as $class){
				$thisIs = $class::$TYPO3['thisIs'];
				$_isEntity = \Litovchenko\AirTable\Utility\BaseUtility::$entityAnnotations[$thisIs]['_isEntity'];
				if($_isEntity == 1){
					$fields = \Litovchenko\AirTable\Utility\BaseUtility::getClassAllFieldsNew($class, 1); // Только атрибуты
					if(count($fields)>0){
						foreach($fields as $k => $v){
							$annotationLabel = BaseUtility::getClassFieldAnnotationValueNew($class,$v,'AirTable\Field\Label');
							$annotationField = BaseUtility::getClassFieldAnnotationValueNew($class,$v,'AirTable\Field');
							$fieldClass = 'Litovchenko\AirTable\Domain\Model\Fields\\'.$annotationField;
							$signature = BaseUtility::getTableNameFromClass($class);
							$keyNumHash = intval(BaseUtility::numHash($signature.$v,8));
							$keyNumHashEntity = intval(BaseUtility::numHash($signature,8));
							$data = [];
							$data['uid'] = $keyNumHash;
							$data['uidkey'] = $v;
							$data['RType'] = $annotationField;
							$data['title'] = $annotationLabel;
							$data['prop_configuration_path'] = $class.'->'.$v;
							$data['propref_entity'] = $keyNumHashEntity;
							$data['sorting'] = $j;
							$insertId = \Litovchenko\AirTable\Domain\Model\Eav\SysAttribute::recInsert($data);
							
							// Если существуют опции
							$i = 1;
							$annotationItems = BaseUtility::getClassFieldAnnotationValueNew($class,$v,'AirTable\Field\Items');
							if(count($annotationItems)>0 && !empty($annotationItems)){
								foreach($annotationItems as $kOption => $vOption){
									$data = [];
									#$data['uid'] = $keyNumHash;
									$data['uidkey'] = $kOption;
									$data['title'] = $vOption[0];
									$data['proprefinv_attribute'] = $insertId;
									$data['sorting'] = $i;
									\Litovchenko\AirTable\Domain\Model\Eav\SysAttributeOption::recInsert($data);
									$i++;
								}
							}
							$j++;
						}
					}
				}
			}
		}
		*/
		
    }

}