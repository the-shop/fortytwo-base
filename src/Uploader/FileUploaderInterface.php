<?php

namespace Framework\Base\Uploader;

use Framework\Base\Application\ApplicationAwareInterface;

/**
 * Interface FileUploadInterface
 * @package Framework\Base\FileUpload
 */
interface FileUploaderInterface extends ApplicationAwareInterface
{
    /**
     * FileUploaderInterface constructor.
     *
     * @param null $client
     */
    public function __construct($client = null);

    /**
     * @param string $filePath
     * @param string $fileName
     *
     * @return mixed
     */
    public function upload(string $filePath, string $fileName);

    /**
     * @param $client
     *
     * @return \Framework\Base\Uploader\FileUploaderInterface
     */
    public function setClient($client): FileUploaderInterface;

    /**
     * @return mixed
     */
    public function getClient();
}
