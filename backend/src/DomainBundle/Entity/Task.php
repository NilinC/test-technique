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
     * @var string
     * @Column(type="datetime", name="created_at")
     */
    private $createdAt;

    /**
     * @var string
     * @Column(type="datetime", name="modified_at")
     */
    private $modifiedAt;
}
