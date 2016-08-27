<?php
<<<<<<< HEAD
namespace JT\Filebundle\Uploader;

use Symfony\Component\Filesystem\Filesystem;
use JT\FileBundle\Model\UploadableFile;

class FileDeleter
{
	private $fileSystem;
=======
namespace JT\Filebundle\Uploader

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class FileDeleter
{
	private fileSystem;
>>>>>>> ad591436b40a24ad124f92f9b9ce4ad77bf3920c

	public function __construct(Filesystem $sys)
	{
		$this->fileSystem = $sys;
	}

	public function delete(UploadableFile $fileEntity)
	{
		$this->fileSystem->remove($fileEntity->getAbsolutePathname());
	}
}

