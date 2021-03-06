<?php

use Framework\Base\Model\GenericModel;
use Framework\Base\Repository\GenericRepository;

return [
    'env' => [
        'DATABASE_NAME' => getenv('DATABASE_NAME'),
        'DATABASE_ADDRESS' => getenv('DATABASE_ADDRESS'),
        'SENTRY_DSN' => getenv('SENTRY_DSN'),
        'RABBIT_MQ_HOST' => getenv('RABBIT_MQ_HOST'),
        'RABBIT_MQ_PORT' => getenv('RABBIT_MQ_PORT'),
        'RABBIT_MQ_USER' => getenv('RABBIT_MQ_USER'),
        'RABBIT_MQ_PASSWORD' => getenv('RABBIT_MQ_PASSWORD'),
        'FILE_LOGGER_FILE_NAME' => getenv('FILE_LOGGER_FILE_NAME'),
        'FILE_LOGGER_FILE_DIR_PATH' => getenv('FILE_LOGGER_FILE_DIR_PATH'),
    ],
    'repositories' => [
        GenericModel::class => GenericRepository::class,
    ],
];
