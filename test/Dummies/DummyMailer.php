<?php

namespace Framework\Base\Test\Dummies;

use Framework\Base\Mailer\Mailer;
use Framework\Base\Mailer\MailInterface;

/**
 * Class DummyMailer
 * @package Framework\Base\Test\Dummies
 */
class DummyMailer extends Mailer
{
    /**
     * @param MailInterface $mail
     *
     * @return string
     */
    public function send(MailInterface $mail)
    {
        $client = $this->getClient();

        return $client->send($mail);
    }
}
