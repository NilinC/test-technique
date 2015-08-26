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
     * @Column(type="datetime", name="modified_at", nullable=true)
     */
    private $modifiedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Create a new Task
     * @param $label
     * @param $done
     * @return Task
     */
    static public function create($label, $done)
    {
        $task = new self();
        $task->label = $label;
        $task->done = (boolean) $done;

        return $task;
    }

    /**
     * Update the task's label and if it done or not
     * @param $label
     * @param $done
     * @return $this
     */
    public function update($label, $done)
    {
        $this->label = $label;
        $this->done = (boolean) $done;
        $this->updateDateOfLastModification();

        return $this;
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
