<?php

namespace Theodo\SendGridMailerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 *
 * @author Reynald Mandel <reynaldm@theodo.fr>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('theodo_send_grid_mailer');

        $rootNode
            ->children()
            ->arrayNode('sendgrid')
            ->children()
            ->scalarNode('user_login')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('user_password')->isRequired()->cannotBeEmpty()->end()
            ->end()
            ->isRequired()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
