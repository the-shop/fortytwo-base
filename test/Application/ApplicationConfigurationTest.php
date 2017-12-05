<?php

namespace Framework\Base\Test\Application;

use Framework\Base\Application\ApplicationConfiguration;
use Framework\Base\Test\Dummies\TestDatabaseAdapter;
use Framework\Base\Test\Dummies\TestModel;
use Framework\Base\Test\Dummies\TestRepository;
use Framework\Base\Test\UnitTest;

/**
 * Class ConfigurationTest
 * @package Framework\Base\Test\Application
 */
class ApplicationConfigurationTest extends UnitTest
{
    /**
     *
     */
    public function testSetGet()
    {
        $config = new ApplicationConfiguration();
        $config->setPathValue('test', 'value');

        $this->assertEquals(['test' => 'value'], $config->getAll());

        $config->setPathValue('test.nested.value', ['array', 'value']);

        // Overwrite
        $this->assertEquals(
            ['test' => ['nested' => ['value' => ['array', 'value']]]],
            $config->getAll()
        );

        // Write in parallel
        $config->setPathValue('parallel.nested.value', ['array2', 'value2']);

        $this->assertEquals(
            [
                'test' =>
                    [
                        'nested' => [
                            'value' => [
                                'array',
                                'value',
                            ],
                        ],
                    ],
                'parallel' =>
                    [
                        'nested' => [
                            'value' => [
                                'array2',
                                'value2',
                            ],
                        ],
                    ],
            ],
            $config->getAll()
        );

        // Test get at path
        $this->assertEquals('value2', $config->getPathValue('parallel.nested.value.1'));
    }

    /**
     * Test configuration methods readFromPhp and readFromJson
     */
    public function testReadFromPhpAndReadFromJson()
    {
        $config = new ApplicationConfiguration();

        $config->readFromPhp(dirname(__DIR__) . '/Dummies/dummyConfig.php');

        $this->assertEquals(
            [
                'test' => [
                    'value1',
                    'value2',
                ],
            ],
            $config->getPathValue('php')
        );

        $config->readFromJson(dirname(__DIR__) . '/Dummies/dummyConfigJson.json');

        $this->assertEquals('value1', $config->getPathValue('json.test.0'));
        $this->assertEquals(
            [
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
                "json" => [
                    "test" => [
                        "value1",
                        "value2"
                    ]
                ],
                "models" => [
                    "Test" => [
                        "collection" => "tests",
                        "authenticatable" => false,
                        "fields" => [
                            "_id" => [
                                "primaryKey" => true,
                                "label" => "ID",
                                "type" => "string",
                                "disabled" => true,
                                "required" => false,
                                "default" => ""
                            ],
                            "name" => [
                                "label" => "Name",
                                "type" => "string",
                                "required" => true,
                                "validation" => [
                                    "string"
                                ],
                                "default" => ""
                            ],
                            "email" => [
                                "label" => "Email",
                                "type" => "string",
                                "required" => true,
                                "validation" => [
                                    "string",
                                    "email",
                                    "unique"
                                ],
                                "default" => ""
                            ],
                            "password" => [
                                "label" => "Password",
                                "type" => "password",
                                "required" => true,
                                "validation" => [],
                                "default" => null
                            ]
                        ]
                    ]
                ]
            ],
            $config->getAll()
        );
    }

    /**
     * Test configuration readFromPhp method - file not found - exception thrown
     */
    public function testReadFromPhpExceptionThrown()
    {
        $config = new ApplicationConfiguration();

        $file = 'testing.php';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('Unable to open php file' . $file . ' file not found!');

        $config->readFromPhp($file);
    }

    /**
     * Test configuration readFromJson method - file not found - exception thrown
     */
    public function testReadFromJsonExceptionThrown()
    {
        $config = new ApplicationConfiguration();

        $file = 'testing.json';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('Unable to open json file' . $file . ' file not found!');

        $config->readFromJson($file);
    }
}
