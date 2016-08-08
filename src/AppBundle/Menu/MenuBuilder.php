<?php
namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class MenuBuilder implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	public function welcomeMenu(FactoryInterface $factory, array $options)
	{
	    $menu = $factory->createItem('root');
	    $menu->setChildrenAttribute('class', 'nav navbar-nav');

	    $uri = $this->container->get('router')->generate('welcome');

	    $menu->addChild('blog', array('uri' => 'http://blog.thesimplecrm.net', 'label' => 'Blog'));
	    $menu->addChild('legal', array('uri' => $uri.'#legal', 'label' => 'Legal'));
	    $menu->addChild('features', array('uri' => $uri.'#features', 'label' => 'Features'));
	    $menu->addChild('pricing', array('uri' => $uri.'#pricing', 'label' => 'Pricing'));
	    $menu->addChild('contact', array('uri' => $uri.'#contact', 'label' => 'Contact'));

	    return $menu;
	}

	public function mainMenu(FactoryInterface $factory, array $options)
	{
		$menu = $factory->createItem('root');
		$menu->setChildrenAttribute('class', 'nav navbar-nav');

		$menu->addChild('homepage', array(
		    'route' => 'homepage',
		    'label' => 'menu.homepage'
		))->setAttribute('icon', 'fa fa-home');

		$menu->addChild('tasks', array(
		    'route' => 'task_index',
		    'label' => 'menu.tasks'
		))->setAttribute('icon', 'fa fa-tasks');

		$menu->addChild('companies', array(
		    'route' => 'company_new',
		    'label' => 'menu.companies'
		))->setAttribute('icon', 'fa fa-building');

		$menu->addChild('team', array(
		    'route' => 'team_index',
		    'label' => 'menu.team'
		))->setAttribute('icon', 'fa fa-users');

		$menu->addChild('calendar', array(
		    'route' => 'calendar',
		    'label' => 'menu.calendar'
		))->setAttribute('icon', 'fa fa-calendar');

		return $menu;
	}

	public function userMenu(FactoryInterface $factory, array $options)
	{
		$menu = $factory->createItem('root');
		$menu->setChildrenAttribute('class', 'nav navbar-nav');

		if ($this->container->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
		    $user = $this->container->get('security.token_storage')->getToken()->getUser();
		    $userMenu = $menu->addChild('user', array('label' => $user))
		         ->setAttribute('dropdown', true)
			     ->setAttribute('icon', 'fa fa-user');

		    $userMenu->addChild('logout', array(
		        'route' => 'fos_user_security_logout',
		        'label' => 'layout.logout',
		    ))->setExtra('translation_domain', 'FOSUserBundle');
		}
		else {
		    $menu->addChild('login', array(
		        'route' => 'hwi_oauth_connect',
		        'label' => 'layout.login',
		    ))->setExtra('translation_domain', 'FOSUserBundle')->setAttribute('id', 'login_link');
		}

		return $menu;
	}
}