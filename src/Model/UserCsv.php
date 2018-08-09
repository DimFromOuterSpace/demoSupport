<?php

namespace App\Model;

use App\Utils\Sanitize;

class UserCsv
{
    private $email;
    private $fistname;
    private $lastname;
    private $company;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = Sanitize::sanitizeEmail($email);
    }

    /**
     * @return mixed
     */
    public function getFistname()
    {
        return $this->fistname;
    }

    /**
     * @param mixed $fistname
     */
    public function setFistname($fistname): void
    {
        $this->fistname = $fistname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company): void
    {
        $this->company = $company;
    }


}