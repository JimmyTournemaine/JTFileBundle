<?php
namespace JT\Filebundle\Uploader;

use JT\FileBundle\Model\UploadableFile;

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

