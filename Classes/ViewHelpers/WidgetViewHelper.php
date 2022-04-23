<?php
namespace Litovchenko\AirTable\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;
use Litovchenko\AirTable\Utility\BaseUtility;

class WidgetViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 			=> 'FrontendViewHelper',
		'name' 				=> 'Widget',
		'description' 		=> 'Хелпер для запуска виджета (контроллера)'
	];
	
	public function initializeArguments()
	{
		/**
		 * @param string $name Name of the argument
		 * @param string $type Type of the argument
		 * @param string $description Description of the argument
		 * @param bool $required If TRUE, argument is required. Defaults to FALSE.
		 * @param mixed $defaultValue Default value of argument
		 */
		$this->registerArgument('action', 'string', '', false, 'index');
		parent::initializeArguments();
	}
	
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
		$class = get_called_class();
		$temp = explode('\\',$class);
		
		$extensionName = preg_replace('/^WgsExt/is','',$temp[count($temp)-2]);
		$subfolder = 'Widgets';
		$pluginName = $subfolder.'_'.preg_replace('/ViewHelper$/is','',$temp[count($temp)-1]); // Controller
		$action = $this->arguments['action'];
		$className = key($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$extensionName]['plugins'][$pluginName]['controllers']);
		
		$fullClassName = $GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces.AirTable']['WgsExt'.$extensionName][$class];
		$signature = BaseUtility::getTableNameFromClass($fullClassName);
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');    
		$configurationManager = $objectManager->get('TYPO3\CMS\Extbase\Configuration\ConfigurationManager');
		$settingsConf = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		// $settingsConf['plugin.']['tx_xxxx.']['settings.']['storagePid']
		
		$tsObject = $settingsConf['tt_content.']['list.']['20.'][$signature]; // USER
		$tsConf = $settingsConf['tt_content.']['list.']['20.'][$signature.'.'];
		// $tsConf['settings.'] += $settingsConf['plugin.']['tx_'.$signature.'.']['settings.'];
		
		// $tsConf['userFunc'] = 'TYPO3\CMS\Extbase\Core\Bootstrap->run';
		// $tsConf['extensionName'] = $extensionName;
		// $tsConf['pluginName'] = $pluginName;
		
		// Переданные параметры-настройки
		$originalSettings = $this->arguments;
		if(is_array($originalSettings) && !empty($originalSettings)){
			// Important: #78650 - TypoScriptService class moved from Extbase to Core
			$typoScriptService = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\TypoScriptService::class);
			$typoScriptArray = $typoScriptService->convertPlainArrayToTypoScriptArray($originalSettings);
			$tsConf['settings.'] = $typoScriptArray;
		}
			
		// Так устанавливаем действие по умолчанию
		// \TYPO3\CMS\Extbase\Service\ExtensionService -> getDefaultActionNameByPluginAndController()
		if($action != 'index'){
			
			// Добавляем в начало массива
			// $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][***]['plugins'][***]['controllers'][***]['actions'][0] = 'two';
			array_unshift($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$extensionName]['plugins'][$pluginName]['controllers'][$className]['actions'], $action);
		} else {
			
			// Ставим действие по умолчанию
			array_unshift($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions'][$extensionName]['plugins'][$pluginName]['controllers'][$className]['actions'], 'index');
		}
		
		return $GLOBALS['TSFE']->cObj->cObjGetSingle($tsObject, $tsConf);
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