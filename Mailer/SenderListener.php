<?php

namespace Theodo\SendGridMailerBundle\Mailer;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class SenderListener
 */
class SenderListener implements EventSubscriberInterface
{
    /**
     * @var SendGridMailer
     */
    private $mailer;

    /**
     * @param SendGridMailer $mailer
     */
    public function __construct(SendGridMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'kernel.terminate' => array(
                array('onKernelTerminate', 10),
            ),
        );
    }

    /**
     * onKernelTerminate
     */
    public function onKernelTerminate()
    {
        $this->mailer->sendEmails();
    }
}
