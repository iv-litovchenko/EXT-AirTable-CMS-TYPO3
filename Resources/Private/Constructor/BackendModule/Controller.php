<?php
namespace Mynamespace\Myext\Controller\Modules;

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
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

class NewModule1Controller extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * The magic variable TYPO3
     * Parameters are described here
     * @var array
     */
    public static $TYPO3 = [
        'thisIs' => 'BackendModule',
        'name' => 'Module 1',
        'description' => 'Module 1 description',
        'access' => 'user,group || admin || systemMaintainer',
        'accessCustomPermOptions' => [
            // Todo -> $GLOBALS['TYPO3_CONF_VARS']['BE']['customPermOptions']
            'key1' => 'Name One',
            'key2' => 'Name Two',
            'key3' => 'Name Three',
        ],
        'ajaxActions' => 'index', // Todo
        'section' => 'web || file || user || help || content || tools || ext || unseen || sec_ext_myext',
        'position' => '100'
    ];

    /**
     * Backend Template Container
     *
     * @var BackendTemplateView
     */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * Main action for administration
     */
    public function indexAction()
    {
        // $id = (int) (GeneralUtility::_GET('id') ?? 0);
        $this->view->assign('var', rand(1, 1000));
    }

    /**
     * Edit action for administration
     */
    public function editAction()
    {
        // $id = (int) (GeneralUtility::_GET('id') ?? 0);
        $this->view->assign('var', rand(1, 1000));
    }
}