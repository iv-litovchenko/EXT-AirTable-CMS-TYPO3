<?php
namespace ###NAMESPACE_1###\###NAMESPACE_2###\Controller\PagesElements\Elements;

class ###KEY###Controller extends \FluidTYPO3\Flux\Controller\ContentController
{
    /**
     * The magic variable TYPO3
     * Parameters are described here
     * @var array
     */
    public static $TYPO3 = [
        'thisIs' => 'FrontendContentElement',
        'name' => '###NAME###',
        'description' => '###DESCRIPTION###',
		'fieldsExcludeList' => 'header_position,date',
        'fieldsAddList' => 'imageorient',
        'nonCachedActions' => '', // USER_INT
        'ajaxActions' => '' // http://your-site.com/?eIdAjax=1&eIdAjaxPath=Ext.###NAMESPACE_2###.Elements.###KEY###.index - See "Ajax-Frontend"
        
    ];

    public function indexAction()
    {
        // typo3conf/ext/###EXTKEY###/Configuration/TypoScript/IncFrontend/constants.ts
        // typo3conf/ext/###EXTKEY###/Configuration/TypoScript/IncFrontend/setup.ts
        // plugin.tx_###EXTKEY2###.settings.myOneSetting = 100
        // plugin.tx_###EXTKEY2###_signaturecontroller.settings.myTwoSetting = 100

        // print_r($this->settings);
        // print_r($this->data); // $this->configurationManager->getContentObject()->data['uid']

        $this->view->assign('var', rand(1, 1000));
    }
}