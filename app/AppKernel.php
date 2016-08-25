<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
        	new Symfony\Bundle\AsseticBundle\AsseticBundle(),
        	new FOS\UserBundle\FOSUserBundle(),
        	new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        	new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
        	new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
        	new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
        	new Bazinga\Bundle\JsTranslationBundle\BazingaJsTranslationBundle(),
        	new Misd\PhoneNumberBundle\MisdPhoneNumberBundle(),
        	new ADesigns\CalendarBundle\ADesignsCalendarBundle(),
            new Fungio\GoogleMapBundle\FungioGoogleMapBundle(),
        	new AppBundle\AppBundle(),
            new UserBundle\UserBundle(),
            new CalendarBundle\CalendarBundle(),
            new TaskBundle\TaskBundle(),
            new MapsBundle\MapsBundle(),
            new JT\ContactUsBundle\JTContactUsBundle(),
            new JT\MailBundle\JTMailBundle(),
            new JT\FileBundle\JTFileBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
