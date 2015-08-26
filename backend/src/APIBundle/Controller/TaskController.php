<?php

namespace APIBundle\Controller;

use DomainBundle\Entity\Task;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class TaskController extends FOSRestController
{
    /**
     * @ApiDoc(
     *      description="Get all the tasks",
     *      statusCodes={
     *          200="OK",
     *          400="Bad Request"
     *      }
     * )
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
     * @ApiDoc(
     *      description="Get one task by its id",
     *      statusCodes={
     *          200="OK",
     *          400="Bad Request",
     *          404="Not Found"
     *      },
     *      output="DomainBundle\Entity\Task"
     * )
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
     * @ApiDoc(
     *      description="Add a new task to the todolist",
     *      statusCodes={
     *          201="Created",
     *          400="Bad Request"
     *      },
     *      output="DomainBundle\Entity\Task"
     * )
     * @RequestParam(name="label", requirements=".+", description="task label")
     * @RequestParam(name="done", requirements="true|false|0|1", description="task done")
     * @param ParamFetcherInterface $paramFetcher
     * @return \FOS\RestBundle\View\View
     */
    public function postTaskAction(ParamFetcherInterface $paramFetcher)
    {
        $label = $paramFetcher->get("label");
        $done = $paramFetcher->get("done");

        $task = Task::create($label, $done);

        $em = $this
            ->getDoctrine()
            ->getManager()
        ;

        $em->persist($task);
        $em->flush();

        return $this->view($task, 201);
    }

    /**
     * @ApiDoc(
     *      description="Update a task's label or if it's checked or unchecked",
     *      statusCodes={
     *          200="OK",
     *          400="Bad Request",
     *          404="Not Found"
     *      },
     *      output="DomainBundle\Entity\Task"
     * )
     * @param $id
     * @RequestParam(name="label", requirements=".+", description="task label")
     * @RequestParam(name="done", requirements="true|false|0|1", description="task done")
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

        $task->update($label, $done);

        $em = $this
            ->getDoctrine()
            ->getManager()
        ;

        $em->persist($task);
        $em->flush();

        return $this->view($task);
    }

    /**
     * @ApiDoc(
     *      description="Delete a task",
     *      statusCodes={
     *          204="No Content",
     *          400="Bad Request",
     *          404="Not Found"
     *      }
     * )
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
     * @ApiDoc(
     *      description="Toggle a task from checked to unchecked and vice-versa",
     *      statusCodes={
     *          200="OK",
     *          400="Bad Request",
     *          404="Not Found"
     *      },
     *      output="DomainBundle\Entity\Task"
     * )
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
