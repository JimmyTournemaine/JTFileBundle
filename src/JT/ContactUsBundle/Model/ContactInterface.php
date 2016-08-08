<?php
namespace JT\ContactUsBundle\Model;

interface ContactInterface
{
    public function getEmail();
    public function getSubject();
    public function getContent();
    public function getExtra();
}