<?php
<<<<<<< HEAD
namespace JT\Filebundle\Uploader;

use JT\FileBundle\Model\UploadableFile;
=======
namespace JT\Filebundle\Uploader

use Symfony\Component\HttpFoundation\File\UploadedFile;
>>>>>>> ad591436b40a24ad124f92f9b9ce4ad77bf3920c

class FileUploader
{
	public function upload(UploadableFile $fileEntity)
	{
		$file = $fileEntity->getFile();
		$filename = md5(uniquid()).'.'.$file->guessExtension();
		$file->move($fileEntity->getTargetDirectory(), $filename);

		$fileEntity->setFilename($filename);
		$fileEntity->setOriginalName($file->getClientOriginalName() ?? 'no_named');

		return $filename;
	}
}

