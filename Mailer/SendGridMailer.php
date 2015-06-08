<?php

namespace Theodo\SendGridMailerBundle\Mailer;

use SendGrid as SendGridService;
use SendGrid\Email;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Theodo\SendGridMailerBundle\Factory\SendGridEmailFactory;

/**
 * Send emails via the sendGridMailer API
 * Different methods can be used depending on the use case
 *
 * @author Reynald Mandel <reynaldm@theodo.fr>
 */
class SendGridMailer
{
    const SUCCESS_KEY = 'message';

    const SUCCESS_MESSAGE = 'success';

    /**
     * @var array
     */
    protected $mails;

    /**
     * @var SendGridService
     */
    protected $sendGridService;

    /**
     * @var SendGridEmailFactory
     */
    protected $sendGridEmailFactory;

    /**
     * @param string               $sendGridUserLogin    the send grid user's account login
     * @param string               $sendGridUserPassword the send grid user's account password
     * @param SendGridEmailFactory $sendGridEmailFactory the factory to generate send grid specific emails
     * @param Filesystem           $filesystem           the filesystem service
     */
    public function __construct(
        $sendGridUserLogin,
        $sendGridUserPassword,
        SendGridEmailFactory $sendGridEmailFactory,
        Filesystem $filesystem
    )
    {
        $this->sendGridService = new SendGridService($sendGridUserLogin, $sendGridUserPassword, array("turn_off_ssl_verification" => true));
        $this->sendGridEmailFactory = $sendGridEmailFactory;
        $this->emails = array();
        $this->filesystem = $filesystem;
    }

    /**
     * Send an already fully prepared email
     * Return true if the mail has been effectively sent, false otherwise
     *
     * @param Email $email
     *
     * @return bool
     */
    public function sendSendGridEmail(Email $email)
    {
        $response = $this->sendGridService->send($email);

        $response = $response->getBody();
        return $response[self::SUCCESS_KEY] === self::SUCCESS_MESSAGE;
    }

    /**
     * @param string $from
     * @param string $from_name
     * @param string $to
     * @param string $subject
     * @param string $html
     * @param array  $attachments
     *
     * @return bool
     */
    public function addEmail($from, $from_name, $to, $subject, $html, $attachments)
    {
        $options = compact('from', 'from_name', 'to', 'subject', 'html');
        $sendGridEmail = $this->sendGridEmailFactory->createFromParameters($options);
        $sendGridEmail->setAttachments($attachments);

        return $this->sendSendGridEmail($sendGridEmail);
    }

    /**
     * @param string  $from
     * @param string  $from_name
     * @param string  $to
     * @param string  $subject
     * @param string  $html
     * @param array   $attachments
     * @param boolean $removeAttachments
     */
    public function addEmailToSend($from, $from_name, $to, $subject, $html, $attachments, $removeAttachments = false)
    {
        $options = compact('from', 'from_name', 'to', 'subject', 'html');
        $sendGridEmail = $this->sendGridEmailFactory->createFromParameters($options);
        $sendGridEmail->setAttachments($attachments);

        $this->emails[] = array($sendGridEmail, $removeAttachments);
    }

    /**
     * send emails
     */
    public function sendEmails()
    {
        foreach ($this->emails as $datas) {
            /** @var Email $email */
            $email = $datas[0];
            $this->sendGridService->send($email);

            if ($datas[1]) {
                $this->removeAttachments($email->getAttachments());
            }
        }
    }

    /**
     * @param array $attachments
     */
    public function removeAttachments(array $attachments)
    {
        foreach ($attachments as $attachment) {
            if ($this->filesystem->exists($attachment)) {
                try {
                    $this->filesystem->remove($attachment);
                } catch (IOException $e) {
                }
            }
        }
    }
}
