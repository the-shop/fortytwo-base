<?php

namespace Framework\Base\Test\Dummies;

/**
 * Class DummyMailerClient
 * @package Framework\Base\Test\Dummies
 */
class DummyMailerClient
{
    /**
     * @param $to
     * @param $from
     * @param $subject
     * @param $textBody
     * @param $htmlBody
     *
     * @return string
     */
    public function send($to, $from, $subject, $textBody, $htmlBody)
    {
        if (empty($to) === true || empty($from) === true || empty($subject) === true) {
            throw new \RuntimeException('Recipient field "to", "from" and "subject" field must be provided.', 403);
        }

        if (empty($htmlBody) === true && empty($textBody) === true) {
            throw new \RuntimeException('Text-plain or html body is required.', 403);
        }

        return $htmlBody;
    }
}
