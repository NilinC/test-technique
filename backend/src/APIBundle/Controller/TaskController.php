<?php

namespace APIBundle\Controller;

use DomainBundle\Entity\Task;
use FOS\RestBundle\Controller\FOSRestController;

class TaskController extends FOSRestController
{
    /**
     * @return \FOS\RestBundle\View\View
     */
    public function getTasksAction()
    {
        $tasks = $this
            ->getDoctrine()
            ->getRepository('DomainBundle:Task')
            ->findAll()
        ;

        return $this->view($tasks);
    }

    /**
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function getTaskAction($id)
    {
        $task = $this
            ->getDoctrine()
            ->getRepository('DomainBundle:Task')
            ->find($id)
        ;

        if (is_null($task)) {
            return $this->view(array('error' => sprintf('Task with id %d not found', $id)), 404);
        }

        return $this->view($task);
    }

    /**
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function deleteTaskAction($id)
    {
        $task = $this
            ->getDoctrine()
            ->getRepository('DomainBundle:Task')
            ->find($id)
        ;

        if (is_null($task)) {
            return $this->view(array('error' => sprintf('Task with id %d not found', $id)), 404);
        }

        $em = $this
            ->getDoctrine()
            ->getManager()
        ;

        $em->remove($task);
        $em->flush();

        return $this->view(null, 204);
    }

    /**
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function checkTaskAction($id)
    {
        /** @var Task $task */
        $task = $this
            ->getDoctrine()
            ->getRepository('DomainBundle:Task')
            ->find($id)
        ;

        if (is_null($task)) {
            return $this->view(array('error' => sprintf('Task with id %d not found', $id)), 404);
        }

        $task->toggleTaskState();

        $em = $this
            ->getDoctrine()
            ->getManager()
        ;

        $em->persist($task);
        $em->flush();

        return $this->view($task);
    }
}
