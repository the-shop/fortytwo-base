<?php

use Framework\Base\Test\Dummies\TestDatabaseAdapter;
use Framework\Base\Test\Dummies\TestModel;
use Framework\Base\Test\Dummies\TestRepository;

return [
    'php' => [
        'test' => [
            'value1',
            'value2'
        ]
    ],
    'repositories' => [
        TestModel::class => TestRepository::class,
    ],
    'modelAdapters' => [
        'tests' => [
            TestDatabaseAdapter::class,
        ],
    ],
    'primaryModelAdapter' => [
        'tests' => TestDatabaseAdapter::class
    ],
    'env' => [
        'DATABASE_ADDRESS' => getenv('DATABASE_ADDRESS'),
        'DATABASE_NAME' => getenv('DATABASE_NAME'),
    ],
];
