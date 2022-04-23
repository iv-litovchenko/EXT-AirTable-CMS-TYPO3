<?php
namespace Litovchenko\AirTable\Hooks\Backend;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Resource\Folder;

class GetDefaultUploadFolder
{
	/**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'Hooks',
		'name' => 'FAL стандартизированный путь к загрузке изображений вида fileadmin/user_upload/record.table_name.field_name/',
		'description' => '',
		'onlyBackend' => [
			'TYPO3_CONF_VARS|SC_OPTIONS|t3lib/class.t3lib_userauthgroup.php|getDefaultUploadFolder::getDefaultUploadFolder'
		]
	];
	
    /**
     * set default upload folder
     *
     * @param array $params
     * @param BackendUserAuthentication $backendUserAuthentication
     * @return Folder
     */
    public function getDefaultUploadFolder($params, BackendUserAuthentication $backendUserAuthentication)
    {
        $uploadFolder = $params['uploadFolder'];
		$pid = $params['pid'];
        $table = $params['table'];
        $field = $params['field'];
		
		// \Litovchenko\AirTable\Utility\BaseUtility::fileReWrite($GLOBALS['_SERVER']['DOCUMENT_ROOT'].'/1.txt',print_r($GLOBALS['_GET'],true));
		
		if (!empty($uploadFolder)){
			if(isset($GLOBALS['TCA'][$table]['ctrl']['AirTable.Class'])){
				if($table == "pages"){
					$subFolder = 'air_table/Content/Pages-All/';
				} elseif($table == "tt_content"){
					$subFolder = 'air_table/Content/Pages-Content-'.intval($pid).'/';
				} else {
					$ext = $GLOBALS['TCA'][$table]['ctrl']['AirTable.Ext'];
					$subDomain = $GLOBALS['TCA'][$table]['ctrl']['AirTable.SubDomain'];
					$className = end(explode("\\",$GLOBALS['TCA'][$table]['ctrl']['AirTable.Class']));
					if(isset($subDomain)){
						$subFolder = $ext.'/'.$subDomain.'/'.$className.'/';
					} else {
						$subFolder = $ext.'/'.$className.'/';
					}
				}
			}
			if ($uploadFolder->hasFolder($subFolder)) {
				$uploadFolderResult = $uploadFolder->getSubfolder($subFolder);
			} else {
				$uploadFolder->createFolder($subFolder);
				$uploadFolderResult = $uploadFolder->getSubfolder($subFolder);
			}
		}

        return $uploadFolderResult;
    }
}
