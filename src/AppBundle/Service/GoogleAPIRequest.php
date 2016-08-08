<?php
namespace AppBundle\Service;

use Buzz\Browser;
use Buzz\Message\Response;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use AppBundle\Exception\GoogleTokenInvalidException;

abstract class GoogleAPIRequest
{
	protected $user;
	protected $url;
	protected $userProvider;
	protected $query_data;
	protected $response;
	
	public function __construct(UserProviderInterface $provider, UserInterface $user)
	{
		$this->userProvider = $provider;
		$this->user = $user;
	}
	
	public function execute()
	{
		$url = $this->url;
		if ($this->query_data != null){
			$url .= '?' . http_build_query($this->query_data);
		}
		
		$browser = new Browser();
		$this->response = $browser->get($url, array('GData-Version: 3.0'));
		
		if ($this->response->getHeaders()[0] != "HTTP/1.1 200 OK"){
			throw new GoogleTokenInvalidException();
		}
		
		return $this;
	}
	
	abstract public function prepare($value);
	abstract public function getResult();
}