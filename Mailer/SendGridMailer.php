<?php

namespace Theodo\SendGridMailerBundle\Mailer;

use SendGrid as SendGridService;
use SendGrid\Email;

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
     * @param string $sendGridUserLogin     the send grid user's account login
     * @param string $sendGridUserPassword  the send grid user's account password
     */
    public function __construct($sendGridUserLogin, $sendGridUserPassword)
    {
        $this->sendGridService = new SendGridService($sendGridUserLogin, $sendGridUserPassword, array("turn_off_ssl_verification" => true));
    }

    /**
     * Send an already fully prepared email
     * Return true if the mail has been effectively sent, false otherwise
     *
     * @param Email $email
     * @return bool
     */
    public function sendEmail(Email $email)
    {
        $response = $this->sendGridService->send($email);

        return $response->message == 'success';
    }
}
