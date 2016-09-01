<?php
namespace JT\FileBundle\Uploader;

use JT\FileBundle\Model\UploadableFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Create a BinaryFileResponse to download a file from an entity
 *
 * @author Jimmy Tournemaine <jimmy.tournemaine@yahoo.fr>
 */
class FileDownloader
{
    private $filesystem;
    private $rootDir;

    public function __construct(Filesystem $filesystem, $rootDir) {
        $this->filesystem = $filesystem;
        $this->rootDir = $rootDir;
    }

    public function createResponse($fileEntity)
    {
        if (!$fileEntity instanceof UploadableFile){
            return $this->createZipResponse($fileEntity);
        }

        $absFilename = $this->rootDir . '/../web/' . $fileEntity->getWebPathname();
        $response = new BinaryFileResponse($absFilename);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }

    private function createZipResponse(array $fileEntities)
    {
        $zip = new \ZipArchive();
        $zipName = '/tmp/'.uniqid().'.zip';

        $zip->open($zipName, \ZipArchive::CREATE);
        foreach($fileEntities as $file){
            $filename = $this->rootDir . '/../web/' . $file->getWebPathname();
            $zip->addFile($filename, $file->getOriginalName());
        }
        $zip->close();

        $response = new BinaryFileResponse($zipName);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }
}