<?php

namespace Theodo\SendGridMailerBundle\Mailer;

use SendGrid as SendGridService;
use SendGrid\Email;

use Theodo\SendGridMailerBundle\Factory\SendGridEmailFactory;

/**
 * Send emails via the sendGridMailer API
 * Different methods can be used depending on the use case
 *
 * @author Reynald Mandel <reynaldm@theodo.fr>
 */
class SendGridMailer
{
    /**
     * @var SendGridService
     */
    protected $sendGridService;

    /**
     * @var SendGridEmailFactory
     */
    protected $sendGridEmailFactory;

    /**
     * @param string                $sendGridUserLogin     the send grid user's account login
     * @param string                $sendGridUserPassword  the send grid user's account password
     * @param SendGridEmailFactory  $sendGridEmailFactory  the factory to generate send grid specific emails
     */
    public function __construct($sendGridUserLogin, $sendGridUserPassword, SendGridEmailFactory $sendGridEmailFactory)
    {
        $this->sendGridService = new SendGridService($sendGridUserLogin, $sendGridUserPassword, array("turn_off_ssl_verification" => true));
        $this->sendGridEmailFactory = $sendGridEmailFactory;
    }

    /**
     * Send an already fully prepared email
     * Return true if the mail has been effectively sent, false otherwise
     *
     * @param Email $email
     * @return bool
     */
    public function sendSendGridEmail(Email $email)
    {
        $response = $this->sendGridService->send($email);

        return $response->message == 'success';
    }

    /**
     * @param $from
     * @param $from_name
     * @param $to
     * @param $subject
     * @param $html
     * @param $attachments
     * @return bool
     */
    public function sendEmail($from, $from_name, $to, $subject, $html, $attachments)
    {
        $options = compact('from', 'from_name', 'to', 'subject', 'html');
        $sendGridEmail = $this->sendGridEmailFactory->createFromParameters($options);
        $sendGridEmail->setAttachments($attachments);

        return $this->sendSendGridEmail($sendGridEmail);
    }
}
