<?php


namespace App\Service;


use App\Entity\Author;
use Doctrine\Common\Collections\Collection;

class AuthorManager
{

    /**
     * @param Collection|Author[] $authors
     * @return string
     */
    public static function getEtag(array $authors){

        return hash('crc32', serialize($authors));
    }
}