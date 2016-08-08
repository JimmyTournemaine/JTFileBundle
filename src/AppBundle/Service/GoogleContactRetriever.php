<?php
namespace AppBundle\Service;

use AppBundle\Service\GoogleAPIRequest;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Model\GoogleContact;
use Symfony\Component\DomCrawler\Crawler;
use Buzz\Message\Response;

class GoogleContactRetriever extends GoogleAPIRequest
{
	private $maxResults;
	private $startIndex;
	
	public function __construct(UserProviderInterface $provider, TokenStorage $token, $maxResults)
	{
		parent::__construct($provider, $token->getToken()->getUser());
		$this->maxResults = $maxResults;
	}
	
	
	public function prepare($page)
	{
		$this->url = "https://www.google.com/m8/feeds/contacts/default/full/";
		$this->startIndex = ($page-1) * $this->maxResults + 1;
		$this->query_data = array(
				'max-results' => $this->maxResults,
				'start-index' => $this->startIndex,
				'access_token' => $this->user->getGoogleAccessToken()
		);
	
		return $this;
	}
	
	public function getResult()
	{
		$xml = $this->response->getContent();
		$crawler = new Crawler($xml);
		$entries = $crawler->filterXPath('//default:feed/default:entry');
		$contacts = new ArrayCollection();
		$matches = array();
		foreach ($entries as $entry)
		{
			$entryCrawler = new Crawler($entry);
			$contact = new GoogleContact();
			
			if (preg_match("#https?://w{3}\.google\.com/m8/feeds/contacts/.+/base/([0-9a-f]+)#", $entryCrawler->filterXPath('default:entry/default:id')->text(), $matches))
				$contact->setGoogleId($matches[1]);
			
			$value = $entryCrawler->filterXPath('default:entry/gd:email');
			if ($value->count())
				$contact->setEmail($value->attr('address'));
			
			$value = $entryCrawler->filterXPath('default:entry/gd:name/gd:givenName');
			if ($value->count()){
				$contact->setGivenName($value->text());
			}
			
			$value = $entryCrawler->filterXPath('default:entry/gd:name/gd:familyName');
			if ($value->count())
				$contact->setFamilyName($value->text());
			
			$contacts->add($contact);
		}
		
		$totalResults = (int) $crawler->filterXPath('//default:feed/openSearch:totalResults')->text();
		$hasNext = ($this->maxResults + $this->startIndex <= $totalResults);
		
		return array(
				'contacts' => $contacts,
				'hasNext' => $hasNext,
		);
	}
	
	
}