<?php

namespace Framework\Base\Mailer;

/**
 * Class Mailer
 * @package Framework\Base\Mailer
 */
abstract class Mailer implements MailerInterface
{
    /**
     * @var
     */
    private $client;

    /**
     * Mailer constructor.
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
     * @return MailerInterface
     */
    public function setClient($client): MailerInterface
    {
        $this->client = $client;

        return $this;
    }
}
