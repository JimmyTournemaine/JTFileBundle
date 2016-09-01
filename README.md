#Installation

##Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer require jimmytournemaine/file-bundle "~1.0"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

##Step 2: Enable the Bundle


Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new JT\FileBundle\JTFileBundle(),
        );

        // ...
    }

    // ...
}
```

# How to use it ?

## Create your entity

```php
<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Post
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Avatar", cascade={"persist","remove"})
     */
    private $avatar;
}

```

__Be carefull !__ Don't forget to use ```cascade={"persist"}``` if you want your file to be uploaded.
For same reasons, use ```cascade={"remove"}``` to delete the file entity and the file itself when you delete the Post entity.

```php
<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JT\FileBundle\Entity\File;

/**
 * @ORM\Entity
 */
class Avatar extends File
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function getTargetDirectory()
    {
        return 'avatar';
    }
}
```

The ```getTargetDirectory()``` method must return the directory where files will be stored. In this example it would be **/web/avatar**. You should create the directory and edit permissions otherwise the bundle could not write in your repertory.

## Create the form

```php
// use JTFileBundle\Form\Type\FileType;
$builder->add('avatar', FileType::class, array(
        'data_class' => Avatar::class
    ));
}
```

## That's it !

You can use any entity as Avatar for all kinds of doctrine relationships without any problem.
When a **UploadableFile** is persist (or remove) the associate file is upload (or delete).

### Download your files

I just added a little feature to download a file from a controller :
```php
return $this->get('jt_file.downloader')->createResponse($entity);
```

And to download a ZIP of several entities :
```php
return $this->get('jt_file.downloader')->createResponse($entities);
```