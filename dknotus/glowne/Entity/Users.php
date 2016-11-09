<?php

namespace NaszSystemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Encja z uzytkownikami
 *
 * Nie opisuje juz dokladnie pol jakich typow
 */
class Users
{
    /*minimalne pola w bazie*/
    private $id;
    private $name;
    private $surname;
    private $login;
    private $password;
    private $roles;
}