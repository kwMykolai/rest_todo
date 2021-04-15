<?php

namespace App\Controller\Api\V1;

use App\Entity\ListEntry;
use App\Entity\ToDoList;
use App\Form\ListEntryType;
use App\Form\ToDoListFormType;
use App\Repository\ListEntryRepository;
use App\Repository\ToDoListRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as REST;

/**
 * Class ListController
 * @package App\Controller\Api\V1
 * @REST\Route("api/v1/lists", name="lists")
 */
class ListController extends AbstractFOSRestController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @REST\Get()
     * @param ToDoListRepository $repository
     * @param Request $request
     * @return Response
     */
    public function getLists(ToDoListRepository $repository, Request $request): Response
    {
        $query = $request->get("query", null);
        if ($query) {
            $lists = $repository->searchAny($query);
        } else {
            $lists = $repository->findAll();
        }
        return $this->handleView($this->view($lists));
    }

    /**
     * @REST\Get("/{list}")
     * @param ToDoList $list
     * @return Response
     */
    public function getList(ToDoList $list): Response
    {
        $view = $this->view(
            $list
        );
        $context = new Context();
        $context->setGroups(["rest", "with_entries"]);
        $view->setContext($context);
        return $this->handleView($view);
    }

    /**
     * @REST\Post()
     * @param Request $request
     * @return Response
     */
    public function createList(Request $request): Response
    {
        $data = $request->request->all();
        $form = $this->createForm(ToDoListFormType::class, new ToDoList());
        $form->submit($data);
        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }
        $newList = $form->getData();
        $this->manager->persist($newList);
        $this->manager->flush();
        $this->manager->refresh($newList);
        return $this->handleView(
            $this->view(
                $newList,
                Response::HTTP_CREATED
            )
        );
    }

    /**
     * @REST\Delete("/{list}")
     * @param ToDoList $list
     * @return Response
     */
    public function deleteList(ToDoList $list): Response
    {
        $this->manager->remove($list);
        $this->manager->flush();
        return $this->handleView(
            $this->view(
                null,
                Response::HTTP_OK
            )
        );
    }

    /*
    I know extracting code below to a separate controller with prefix would have been cleaner, but entries can not
    exist without list, so they don't deserve separate controller;
    */
    /**
     * @REST\Get("/{list}/entries")
     * @param ListEntryRepository $repository
     * @param Request $request
     * @param ToDoList $list
     * @return Response
     */
    public function getEntries(ListEntryRepository $repository, Request $request, ToDoList $list): Response
    {
        $query = $request->get("query", null);
        if ($query) {
            $entries = $repository->searchAnyForList($list, $query);
        } else {
            $entries = $repository->findAllForList($list);
        }
        return $this->handleView($this->view($entries));
    }

    /**
     * @REST\Post("/{list}/entries")
     * @param Request $request
     * @param ToDoList $list
     * @return Response
     */
    public function createEntry(Request $request, ToDoList $list): Response
    {
        $data = $request->request->all();
        $form = $this->createForm(ListEntryType::class, new ListEntry());
        $form->submit($data);
        if (false === $form->isValid()) {
            return $this->handleView(
                $this->view($form)
            );
        }
        $newEntry = $form->getData();
        $list->addEntry($newEntry);
        $this->manager->flush();
        $this->manager->refresh($newEntry);
        return $this->handleView(
            $this->view(
                $newEntry,
                Response::HTTP_CREATED
            )
        );
    }

    /**
     * @REST\Delete("/{list}/entries/{id}")
     * @param ToDoList $list
     * @param ListEntry $entry
     * @return Response
     */
    public function deleteEntry(ToDoList $list, ListEntry $entry): Response
    {
        $this->manager->remove($entry);
        $this->manager->flush();
        return $this->handleView(
            $this->view(
                null,
                Response::HTTP_OK
            )
        );
    }
}
