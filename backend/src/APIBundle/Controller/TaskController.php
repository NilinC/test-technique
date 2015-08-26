<?php

namespace APIBundle\Controller;

use DomainBundle\Entity\Task;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\Request;

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
     * @RequestParam(name="label", requirements=".+", description="task label")
     * @RequestParam(name="done", requirements="true | false | 0 | 1", description="task label")
     * @param ParamFetcherInterface $paramFetcher
     * @return \FOS\RestBundle\View\View
     */
    public function putTaskAction($id, ParamFetcherInterface $paramFetcher)
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

        $label = $paramFetcher->get("label");
        $done = $paramFetcher->get("done");

        $task->updateTask($label, $done);

        $em = $this
            ->getDoctrine()
            ->getManager()
        ;

        $em->persist($task);
        $em->flush();

        return $this->view($task);
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
