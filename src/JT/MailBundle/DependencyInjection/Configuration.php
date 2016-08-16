<?php

namespace JT\MailBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('jt_mail');

        $rootNode
            ->children()
                ->arrayNode('pre_mailer')
					->children()
						->scalarNode('charset')->defaultValue('UTF-8')->end()
						->booleanNode('remove_comments')->defaultTrue()->end()
						->enumNode('style_tag')
							->values(array('body', 'head', 'remove'))
							->defaultValue('body')
						->end()
						->booleanNode('remove_classes')->defaultFalse()->end()
						->integerNode('text_line_width')->defaultValue(60)->end()
						->scalarNode('css_writer_class')->defaultValue('\Crossjoin\Css\Writer\Compact')->end()
						->booleanNode('generate_text')->defaultTrue()->end()
					->end()
				->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

