<?php
namespace JT\FileBundle\Uploader;

use JT\FileBundle\Model\UploadableFile;
use Symfony\Component\Filesystem\Filesystem;

class FileUploader
{
    private $rootDir;
    private $filesystem;

    public function __construct(Filesystem $filesystem, $rootDir)
    {
        $this->filesystem = $filesystem;
        $this->rootDir = $rootDir;
    }

	public function upload(UploadableFile $fileEntity)
	{
	    $uploadDirectory = $this->rootDir . '/../web/'. $fileEntity->getTargetDirectory();
	    if (!$this->filesystem->exists($uploadDirectory)) {
	        $this->filesystem->mkdir($uploadDirectory);
	    }

		$file = $fileEntity->getFile();
		$filename = md5(uniqid()).'.'.$file->guessExtension();
		$file->move($uploadDirectory, $filename);
		$fileEntity->setFilename($filename);
		$fileEntity->setOriginalName($file->getClientOriginalName() ?? 'no_named');

		return $filename;
	}

	public function delete(UploadableFile $fileEntity)
	{
	    $pathname = $this->rootDir . '/../web/' . $fileEntity->getWebPathname();
	    $this->filesystem->remove($pathname);
	}
}