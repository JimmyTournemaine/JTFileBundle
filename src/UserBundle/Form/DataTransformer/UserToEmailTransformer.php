<?php
namespace UserBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class UserToEmailTransformer implements DataTransformerInterface
{

    private $manager;

    public function __construct(UserManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Symfony\Component\Form\DataTransformerInterface::transform()
     */
    public function transform($value)
    {
        if ($value === null)
            return '';
        return $value->getEmail();
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \Symfony\Component\Form\DataTransformerInterface::reverseTransform()
     */
    public function reverseTransform($value)
    {
        $user = $this->manager->findUserByEmail($value);
        if ($user === null) {
            throw new TransformationFailedException(sprintf('An user with email %s does\'t exist.'), $value);
        }

        return $user;
    }
}