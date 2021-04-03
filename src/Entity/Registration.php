<?php
namespace App\Entity;

use App\Repository\RegistrationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegistrationRepository::class)
 */
class Registration
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    public function getId(){return $this->id;}

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $firstName;

    public function setFirstName($firstName){$this->firstName = $firstName;}
    public function getFirstName(){return $this->firstName;}

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $lastName;

    public function setLastName($lastName){$this->lastName = $lastName;}
    public function getLastName(){return $this->lastName;}

    /**
     * @ORM\Column(type="string", length=64)
     */
    protected $email;

    public function setEmail($email){$this->email = $email;}
    public function getEmail(){return $this->email;}

    /**
     * @ORM\Column(type="string", length=16)
     */
    protected $phoneNumber;

    public function setPhoneNumber($phoneNumber){$this->phoneNumber = $phoneNumber;}
    public function getPhoneNumber(){return $this->phoneNumber;}
}