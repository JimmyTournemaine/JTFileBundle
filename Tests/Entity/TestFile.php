<?php
namespace JT\FileBundle\Tests\Entity;

use JT\FileBundle\Entity\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TestFile extends File
{

    protected $id;

    public function __construct(UploadedFile $file)
    {
        $this->setFile($file);
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \JT\FileBundle\Model\UploadableFile::getTargetDirectory()
     */
    public function getTargetDirectory()
    {
        return 'tests';
    }
}