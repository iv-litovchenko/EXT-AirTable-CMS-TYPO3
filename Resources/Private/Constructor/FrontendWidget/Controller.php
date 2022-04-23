<?php
namespace ###NAMESPACE_1###\###NAMESPACE_2###\Controller\Widgets;

class ###KEY###Controller extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * The magic variable TYPO3
     * Parameters are described here
     * @var array
     */
    public static $TYPO3 = [
        'thisIs' => 'FrontendWidget',
        'name' => '###NAME###',
        'description' => '###DESCRIPTION###',
        'nonCachedActions' => 'index', // USER_INT
        'ajaxActions' => 'index', // http://your-site.com/?eIdAjax=1&eIdAjaxPath=Ext.###NAMESPACE_2###.Widgets.###KEY###.index - See "Ajax-Frontend"
        'registerArguments' => [
            'testArg1*' => ['string','Default value','Description'], // integer || string || mixed || boolean || array
            'testArg2*' => ['string',640],
            'testArg3' => ['string',480]
        ]
    ];

    public function indexAction(string $value = null)
    {
        $this->view->assign('testArg1', $this->settings['testArg1']);
        $this->view->assign('testArg2', $this->settings['testArg2']);
        $this->view->assign('testArg3', $this->settings['testArg3']);
        $this->view->assign('value', $value);
    }
}

// Run -> <f:wgsExt###NAMESPACE_2###.###KEY### action="index" testArg1="100" testArg2="200" testArg3="300" />