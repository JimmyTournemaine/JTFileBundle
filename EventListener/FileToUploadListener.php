<?php
namespace JT\FileBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use JT\FileBundle\Uploader\FileUploader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use JT\FileBundle\Model\UploadableFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileToUploadListener implements EventSubscriber
{
	private $uploader;

	public function __construct(FileUploader $uploader)
	{
		$this->uploader = $uploader;
	}

	public function getSubscribedEvents()
	{
	    return array(Events::prePersist, Events::preUpdate, Events::preRemove, Events::preRemove);
	}

	public function prePersist(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
		if(!$entity instanceof UploadableFile) {
			return;
		}
		$this->uploader->upload($entity);
	}

	public function preUpdate(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
		if(!$entity instanceof UploadableFile) {
			return;
		}
		if (!$entity->getFile() instanceof UploadedFile){
		    return;
		}

		$this->uploader->upload($entity);
	}

	public function preRemove(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();
		if(!$entity instanceof UploadableFile) {
			return;
		}

		$this->uploader->delete($entity);
	}

}
