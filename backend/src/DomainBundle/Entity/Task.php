<?php

namespace DomainBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * Task of todolist
 * @Entity()
 * @Table(name="Task")
 */
class Task 
{
    /**
     * @var int
     * @Id()
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Column(type="string", name="label", length=255)
     */
    private $label;

    /**
     * @var string
     * @Column(type="boolean", name="done")
     */
    private $done;

    /**
     * @var \DateTime
     * @Column(type="datetime", name="created_at")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @Column(type="datetime", name="modified_at")
     */
    private $modifiedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Toggle the task's status from check to uncheck and vice versa
     * @return $this
     */
    public function toggleTaskState()
    {
        $this->done = !$this->done;
        $this->updateDateOfLastModification();

        return $this;
    }

    /**
     * Update the task's modifiedAt date
     * @return $this
     */
    private function updateDateOfLastModification()
    {
        $this->modifiedAt = new \DateTime();

        return $this;
    }
}
