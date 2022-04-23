<?php
namespace Litovchenko\AirTable\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use Litovchenko\AirTable\Utility\BaseUtility;

class AdminPanelToolsViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 			=> 'FrontendViewHelper',
		'name' 				=> 'AdminPanelTools'
	];
	
    /**
     * @var boolean
     */
    protected $escapeChildren = false;

    /**
     * @var boolean
     */
    protected $escapeOutput = false;
	
    public function render()
    {
		if($GLOBALS['BE_USER']->user['uid'] > 0) {
			return "<!--@@@ADMINPANEL-TOOLS@@@-->";
		} else {
			return '';
		}
    }
	
	public static function processOutput()
	{
		$srcAdmPath = '/typo3conf/ext/air_table/Resources/Public/Admin/';
		
		$content = "";
		$content .= "
			<script type='text/javascript'>
			function tagAdminPanel_hideOrShowContainerWithText( objName ){
				var valueDisblayContainer = document.getElementById( objName ).style.display;
				if (valueDisblayContainer == 'block' ){
					document.getElementById(objName).style.display='none';
				} else {
					document.getElementById(objName).style.display='block';
				}
			}
			</script>";
		$content .= "<!--TYPO3_NOT_SEARCH_begin-->";
		$content .= "<link rel='stylesheet' type='text/css' href='".$srcAdmPath."adminPanelTools.css' media='all'>";
		
		if($GLOBALS['BE_USER']->uc['phptemplate_checkOption'] == 1){
			$content .= "<tagAdminPanelTools class='phptemplate_tagAdminPanelTools' style='opacity: 0.5;'>";
		} else {
			$content .= "<tagAdminPanelTools class='phptemplate_tagAdminPanelTools'>";
		}

		$content .= "<tagAdminPanelTools_webProjectLabel_wrap class='tagAdminPanelTools_webProjectLabel_wrap'>
									<tagAdminPanelTools_webProjectLabel class='tagAdminPanelTools_webProjectLabel'>
										". $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename']. "
									</tagAdminPanelTools_webProjectLabel>
								</tagAdminPanelTools_webProjectLabel_wrap>";
								
		#$content .= "<tagAdminPanelTools_webProjectLabel_wrap class='tagAdminPanelTools_webProjectLabel_wrap' style='float: left !important;'>
		#							<tagAdminPanelTools_webProjectLabel class='tagAdminPanelTools_webProjectLabel'>
		#								Страницы
		#							</tagAdminPanelTools_webProjectLabel>
		#						</tagAdminPanelTools_webProjectLabel_wrap>";
								
		#$content .= "<tagAdminPanelTools_webProjectLabel_wrap class='tagAdminPanelTools_webProjectLabel_wrap' style='float: left !important;'>
		#							<tagAdminPanelTools_webProjectLabel class='tagAdminPanelTools_webProjectLabel'>
		#								Структура контента
		#							</tagAdminPanelTools_webProjectLabel>
		#						</tagAdminPanelTools_webProjectLabel_wrap>";
								
		#$content .= "<tagAdminPanelTools_webProjectLabel_wrap class='tagAdminPanelTools_webProjectLabel_wrap' style='float: left !important;'>
		#							<tagAdminPanelTools_webProjectLabel class='tagAdminPanelTools_webProjectLabel'>
		#								Материалы
		#							</tagAdminPanelTools_webProjectLabel>
		#						</tagAdminPanelTools_webProjectLabel_wrap>";
								
		#$content .= "<tagAdminPanelTools_webProjectLabel_wrap class='tagAdminPanelTools_webProjectLabel_wrap' style='float: left !important;'>
		#							<tagAdminPanelTools_webProjectLabel class='tagAdminPanelTools_webProjectLabel'>
		#								Заметки
		#							</tagAdminPanelTools_webProjectLabel>
		#						</tagAdminPanelTools_webProjectLabel_wrap>";
								
		#$content .= "<tagAdminPanelTools_webProjectLabel_wrap class='tagAdminPanelTools_webProjectLabel_wrap' style='float: left !important;'>
		#							<tagAdminPanelTools_webProjectLabel class='tagAdminPanelTools_webProjectLabel'>
		#								Отладка
		#							</tagAdminPanelTools_webProjectLabel>
		#						</tagAdminPanelTools_webProjectLabel_wrap>";
								
		#$content .= '<br style="clear: left !important;" />';
		
		// Если включен режим редактирования
		if($GLOBALS['BE_USER']->uc['phptemplate_mode_editing'] == 2){
			$CSS_style = '';
		} else {
			$CSS_style = ' padding-left: 15px !important; padding-top: 5px !important; padding-bottom: 5px !important; ';
		}
		
		// Извлекаем данные
		$rows = \Litovchenko\AirTable\Domain\Model\SysNote::with('propmedia_tx_airtable_files')->orderBy('crdate','asc')->get()->toArray();
		foreach($rows as $row){
			
			$editIconContent = '';
			if($GLOBALS['BE_USER']->uc['phptemplate_mode_editing'] == 2){
				$editIcon = new \Litovchenko\AirTable\ViewHelpers\EditIconInlineViewHelper;
				$editIcon->model = 'Litovchenko\AirTable\Domain\Model\SysNote';
				$editIcon->recordId = $row['uid'];
				$editIcon->hideNewIcon = 1;
				$editIcon->styleTop = 1;
				$editIcon->styleLeft = 0;
				$editIconContent = $editIcon->processOutput();
			}
			
			// content
			$strContent = '';
				
			// parse RTE
			$cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
			$tparseFunc = $GLOBALS['TSFE']->tmpl->setup['lib.']['parseFunc_RTE.'];
			$message = trim($row['message']) == '' ? '-' : $row['message'];
			$message = htmlspecialchars($message);
			# $message = $cObj->parseFunc($message, $tparseFunc);
			$strContent .= $message;
			
			// files
			if(count($row['propmedia_tx_airtable_files'])>0){
				$strContent .= '<p>';
				foreach($row['propmedia_tx_airtable_files'] as $k => $v){
					if(!empty($v['alternative'])){
						$title = $v['alternative'];
					}elseif(!empty($v['file']['metadata']['title'])){
						$title = $v['file']['metadata']['title'];
					}else{
						$title = $v['file']['name'];
					}
					$srcAdmPath = '/typo3conf/ext/air_table/Resources/Public/Admin/';
					$pathFile = \Litovchenko\AirTable\Domain\Model\Fal\SysFile::getPathById($v['file']['uid']);
					// $strContent .= '<img src="'.\Litovchenko\AirTable\Domain\Model\Fal\SysFile::urlMiniature($v['file']['uid']).'">';
					$strContent .= '<a href="'.$pathFile.'" style="line-height: 28px;"><img src="'.$srcAdmPath.'noteFileIcon.png" width="20" style="vertical-align: middle;"> '.$title.'</a><br />';
				}
				$strContent .= '</p>';
			}
			
			$content .= "<tagAdminPanelTools_tabItem_wrap class='tagAdminPanelTools_tabItem_wrap'>
							<tagAdminPanelTools_tabItemLabel class='tagAdminPanelTools_tabItemLabel' style='".$CSS_style."'>
								".$editIconContent."
								<tagAdminPanelTools_tabItemLabel_Wrap onclick=\"tagAdminPanel_hideOrShowContainerWithText('tagAdminPanelTools_tabItemContent_".$row['uid']."');\" style='display: inline-block; cursor: pointer;'>
								".$row['subject']."
								</tagAdminPanelTools_tabItemLabel_Wrap>
							</tagAdminPanelTools_tabItemLabel>
							<tagAdminPanelTools_tabItemContent class='tagAdminPanelTools_tabItemContent' id='tagAdminPanelTools_tabItemContent_".$row['uid']."'>".$strContent."</tagAdminPanelTools_tabItemContent>
						</tagAdminPanelTools_tabItem_wrap>
			";
		}
		
		$newIconContent = '';
		if($GLOBALS['BE_USER']->uc['phptemplate_mode_editing'] == 2){
			$newIcon = new \Litovchenko\AirTable\ViewHelpers\NewIconViewHelper;
			$newIcon->model = 'Litovchenko\AirTable\Domain\Model\SysNote';
			$newIcon->title = $GLOBALS['LANG']->sL('LLL:EXT:air_table/Resources/Private/Language/Localling.EditAdmin.xml:plugins.newIcon.addNote');
			$newIconContent = $newIcon->processOutput();
		}

		$content .= "<tagAdminPanelTools_wrapNewIcon class='tagAdminPanelTools_wrapNewIcon'>";
		$content .= $newIconContent;
		$content .= "</tagAdminPanelTools_wrapNewIcon>";
		
		$content .= "<tagAdminPanelTools_webProjectLabel_wrap class='tagAdminPanelTools_webProjectLabel_wrap'>
									<tagAdminPanelTools_webProjectLabel class='tagAdminPanelTools_webProjectLabel'>
										REQUEST
									</tagAdminPanelTools_webProjectLabel>
								</tagAdminPanelTools_webProjectLabel_wrap>";
								
		 
			// _GET
			$content .= "<tagAdminPanelTools_tabItem_wrap class='tagAdminPanelTools_tabItem_wrap'>
							<tagAdminPanelTools_tabItemLabel class='tagAdminPanelTools_tabItemLabel'>
								<tagAdminPanelTools_tabItemLabel_Wrap style='display: inline-block;'>
									&nbsp;&nbsp; \$_GET	
								</tagAdminPanelTools_tabItemLabel_Wrap>
							</tagAdminPanelTools_tabItemLabel>
							<tagAdminPanelTools_tabItemContent class='tagAdminPanelTools_tabItemContent' style='display: block;'>".print_r($GLOBALS['_GET'],true)."</tagAdminPanelTools_tabItemContent>
						</tagAdminPanelTools_tabItem_wrap>
			";
			
			// _POST
			$content .= "<tagAdminPanelTools_tabItem_wrap class='tagAdminPanelTools_tabItem_wrap'>
							<tagAdminPanelTools_tabItemLabel class='tagAdminPanelTools_tabItemLabel'>
								<tagAdminPanelTools_tabItemLabel_Wrap style='display: inline-block;'>
									&nbsp;&nbsp; \$_POST	
								</tagAdminPanelTools_tabItemLabel_Wrap>
							</tagAdminPanelTools_tabItemLabel>
							<tagAdminPanelTools_tabItemContent class='tagAdminPanelTools_tabItemContent' style='display: block;'>".print_r($GLOBALS['_POST'],true)."</tagAdminPanelTools_tabItemContent>
						</tagAdminPanelTools_tabItem_wrap>
			";
		
		$content .= "</tagAdminPanelTools>";
		$content .= "<!--TYPO3_NOT_SEARCH_end-->";
		
		return $content;
	}
	
}