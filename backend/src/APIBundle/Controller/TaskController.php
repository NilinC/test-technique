<?php

namespace APIBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class TaskController extends FOSRestController
{
    public function getTasksAction()
    {
        $tasks = $this
            ->getDoctrine()
            ->getRepository('DomainBundle:Task')
            ->findAll()
        ;

        return $this->view($tasks);
    }

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
}
