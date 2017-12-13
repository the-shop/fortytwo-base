<?php

namespace Framework\Base\Mailer;

/**
 * Class Mail
 * @package Framework\Base\Mailer
 */
class Mail implements MailInterface
{
    /**
     * @var string
     */
    private $to;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $textBody;

    /**
     * @var string
     */
    private $htmlBody;

    /**
     * @var array
     */
    private $options = [
        'cc' => null,
        'bcc' => null,
    ];

    /**
     * @var array
     */
    private $attachments = [];

    /**
     * Mail constructor.
     *
     * @param string $to
     * @param string $from
     * @param string $subject
     * @param string $htmlBody
     * @param string $textBody
     * @param array  $options
     */
    public function __construct(
        string $to = '',
        string $from = '',
        string $subject = '',
        string $htmlBody = '',
        string $textBody = '',
        array $options = []
    )
    {
        $this->setTo($to)
             ->setFrom($from)
             ->setSubject($subject)
             ->setHtmlBody($htmlBody)
             ->setTextBody($textBody)
             ->setOptions($options);

    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     *
     * @return MailInterface
     */
    public function setTo(string $to): MailInterface
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     *
     * @return MailInterface
     */
    public function setFrom(string $from): MailInterface
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return MailInterface
     */
    public function setSubject(string $subject): MailInterface
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTextBody()
    {
        return $this->textBody;
    }

    /**
     * @param string $textBody
     *
     * @return MailInterface
     */
    public function setTextBody(string $textBody): MailInterface
    {
        $this->textBody = $textBody;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getHtmlBody()
    {
        return $this->htmlBody;
    }

    /**
     * @param string $htmlBody
     *
     * @return MailInterface
     */
    public function setHtmlBody(string $htmlBody): MailInterface
    {
        $this->htmlBody = $htmlBody;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * CC and Bcc setter
     *
     * @param array $options
     *
     * @return MailInterface
     * @throws \Exception
     */
    public function setOptions(array $options): MailInterface
    {
        if (empty($options) === false) {
            foreach ($options as $key => $value) {
                if (array_key_exists($key, $this->getOptions()) === false) {
                    throw new \Exception("Option field $key is not allowed.", 403);
                }
            }
            $this->options = $options;
        }

        return $this;
    }

    /**
     * @param array $attachments
     *
     * @return MailInterface
     */
    public function addAttachments(array $attachments = []): MailInterface
    {
        foreach ($attachments as $fileNameAndExtension => $content) {
            $this->addAttachment($fileNameAndExtension, $content);
        }

        return $this;
    }

    /**
     * @param string $fileName
     * @param        $content
     *
     * @return MailInterface
     */
    public function addAttachment(string $fileName, $content): MailInterface
    {
        $this->attachments[$fileName] = $content;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }
}
