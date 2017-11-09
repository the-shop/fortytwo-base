<?php

namespace Framework\Base\Test\FileUpload;

use Application\Test\Application\Traits\Helpers;

/**
 * Class DummyS3Response
 * @package Framework\Base\Test\FileUpload
 * @todo lose APPLICATION DEPENDENCY
 */
class DummyS3Response
{
    use Helpers;

    public function get(string $key)
    {
        return $key . '.testing.url';
    }
}
