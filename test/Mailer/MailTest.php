<?php

namespace Framework\Base\Test\Mailer;

use Framework\Base\Mailer\Mail;
use Framework\Base\Test\UnitTest;

/**
 * Class MailTest
 * @package Framework\Base\Test\Mailer
 */
class MailTest extends UnitTest
{
    /**
     * Test mail construct, setters and getters methods - success
     */
    public function testMailConstructorAndSetterAndGetters()
    {
        $mail = new Mail();
        $mail->setHtmlBody('<h1>Test</h1>')
             ->setTextBody('Test')
             ->setFrom('test@test.com')
             ->setTo('test@testing.com')
             ->setSubject('test')
             ->setOptions(
                 [
                     'cc' => 'test cc'
                 ]
             );

        $this::assertEquals('<h1>Test</h1>', $mail->getHtmlBody());
        $this::assertEquals('Test', $mail->getTextBody());
        $this::assertEquals('test@test.com', $mail->getFrom());
        $this::assertEquals('test@testing.com', $mail->getTo());
        $this::assertEquals('test', $mail->getSubject());
        $this::assertEquals(['cc' => 'test cc'], $mail->getOptions());
    }

    /**
     * Test mail set options - not allowed option field - exception
     */
    public function testMailSetOptionsNotAllowedField()
    {
        $mail = new Mail();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Option field test is not allowed.");
        $this->expectExceptionCode(403);

        $mail->setOptions(['test' => 'test']);
    }
}
