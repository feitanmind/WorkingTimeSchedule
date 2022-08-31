<?php
    class MailerTest extends \PHPUnit\Framework\TestCase
    {
        public function testSendingMail()
        {
            // $this->markTestSkipped('Sending mails every test');
            $nameOfClient = "John Doe";
            $mailOfClient = "burskiadamwork@gmail.com";
            $mailer = new App\Mailer;
            $this-> assertEquals(true,$mailer->sendMail($nameOfClient,$mailOfClient, "testSubject","testBody"));
        }

    }



?>