<?php

namespace Framework\Base\Service;

use Framework\Base\Mailer\Mail;
use Framework\Base\Queue\Adapters\Sync;
use Framework\Base\Queue\TaskQueue;

/**
 * Class EmailService
 * @package Framework\Base\Service
 */
class EmailService extends Service
{
    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return self::class;
    }

    /**
     * @param string $from
     * @param string $subject
     * @param string $to
     * @param        $htmlBody
     * @param array  $attachments
     * @param null   $textBody
     *
     * @return mixed
     */
    public function sendEmail(
        string $from,
        string $subject,
        string $to,
        $htmlBody,
        $attachments = [],
        $textBody = null
    )
    {
        $config = $this->getConfig();

        $mailerClassPath = $config['mailerInterface'];
        $mailerClientClassPath = $config['mailerClient']['classPath'];
        $constructorArguments = array_values($config['mailerClient']['constructorArguments']);

        $mailerClient = new $mailerClientClassPath(...$constructorArguments);
        $mailer = new $mailerClassPath($mailerClient);
        $mail = new Mail();

        $mail->setFrom($from)
             ->setSubject($subject)
             ->setTo($to)
             ->setHtmlBody($htmlBody)
             ->addAttachments($attachments);

        if ($textBody !== null) {
            $mail->setTextBody($textBody);
        }

        $response = TaskQueue::addTaskToQueue(
            'email',
            Sync::class,
            [
                'taskClassPath' => $mailer,
                'method' => 'send',
                'parameters' => [
                    $mail,
                ],
            ]
        );

        return $response;
    }
}
