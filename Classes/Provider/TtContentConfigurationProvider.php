<?php
namespace Litovchenko\AirTable\Provider;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use FluidTYPO3\Flux\Provider\PageProvider;
use FluidTYPO3\Flux\Provider\ProviderInterface;
use FluidTYPO3\Flux\Form;
use FluidTYPO3\Flux\Form\Container\Grid;
use FluidTYPO3\Flux\Integration\PreviewView;
use Litovchenko\AirTable\Domain\Model\SysFluxSetting;
use Litovchenko\AirTable\Utility\BaseUtility;

///////////////////////
// https://www.medienreaktor.de/blog/dynamische-backend-formulare-in-typo3-mit-flux
///////////////////////

class TtContentConfigurationProvider extends \FluidTYPO3\Flux\Provider\ContentProvider
{
	use Traits\FullControllerNameTrait;
	use Traits\GetFormTrait;
	use Traits\GetGridTrait;
	
	protected $fullControllerName = null;
	protected $controllerAction = 'index';
	
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
     * @param array $row
     * @return \FluidTYPO3\Flux\Form|NULL
    public function getForm(array $row)
    {
		return self::getFormTrait($row);
	}
     */
	
    /**
     * @param array $row
     * @return Grid
    public function getGrid(array $row)
    {
		return self::getGridTrait($row);
	}
     */

    /**
     * @param array $row
     * @throws \RuntimeException
     * @return string
     */
    public function getControllerActionFromRecord(array $row)
    {
		$cTypeSignature = $row['CType'];
		$pluginSignature = $row['list_type'];
		
		
		if(TYPO3_MODE === 'FE' && $row['CType'] == 'list' && $row['list_type'] == $pluginSignature)
		{
			$requestParameters = (array) GeneralUtility::_GET('tx_'.$pluginSignature);
			if(isset($requestParameters['action'])) {
				return $requestParameters['action'];
			}
		
			$filter = [];
			$filter['select'] = ['settingvalue'];
			$filter['where.10'] = ['source_table','=','tt_content'];
			$filter['where.20'] = ['source_field','=','pi_flexform'];
			$filter['where.30'] = ['source_signature','=',$pluginSignature];
			$filter['where.40'] = ['source_record','=',$row['uid']];
			$filter['where.50'] = ['settingkey','=','switchableControllerActions'];
			$rowSwitchableControllerActions = SysFluxSetting::recSelect('first',$filter);
			$rowSwitchableControllerActions = end(explode('->',$rowSwitchableControllerActions['settingvalue']));
			if(isset($rowSwitchableControllerActions)) {
				return $rowSwitchableControllerActions;
			}
			
		} else {
			if($row['CType'] == 'list' && $row['list_type'] == $pluginSignature){
				return 'index';
			} else {
				$action = lcfirst(basename($this->getTemplatePathAndFilename($row)));
				$action = current(explode('.',$action));
				return $action;
			}
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
		$cTypeSignature = $row['CType'];
		$pluginSignature = $row['list_type'];
		if ( strstr($cTypeSignature,'_elements_') || strstr($cTypeSignature,'_gridelements_') )
		{
			// Ищем все характеристики
			$filter = [];
			$filter['select'] = ['settingkey','settingvalue'];
			$filter['where.10'] = ['source_table','=','tt_content'];
			$filter['where.20'] = ['source_field','=','pi_flexform'];
			if( strstr($pluginSignature,'_plugins_') ) {
				$filter['where.30'] = ['source_signature','=',$pluginSignature];
			} else {
				$filter['where.30'] = ['source_signature','=',$cTypeSignature];
			}
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