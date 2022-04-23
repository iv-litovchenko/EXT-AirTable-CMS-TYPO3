<?php
namespace Litovchenko\AirTable\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use Litovchenko\AirTable\Utility\BaseUtility;

class AjaxViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 			=> 'FrontendViewHelper',
		'name' 				=> 'Ajax',
		'description' 		=> 'Хелпер для запуска Ajax'
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
		// eIdAjax
		$eIdAjax = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('eIdAjax');
		if(!empty($eIdAjax)){
			
			// Todo
			if (version_compare(TYPO3_version, '10.0.0', '<')) {
				print '// Todo - доработать Ajax в 9, 8 версиях. Нет возможности получить полное имя класса из TYPO3_CONF_VARS|EXTCONF|extbase|extensions';
				exit();
			}
			
			// Параметры запроса запроса:
			$eIdAjaxArgs = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('eIdAjaxPath');
			$eIdAjaxArgs = explode('.',$eIdAjaxArgs);
			
			// $eIdAjaxArgs[0] // Ext.Projiv.Widgets.RandPhoto.index
			$extensionName = $eIdAjaxArgs[1];
			$subfolder = $eIdAjaxArgs[2];
			$pluginName = $subfolder.'_'.$eIdAjaxArgs[3]; // .'Controller'
			$action = $eIdAjaxArgs[4];
			$className = key($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$extensionName]['plugins'][$pluginName]['controllers']);
			
			// Поиск настроек
			$temp = \TYPO3\CMS\Core\Utility\GeneralUtility::_POST();
			if (!empty($temp)) {
				foreach($temp as $k => $v){
					if($k == 'eIdAjaxSettings'){
						$originalSettings = $v;
						break;
					}
					foreach($v as $k2 => $v2){
						if($k2 == 'eIdAjaxSettings'){
							$originalSettings = $v2;
							break;
						}
					}
				}
			} else {
				$eIdAjaxSettings = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('eIdAjaxSettings');
				$originalSettings = $eIdAjaxSettings;
			}
			
			// 1) Проверка расширения
			if(!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$extensionName])){
				return ($this->errorWrap('The extension ['.htmlspecialchars($extensionName).'] not found!'));
			}
			
			// 2) Проверка контроллера
			if(!isset($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$extensionName]['plugins'][$pluginName])){
				return ($this->errorWrap('Plugin ['.htmlspecialchars($pluginName).'] not found!'));
			}
			
			// 3) Проверка действия
			if(isset($action)){
				$annotationAjaxActions = BaseUtility::getClassAnnotationValueNew($className,'AirTable\AjaxActions');
				$annotationAjaxActions = explode(',',$annotationAjaxActions);
				if(!in_array($action,$annotationAjaxActions)){
					return ($this->errorWrap('Action ['.htmlspecialchars($action).'] is not allowed for this controller ['.htmlspecialchars($className).']!'));
				}
			} else {
				$action = 'index';
			}
			
			// Так устанавливаем действие по умолчанию
			// \TYPO3\CMS\Extbase\Service\ExtensionService -> getDefaultActionNameByPluginAndController()
			if($action != 'index'){
			
				// Добавляем в начало массива
				// $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][***]['plugins'][***]['controllers'][***]['actions'][0] = 'two';
				array_unshift($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$extensionName]['plugins'][$pluginName]['controllers'][$className]['actions'], $action);
			}
			
			$tsObject = 'USER';
			$tsConf = [];
			$tsConf['userFunc'] = 'TYPO3\CMS\Extbase\Core\Bootstrap->run';
			$tsConf['extensionName'] = $extensionName;
			$tsConf['pluginName'] = $pluginName;
			
			// Переданные параметры-настройки
			if(is_array($originalSettings) && !empty($originalSettings)){
				// Important: #78650 - TypoScriptService class moved from Extbase to Core
				$typoScriptService = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\TypoScriptService::class);
				$typoScriptArray = $typoScriptService->convertPlainArrayToTypoScriptArray($originalSettings);
				$tsConf['settings.'] = $typoScriptArray;
			}
			
			return $GLOBALS['TSFE']->cObj->cObjGetSingle($tsObject, $tsConf);
		}
		
		return '';
    }
	
	/**
		$content
	*/
	public function errorWrap($content = '') {
		return "<tagErrorWrap style='display: block; background-color: red; padding: 5px 10px 5px 10px; color: white;'>
			".rand(10000,99999)." eIdAjax say: ".$content." 
		</tagErrorWrap>";
	}
	
	public function wrap($content, $wgsClass, $arguments) 
	{	
		// При условии, что включена шестеренка (настройки)
		if ($GLOBALS['BE_USER']->uc['phptemplate_checkOption'] == 1) {
			$srcAdmPath = '/typo3conf/ext/air_table/Resources/Public/Admin/';
			return "
				<tagWrapTtContentElement class='tagWrapTtContentElement'>
					<!--TYPO3_NOT_SEARCH_begin-->
					<tagEditIcon_wrap class='phptemplate_tagEditIcon_wrap_block'>
					<tagEditIcon class='phptemplate_tagEditIcon' style='border: black 1px solid;'>
					<nobr>
						<tagEditIconA class='tagEditIconA'>
						<tagEditIconImg class='tagEditIconImg' style='background-image: url(".$srcAdmPath."isPhpFilePathIcon.png);'></tagEditIconImg>
						</tagEditIconA>WidgetViewHelper (подключен блок)&nbsp;
						<tagEditIconSpan class='phptemplate_tagEditIconSpan hoverInfoNewLine'>
							".$wgsClass."<br /> 
							<tagEditIconPre style='white-space: pre;'>arguments: ".print_r($arguments,true)."</tagEditIconPre>
						</tagEditIconSpan>
					</nobr>
					</tagEditIcon>
					</tagEditIcon_wrap>
					<tagEditIcon_br style='clear: left;'></tagEditIcon_br>
					<!--TYPO3_NOT_SEARCH_end-->
					<tagWrapTtContentElement_dashed_Top		class='tagWrapTtContentElement_dashed_Top'></tagWrapTtContentElement_dashed_Top>
					<tagWrapTtContentElement_dashed_Right	class='tagWrapTtContentElement_dashed_Right'></tagWrapTtContentElement_dashed_Right>
					<tagWrapTtContentElement_dashed_Bottom	class='tagWrapTtContentElement_dashed_Bottom'></tagWrapTtContentElement_dashed_Bottom>
					<tagWrapTtContentElement_dashed_Left	class='tagWrapTtContentElement_dashed_Left'></tagWrapTtContentElement_dashed_Left>
					" . $content . "
				</tagWrapTtContentElement>
			";
		} else {
			return $content;
		}
	}
}