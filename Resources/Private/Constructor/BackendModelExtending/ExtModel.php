<?php
namespace ###NAMESPACE_1###\###NAMESPACE_2###\Domain\Model\Ext;

final class Ext###KEY### extends ###BASEMODEL###
{
    /**
     * The magic variable TYPO3
     * Parameters are described here
     * @var array
     */
    public static $TYPO3 = [
        'thisIs' => 'BackendModelExtending',
        'description' => '###DESCRIPTION###',
        // 'formSettings' => [
        //     'tabs' => [
        //         'newtab' => 'EXT:###EXTKEY### New Tab (###COUNT###)'
        //     ],
        // ],
        // 'baseFields' => [
        //     'RType' => [
        //         'items' => [
        //             '100' => 'New Type',
        //         ]
        //     ],
        // ],
        'dataFields' => [
            'prop_tx_###EXTKEY2###_incrandphoto' => [
                'type' => 'Flag',
                'name' => 'New field',
                // Required parameter - if typing of records ("RType") and (or) "tabs" is defined
                // 'position' => '*|props|10', // '<RType>|<Tab>|<Num>'
                // 'position' => [
                    // '*|props|10',
                    // 'news|props|10',
                    // 'article|props|10',
                    // ...
                // ],
            ]
        ],
        'mediaFields' => [], // ...
        'relationalFields' => [] // ...
    ];

    /**
     * Changing the $TCA settings array
     * @configuration (TCA array)
     * @return &configuration
     */
    public static function postBuildConfiguration(&$configuration = [])
    {
        //$configuration['ctrl']['...'] = ...;
        //$configuration['columns']['field']['config'] = ...;
    }
}