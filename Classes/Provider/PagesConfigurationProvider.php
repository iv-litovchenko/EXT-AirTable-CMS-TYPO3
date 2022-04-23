<?php
namespace Litovchenko\AirTable\Provider;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use FluidTYPO3\Flux\Provider\PageProvider;
use FluidTYPO3\Flux\Provider\ProviderInterface;
use FluidTYPO3\Flux\Form;
use FluidTYPO3\Flux\Form\Container\Grid;
use FluidTYPO3\Flux\Hooks\HookHandler;
use FluidTYPO3\Flux\Integration\PreviewView;
use Litovchenko\AirTable\Domain\Model\SysFluxSetting;
use Litovchenko\AirTable\Utility\BaseUtility;

///////////////////////
// https://www.medienreaktor.de/blog/dynamische-backend-formulare-in-typo3-mit-flux
///////////////////////

class PagesConfigurationProvider extends \FluidTYPO3\Flux\Provider\PageProvider
{
	use Traits\FullControllerNameTrait;
	use Traits\GetFormTrait;
	use Traits\GetGridTrait;
	
	protected $fullControllerName = null;
	protected $controllerAction = 'index';
    protected $pageObjectType = null;
	
	# protected $controllerName = 'Gallery';
	# protected $tableName = 'tt_content'; // tx_data // tx_products_domain_model_product
	# protected $fieldName = 'pi_flexform'; // prop_flexform // prop_flexform
	# protected $extensionKey = 'projiv';
	# protected $listType = 'projiv_plugins_firstplugincontroller';
	# protected $contentObjectType = 'projiv_gallery'; //  Fill with the "CType" value that should trigger this Provider.
	# protected $templatePathAndFilename = 'EXT:dlrg_seminare/Configuration/FlexForms/flexform_pi1.html';
	# protected $templatePaths = array(
	# 	'layoutRootPath' => 'EXT:dlrg_seminare/Resources/Private/Layouts/',
	# 	'templateRootPath' => 'EXT:dlrg_seminare/Resources/Private/Templates/',
	# 	'partialRootPath' => 'EXT:dlrg_seminare/Resources/Private/Partials/',
	# );
	
	 /**
	 * Creates objects inserted into this Form, resulting in
	 * a nested set of PHP objects that correspond exactly
	 * to what would come out of parsing a Flux template
	 */
	 # public function initializeObject() {
	 # // $this->createField('Input', 'test')->setDefault('default value')->setRequired(TRUE);
	 # }

    /**
     * Returns TRUE that this Provider should trigger if:
     *
     * - table matches 'pages'
     * - field is NULL or matches self::FIELD_NAME
     * - a selection was made in the "template for this page" field
     *
     * @param array $row
     * @param string $table
     * @param string $field
     * @param string|NULL $extensionKey
     * @return boolean
     */
    public function trigger(array $row, $table, $field, $extensionKey = null)
    {
		/**
		 *	Если "tx_fed_page_controller_action" содержит
		 *	Vendor.Extension->action - отправляем на стандартный триггер
		 *	Иначе обрабатываем нашу сигнатуру
		*/
		
		$providerFieldName = $this->getFieldName($row);
		$providerTableName = $this->getTableName($row);
		$providerExtensionKey = $this->extensionKey;
		$providerPageObjectTypeName = $this->getPageObjectType();
		
		if($row['tx_fed_page_controller_action'] == $providerPageObjectTypeName){
			return true;
		}
		
		if($row['tx_fed_page_controller_action'][0] == $providerPageObjectTypeName){
			return true;
		}
		
		// if($row['tx_fed_page_controller_action'] == ''){
		// 	return true;
		// }
		
		return false;
    }
	
	/*
    public function getForm(array $row)
    {
		if($row['tx_fed_page_controller_action'] == '' || $row['tx_fed_page_controller_action'][0] == ''){
			$form = Form::create();
			$form->setOption(PreviewView::OPTION_PREVIEW, [PreviewView::OPTION_MODE => 'none']);
			return $form;
		}
		
		return parent::getForm($row);
    }
	
    public function getGrid(array $row)
    {
		if($row['tx_fed_page_controller_action'] == ''){
			return Grid::create();
		}
		
		return parent::getGrid($row);
    }
	*/

    /**
     * @param string $fullControllerName
     * @return string
     */
    public function setPageObjectType($pageObjectType)
    {
        $this->pageObjectType = $pageObjectType;
        return $this;
    }

    /**
     * @return string
     */
    public function getPageObjectType()
    {
        return $this->pageObjectType;
    }
	
    /**
     * @param array $row
     * @return string
     */
    public function getControllerExtensionKeyFromRecord(array $row)
    {
        return $this->extensionKey;
    }

    /**
     * @param array $row
     * @throws \RuntimeException
     * @return string
     */
    public function getControllerActionFromRecord(array $row)
    {
		$pluginSignature = $row['tx_fed_page_controller_action'];
		$requestParameters = (array) GeneralUtility::_GET('tx_'.$pluginSignature);
		if(TYPO3_MODE === 'FE' && $row['doktype'] == 1 && $row['tx_fed_page_controller_action'] == $pluginSignature && isset($requestParameters['action'])){
			return $requestParameters['action'];
		} else {
			return 'index';
		}
    }
	
    /**
     * Converts the contents of the provided row's Flux-enabled field,
     * at the same time running through the inheritance tree generated
     * by getInheritanceTree() in order to apply inherited values.
     *
     * @param array $row
     * @return array
     */
    public function getFlexFormValues(array $row)
    {
		$pluginSignature = $row['tx_fed_page_controller_action'];
		if ( strstr($pluginSignature,'_pages_') )
		{
			// Ищем все характеристики
			$filter = [];
			$filter['select'] = ['settingkey','settingvalue'];
			$filter['where.10'] = ['source_table','=','pages'];
			$filter['where.20'] = ['source_field','=','tx_fed_page_flexform'];
			$filter['where.30'] = ['source_signature','=',$pluginSignature];
			$filter['where.40'] = ['source_record','=',$row['uid']];
			$filter['orderBy'] = ['source_sorting','asc'];
			$rowsSetting = SysFluxSetting::recSelect('get',$filter);
			
			// Разбираем массив
			$arSettings = [];
			foreach($rowsSetting as $k => $v){
				$arSettings[$v['settingkey']] = $v['settingvalue'];
			}
			
			return BaseUtility::convertDotToArray($arSettings);
		} else {
			return parent::getFlexFormValues($row);
		}
    }
	
    /**
     * @param array $row
     * @return string|NULL
     */
    public function getTemplatePathAndFilename(array $row)
    {
		return $this->templatePathAndFilename;
	}
}