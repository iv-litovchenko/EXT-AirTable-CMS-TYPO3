<?php
namespace ###NAMESPACE_1###\###NAMESPACE_2###\Domain\Model;

class ###KEY### extends \Litovchenko\AirTable\Domain\Model\ModelCrud
{
    /**
     * The magic variable TYPO3
     * Parameters are described here
     * @var array
     */
    public static $TYPO3 = [
        'thisIs' => 'BackendModelCrud',
        'name' => '###NAME###',
        'description' => '###DESCRIPTION###',
        'defaultListTypeRender' => 0,
        // 'formSettings' => [
        //     'tabs' => [
        //         'newtab' => 'EXT:###EXTKEY### New Tab (###COUNT###)'
        //     ],
        // ],
        //////////////////////////////////////
        // Special table fields
        //////////////////////////////////////
        'baseFields' => [
            'uidkey',
            'pid', // A record can exist in any part of the site page tree
            'RType' => [
                // Record types similar to "doktype (pages)" and "CType (tt_content)"
                'items' => [
                    '0' => 'Default',
                    'news' => 'News',
                    'article' => 'Article',
                    'link' => 'External link',
                    'type-1' => 'Type 1',
                    'type-2' => 'Type 2',
                    'type-3' => 'Type 3',
                    // ...
                ],
            ],
            'title' => [
                'required' => 1,
            ],
            'alt_title' => [
                'required' => 1,
            ],
            'service_note',
            'date_create',
            'date_update',
            'date_start',
            'date_end',
            'sorting',
            'deleted',
            'disabled',
            // 'status', // Or 'deleted' && 'disabled',
            'bodytext_preview',
            'bodytext_detail',
            'keywords',
            'description',
            'slug',

            'propmedia_pic_preview',
            'propmedia_pic_detail',
            'propmedia_files',
            'propmedia_thumbnail', // Image associated with the recording

            'propref_beauthor', // M-1
            'propref_content', // <f:vhsExtAirTable.content model="Mynamespace\Myext\Domain\Model\NewTable" uid="2" />
            'propref_attributes',
            'propref_categories', //  Categorization M-M
            // or 'propref_category', // Categorization M-1
            // 'propref_parent', // M-1

            // 'foreign_table', // For polymorphic relations
            // 'foreign_field', // For polymorphic relations
            // 'foreign_uid', // For polymorphic relations
            // 'foreign_sortby', // For polymorphic relations
        ],
        //////////////////////////////////////
        // prop_*
        //////////////////////////////////////
        'dataFields' => [
            // Input || Input.Int || Input.Number || Input.Float || Input.Link || Input.Color || Input.Email || Input.Password || Input.InvisibleInt || Input.Invisible
            // Text || Text.Rte || Text.Code || Text.Table || Text.Invisible
            // Date || Date.DateTime || Date.Time || Date.TimeSec || Date.Year
            // Flag || Switcher || Enum
            'prop_name' => [
                'type' => 'Input',
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
                'position' => '*|props|10', // '<RType>|<Tab>|<Num>'
                // 'position' => [
                    // '*|props|10',
                    // 'news|props|10',
                    // 'article|props|10',
                    // ...
                // ],
            ],
            // ...
        ],
        //////////////////////////////////////
        // propmedia_*
        //////////////////////////////////////
        'mediaFields' => [
            // Media
            // Media_1 || Media_1.Image || Media_1.Mix', // One
            // Media_M || Media_M.Image || Media_M.Mix // Many
            'propmedia_pics' => [
                'type' => 'Media_1',
                'name' => 'Field Media_1',
                'position' => '*|media|10', // '<RType>|<Tab>|<Num>' ("RType")
                'show' => 1,
                'maxItems' => 10,
            ],
            // ...
        ],
        //////////////////////////////////////
        // propref_*
        //////////////////////////////////////
        'relationalFields' => [
            // Rel_1To1 || Rel_1ToM || Rel_MTo1(.Large) || Rel_MToM(.Large) || Rel_Poly_1To1 || Rel_Poly_1ToM
            // Rel_1To1 || Rel_1ToM || Rel_MTo1(.Large) || Rel_MToM(.Large) || Rel_Poly_1To1 || Rel_Poly_1ToM
            'propref_tags' => [
                'type' => 'Rel_MTo1',
                'name' => 'Field Rel_MTo1',
                'position' => '*|rels|10', // '<RType>|<Tab>|<Num>' ("RType")
                'show' => 1,
                'foreignModel' => 'Mynamespace\Myext\Domain\Model\Tag', // tx_myext_dm_tag
                'foreignKey' => 'propref_newtable',
                'foreignParentKey' => 'parent_id', // Only Rel_MTo1.Tree || Rel_MToM.Tree
                'foreignWhere' => [ // See $TCA "foreign_table_where
                    'where' => [
                        0 => 'table.RType=###REC_FIELD_RType### '
                    ],
                    'orderBy' => [
                        0 => 'table.field desc'
                    ]
                ],
                'foreignDefaults' => [
                    'CType' => 'image', // See $TCA "foreign_record_defaults"
                ],
            ],
            // ...
        ],
    ];

    /**
     * A set of rules for context-aware validation
     * @return array
     */
    public static function validationRules($params = [])
    {
        $rules = [
            'checkPreInsert' => [
                'title' => [
                    'required' => 'MSG "required"',
                    'string' => 'MSG "string"',
                    'max:2' => 'MSG "max"',
                    #function ($attribute, $value, $fail) {
                    #	if ($value === 'foo') {
                    #		$fail('The '.$attribute.' is invalid.');
                    #	}
                    #}
                ],
            ],
            'checkPreUpdate' => [
                // context update...
            ],
            'checkPreDelete' => [
                // context delete...
            ],
            'checkOther' => [
                // ...
            ],
        ];
        return $rules;
    }

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