<?php

namespace Framework\Base\Uploader;

use Framework\Base\Application\ApplicationAwareTrait;

/**
 * Class FileUploader
 * @package Framework\Base\Uploader
 */
abstract class FileUploader implements FileUploaderInterface
{
    use ApplicationAwareTrait;

    /**
     * @var
     */
    private $client;

    /**
     * FileUploader constructor.
     *
     * @param null $client
     */
    public function __construct($client = null)
    {
        $this->setClient($client);
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param $client
     *
     * @return FileUploaderInterface
     */
    public function setClient($client): FileUploaderInterface
    {
        $this->client = $client;

        return $this;
    }
}
