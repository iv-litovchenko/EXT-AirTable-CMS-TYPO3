<?php
namespace ###NAMESPACE_1###\###NAMESPACE_2###\Controller\Pages;

class ###KEY###Controller extends \FluidTYPO3\Flux\Controller\PageController
{
    /**
     * The magic variable TYPO3
     * Parameters are described here
     * @var array
     */
    public static $TYPO3 = [
        'thisIs' => 'FrontendPage',
        'name' => '###NAME###',
        'description' => '###DESCRIPTION###',
        'fieldsExcludeList' => 'subtitle,nav_title',
        'fieldsAddList' => 'subtitle,nav_title',
        'disableAllHeaderCode' => 0,
        'nonCachedActions' => '', // USER_INT
        'ajaxActions' => '', // http://your-site.com/?eIdAjax=1&eIdAjaxPath=Ext.###NAMESPACE_2###.Pages.###KEY###.index - See "Ajax-Frontend"
        'urlManagerActions' => [
            // [RU] На 1 действие может быть несколько вариантов
            // [ENG] There can be several options for 1 action
            '/'						=> 'index',
            '/set/{value}'			=> 'index',
            '/show/{record_id}'		=> 'detail'
        ],
    ];

    public function indexAction(string $value = null)
    {
        // typo3conf/ext/###EXTKEY###/Configuration/TypoScript/IncFrontend/constants.ts
        // typo3conf/ext/###EXTKEY###/Configuration/TypoScript/IncFrontend/setup.ts
        // plugin.tx_###EXTKEY2###.settings.myOneSetting = 100
        // plugin.tx_###EXTKEY2###_signaturecontroller.settings.myTwoSetting = 100

        // print_r($this->settings);
        // print_r($this->data); // $this->configurationManager->getContentObject()->data['uid']
        // $link = $this->uriBuilder->uriFor('action'); // EXT.###NAMESPACE_2###.Pages.###KEY###.action

        $this->view->assign('tmplShowBreadcrumb', true);
        $this->view->assign('var', rand(1, 1000));
        $this->view->assign('value', $value);
    }

    public function detailAction(int $record_id = null)
    {
        if ($record_id == 100) {
            $GLOBALS['TSFE']->set_cache();
            $GLOBALS['TSFE']->pageRenderer->setTitle('Record id: ' . $record_id);
            // $this->view->assign('rows', []); // recSelect
        }
        $this->view->assign('record_id', $record_id);
        $this->view->assign('tmplShowBreadcrumb', true);
        $this->view->assign('var', rand(1, 1000));
    }
}