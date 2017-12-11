<?php

namespace Framework\Base\Service;

use Framework\Base\Uploader\FileUploaderInterface;

/**
 * Class FileUploadService
 * @package Application\Services
 */
class FileUploadService extends Service
{
    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return self::class;
    }

    /**
     * @param string $filePath
     * @param string $fileName
     *
     * @return string
     */
    public function uploadFile(string $filePath, string $fileName)
    {
        $config = $this->getConfig();

        $fileUploaderClientPath = $config['fileUploaderClient']['classPath'];

        $constructorArguments = $config['fileUploaderClient']['constructorArguments'];

        $fileUploaderClassName = $config['fileUploaderInterface'];

        /**
         * @var FileUploaderInterface $fileUploader
         */
        $fileUploader = new $fileUploaderClassName(new $fileUploaderClientPath($constructorArguments));

        return $fileUploader->upload($filePath, $fileName);
    }
}
