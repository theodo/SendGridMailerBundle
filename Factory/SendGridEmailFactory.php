<?php

namespace Theodo\SendGridMailerBundle\Factory;

use SendGrid\Email;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Reynald Mandel <reynaldm@theodo.fr>
 */
class SendGridEmailFactory
{
    /**
     * @var OptionsResolver
     */
    protected $resolver;

    /**
     * Constructor initialises the options resolver
     */
    public function __construct()
    {
        $this->resolver = new OptionsResolver();
        $this->setDefaultOptions($this->resolver);
    }

    /**
     * Generate a SendGridEmail object from an array of options
     * @see setDefaultOptions method to know the constraints applied to $options
     *
     * @param array $options
     * @return Email
     */
    public function createFromParameters(array $options)
    {
        $options = $this->resolver->resolve($options);

        $email = new Email();
        $email->setFrom($options['from']);
        $email->setFromName($options['from_name']);
        $email->setSubject($options['subject']);
        $email->setHtml($options['html']);

        $emailTos = (is_array($options['to'])) ? $options['to'] : array($options['to']);
        $email->setTos($emailTos);

        return $email;
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('from', 'to', 'subject', 'html'));
        $resolver->setOptional(array('from_name', 'reply_to', 'cc_list', 'bcc_list', 'headers', 'attachments'));
    }
}
