SendGridMailerBundle
====================

An opensource bundle that integrates SendGrid API to send emails via their mailing service

How to install it with composer ?
=================================

The bundle is not yet referenced in Packagist, so you will have to include these lines in your composer.json :

    "repositories": [
        {
            "url": "git@github.com:theodo/SendGridMailerBundle.git",
            "type": "vcs"
        }
    ],
    
Then under its "require" section :

        "theodo/send-grid-mailer-bundle": "dev-master"
        
If your minimum-stability is "stable", instead of lowering it, you can directly add this "dev" sub-dependency in your project :

        "mashape/unirest-php": "dev-master@dev"


Then run :

    composer update theodo/send-grid-mailer-bundle

How to use it ?
===============

Sending mail is easy :
 - define "sendgrid.user_login" and "sendgrid.user_password" in your parameters.yml
 - get the "theodo_send_grid_mailer.mailer" service and use the "sendEmail" function
 - enjoy

Note that you also have a SendGridEmailFactory available.
