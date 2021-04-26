```

        //////////////////////////////////////
        // prop_*
        //////////////////////////////////////
        'dataFields' => [
            // Input
            'prop_nameinput' => [
                'type' => 'Input', // || Input.Int || Input.Number || Input.Float || Input.Link || Input.Color || Input.Email || Input.Password || Input.InvisibleInt || Input.Invisible
                'name' => 'Field Input',
                'max' => 100,
                'size' => 24,
                'liveSearch' => 1,

                //////////////////////////////////////
                // General settings for all types of fields
                //////////////////////////////////////
                'description' => '-------Description-------',
                'placeholder' => '-------Placeholder-------',
                'default' => '-------Default value-------',
                'show' => 1, // Show field in lists
                'required' => 1, // Required to fill
                'readOnly' => 1,
                'validationRules' => [
                    'required' => 1, // Todo Validator
                    'ruleName' => 1, // Todo Validator
                ],
                'onChangeReload' => 1, // Reload the form when the field value changes
                'displayCond' => 'USER:Litovchenko\AirTable\Domain\Model\Content\Data->isVisibleDisplayConditionMatcher:tx_data', // Example
                'exclude' => 1, // Todo

                // Required parameter - if typing of records ("RType") and (or) "tabs" is defined
                // Example 'position' => ['fields']['prop_***']['position'][][type|tab|position'] add to type "1" (RType)
                // Example 'position' => ['fields']['prop_***']['position'][]['*|mytab|100'] "*" adding to all types
                // Example 'position' => ['fields']['prop_***']['position'][]['1|mytab|100'] add to type "1" (RType)
                'position' => '*|props|10', // '<RType>|<Tab>|<Num>'
                // 'position' => [
                    // '*|props|10',
                    // 'news|props|10',
                    // 'article|props|10',
                    // ...
                // ],
            ],
            // Text
            'prop_nametext' => [
                'type' => 'Text', // || Text.Rte || Text.Code || Text.Table || Text.Invisible
                'name' => 'Field Text',
                'show' => 1,
                'liveSearch' => 1,
                'format' => 'css || html || javascript || php || typoscript || xml', // Text.Rte
                'preset' => 'default || full || default || ext_myext_preset', // Text.Code
            ],
            // Date
            'prop_namedate' => [
                'type' => 'Date', // || Date.DateTime || Date.Time || Date.TimeSec || Date.Year
                'name' => 'Field Date',
                'show' => 1,
            ],
            // Flag
            'prop_nameflag' => [
                'type' => 'Flag',
                'name' => 'Field Flag',
                'show' => 1,
                'items' => [
                    1 => 'Checked',
                ],
            ],
            // Switcher
            'prop_nameswitcher' => [
                'type' => 'Switcher', // || Switcher.Int
                'name' => 'Field Switcher',
                'show' => 1,
                'itemsProcFunc' => 'Mynamespace\Myext\Domain\Model\[SubFolder]\NewTable->doItems',
                'itemsModel' => 'Mynamespace\Myext\Domain\Model\###',
                'itemsWhere' => [
                    'where' => [
                        0 => 'table.field > 5'
                    ],
                    'orderBy' => [
                        0 => 'table.field desc'
                    ]
                ],
                'items' => [
                    0 => 'Zero',
                    1 => 'One',
                    2 => 'Two',
                    3 => 'Three',
                ],
            ],
            // Enum
            'prop_nameenum' => [
                'type' => 'Enum',
                'name' => 'Field Enum',
                'show' => 1,
                'itemsProcFunc' => 'Mynamespace\Myext\Domain\Model\[SubFolder]\NewTable->doItems',
                'itemsModel' => 'Mynamespace\Myext\Domain\Model\###',
                'itemsWhere' => [
                    'where' => [
                        0 => 'table.field > 5'
                    ],
                    'orderBy' => [
                        0 => 'table.field desc'
                    ]
                ],
                'items' => [
                    1 => 'One',
                    2 => 'Two',
                    3 => 'Three',
                ],
            ],
            // ...
        ],
    ];
    
    /// --- ПЕРЕНЕСТИ НА ОТДЕЛЬНЫЙ ФАЙЛ ---

    /**
     * Custom value set (user func)
     * It is possible to use a selection from the database
     * return $config
     */
    public static function doItems($config)
    {
        $itemList = []; // If database
        $config['items'][] = [100, 'New item 100'];
        $config['items'][] = [200, 'New item 200'];
        $config['items'][] = [300, 'New item 300'];
        return $config;
    }
}


----



