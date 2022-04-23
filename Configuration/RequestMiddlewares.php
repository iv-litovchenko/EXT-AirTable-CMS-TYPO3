<?php
return [
    'frontend' => [
        'typo3/airtable/aspect' => [
            'target' => \Litovchenko\AirTable\Middleware\FrontendEditingAspect::class,
            'before' => [
                'typo3/cms-frontend/prepare-tsfe-rendering',
            ],
        ],
    ],
];
