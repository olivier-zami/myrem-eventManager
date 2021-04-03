<?php
namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=\App\Repository\Event::class)
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    function getId(){return $this->id;}

    /**
     * @ORM\Column(type="datetime", length=255)
     */
    protected $start;

    function setStart($start){$this->start = $start;}
    function getStart(){return $this->start;}

    /**
     * @ORM\Column(type="datetime", length=255)
     */
    protected $end;

    function setEnd($end){$this->end = $end;}
    function getEnd(){return $this->end;}
}