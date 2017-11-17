<?php

namespace Framework\Base\Mailer;

/**
 * Interface MailInterface
 * @package Framework\Base\Mailer
 */
interface MailInterface
{
    /**
     * @return string
     */
    public function getTo(): string;

    /**
     * Set recipient
     *
     * @param $to
     *
     * @return \Framework\Base\Mailer\MailInterface
     */
    public function setTo(string $to): MailInterface;

    /**
     * @return string
     */
    public function getFrom(): string;

    /**
     * Set sender
     *
     * @param string $from
     *
     * @return \Framework\Base\Mailer\MailInterface
     */
    public function setFrom(string $from): MailInterface;

    /**
     * @return string
     */
    public function getSubject(): string;

    /**
     * @param string $subject
     *
     * @return \Framework\Base\Mailer\MailInterface
     */
    public function setSubject(string $subject): MailInterface;

    /**
     * @return string
     */
    public function getTextBody();

    /**
     * @param string $textBody
     *
     * @return \Framework\Base\Mailer\MailInterface
     */
    public function setTextBody(string $textBody): MailInterface;

    /**
     * @return string
     */
    public function getHtmlBody();

    /**
     * @param string $htmlBody
     *
     * @return \Framework\Base\Mailer\MailInterface
     */
    public function setHtmlBody(string $htmlBody): MailInterface;

    /**
     * @return array
     */
    public function getOptions(): array;

    /**
     * Set additional headers
     *
     * @param $options
     *
     * @return \Framework\Base\Mailer\MailInterface
     */
    public function setOptions(array $options): MailInterface;

    /**
     * @param array $attachments
     *
     * @return MailerInterface
     */
    public function addAttachments(array $attachments = []);

    /**
     * @param string $fileName
     * @param $content
     *
     * @return MailerInterface
     */
    public function addAttachment(string $fileName, $content);

    /**
     * @return array
     */
    public function getAttachments(): array;
}
