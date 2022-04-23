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

class DebugController extends ActionController
{
    /**
     * The magic variable TYPO3 
     * Parameters are described here 
     * @var array
     */
	public static $TYPO3 = [
		'thisIs' 		=> 'BackendModule',
		'name' 			=> 'Отладка кода',
		'description' 	=> 'Модуль для просмотра результатов работы кода написанного в файле: "typo3conf/UserDebug.php"',
		'access' 		=> 'admin',
		'section'		=> 'content',
		'position'		=> '70'
	];
	
    /**
     * TsConfig configuration
     *
     * @var array
     */
    protected $tsConfiguration = [];

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * Function will be called before every other action
     *
     */
    public function initializeAction()
    {
        parent::initializeAction();
    }

    /**
     * BackendTemplateContainer
     *
     * @var BackendTemplateView
     */
    protected $view;

    /**
     * @var IconFactory
     */
    public $iconFactory;

    /**
     * Backend Template Container
     *
     * @var BackendTemplateView
     */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * Set up the doc header properly here
     *
     * @param ViewInterface $view
     */
    protected function initializeView(ViewInterface $view)
    {
        $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
		
        /** @var BackendTemplateView $view */
        parent::initializeView($view);
        $view->getModuleTemplate()->getDocHeaderComponent()->setMetaInformation([]);
		
		$this->createMenu();
		$this->createButtons();
    }

    /**
     * Create menu
     *
     */
    protected function createMenu()
    {
    }

    /**
     * Create the panel of buttons
     *
     */
    protected function createButtons()
    {
        // (+) Кнопка обновить
        
        $buttonBar = $this->view->getModuleTemplate()->getDocHeaderComponent()->getButtonBar();
		$refreshButton = $buttonBar->makeLinkButton()
            ->setHref(GeneralUtility::getIndpEnv('REQUEST_URI'))
            ->setTitle('Обновить')
            ->setIcon($this->iconFactory->getIcon('actions-refresh', Icon::SIZE_SMALL));
        $buttonBar->addButton($refreshButton, ButtonBar::BUTTON_POSITION_RIGHT);
    }


    /**
     * Main action for administration
     */
    public function indexAction()
    {
        $assignedValues = [
            'content' => BaseUtility::debugInController()
        ];
        $this->view->assignMultiple($assignedValues);
    }

}