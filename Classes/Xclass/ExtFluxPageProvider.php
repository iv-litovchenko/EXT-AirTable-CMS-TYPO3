<?php
namespace Litovchenko\AirTable\Xclass;

use FluidTYPO3\Flux\Provider\AbstractProvider;
use FluidTYPO3\Flux\Provider\ProviderInterface;
use FluidTYPO3\Flux\Form;
use FluidTYPO3\Flux\Form\Container\Grid;
use FluidTYPO3\Flux\Integration\PreviewView;
use FluidTYPO3\Flux\Service\FluxService;
use FluidTYPO3\Flux\Service\PageService;
use FluidTYPO3\Flux\Utility\ExtensionNamingUtility;
use FluidTYPO3\Flux\Utility\RecursiveArrayUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Fluid\View\TemplatePaths;
use Litovchenko\AirTable\Utility\BaseUtility;

class ExtFluxPageProvider extends \FluidTYPO3\Flux\Provider\PageProvider
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' => 'Xclass',
		'name' => '',
		'description' => '',
		'object' => 'FluidTYPO3\Flux\Provider\PageProvider'
	];
	
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
		
		if($providerTableName == 'pages' && $table == 'pages')
		{
			if(strstr($row['tx_fed_page_controller_action'],'_pages_')){
				return false;
			}
			
			if(strstr($row['tx_fed_page_controller_action'][0],'_pages_')){
				return false;
			}
			
			if(trim($row['tx_fed_page_controller_action'][0]) == ''){
				return true;
			}
			
			if(trim($row['tx_fed_page_controller_action']) == ''){
				return true;
			}
			
			// Todo
			// По хорошему сюда нужно добавить еще проверку на наличие провайдера...
			// if()
		}
		
		return parent::trigger($row, $table, $field, $extensionKey);
    }
	
    /**
     * @param array $row
     * @return Form|null
     */
    public function getForm(array $row)
    {
		if(strstr($row['tx_fed_page_controller_action'][0],'_pages_') || strstr($row['tx_fed_page_controller_action'],'_pages_')){
			return [];
		} elseif(trim($row['tx_fed_page_controller_action'][0]) == '' || trim($row['tx_fed_page_controller_action']) == ''){
			$form = parent::getForm($row);
			if ($form) {
				$form->setOption(PreviewView::OPTION_PREVIEW, [PreviewView::OPTION_MODE => 'none']);
				$form = $this->setDefaultValuesInFieldsWithInheritedValues($form, $row);
			}
			return $form;
		} else {
			return parent::getForm($row);
		}
    }
	
    /**
     * @param array $row
     * @return Form|null
     */
    public function getGrid(array $row)
    {
		if(strstr($row['tx_fed_page_controller_action'][0],'_pages_') || strstr($row['tx_fed_page_controller_action'],'_pages_')){
			return [];
		} elseif(trim($row['tx_fed_page_controller_action'][0]) == '' || trim($row['tx_fed_page_controller_action']) == ''){
			return parent::getGrid($row);
		} else {
			return parent::getGrid($row);
		}
    }
}
