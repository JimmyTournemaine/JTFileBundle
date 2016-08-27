<?php
namespace JT\Filebundle\Uploader;

use Symfony\Component\Filesystem\Filesystem;
use JT\FileBundle\Model\UploadableFile;

class FileDeleter
{
	private $fileSystem;

	public function __construct(Filesystem $sys)
	{
		$this->fileSystem = $sys;
	}

	public function delete(UploadableFile $fileEntity)
	{
		$this->fileSystem->remove($fileEntity->getAbsolutePathname());
	}
}

