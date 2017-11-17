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
     * @param $client
     *
     * @return \Framework\Base\Uploader\FileUploaderInterface
     */
    public function setClient($client): FileUploaderInterface
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }
}
