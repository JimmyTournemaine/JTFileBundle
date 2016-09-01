<?php
namespace JT\FileBundle\Tests\Uploader;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use JT\FileBundle\Uploader\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use JT\FileBundle\Tests\Entity\TestFile;

class DefaultControllerTest extends WebTestCase
{
    private $container;

    public function setUp()
    {
        self::bootKernel();

        $this->container = self::$kernel->getContainer();
    }

    public function testUpload()
    {
        $file = new UploadedFile('/Users/jimmytournemaine/Pictures/wall/20113.jpg', '20113.jpg', 'image/jpeg');
        $entity = new TestFile($file);

        $uploader = new FileUploader('/Users/jimmytournemaine/Sites/JTFileBundle/app');
        $uploader->upload($entity);
    }
}
