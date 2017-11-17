<?php

namespace Framework\Base\Mailer;

/**
 * Interface MailerInterface
 * @package Framework\Base\Mailer
 */
interface MailerInterface
{
    /**
     * MailerInterface constructor.
     *
     * @param null $client
     */
    public function __construct($client = null);

    /**
     * Send email
     *
     * @param MailInterface $mail
     *
     * @return mixed
     */
    public function send(MailInterface $mail);

    /**
     * @return mixed
     */
    public function getClient();

    /**
     * @param $client
     *
     * @return \Framework\Base\Mailer\MailerInterface
     */
    public function setClient($client): MailerInterface;
}
