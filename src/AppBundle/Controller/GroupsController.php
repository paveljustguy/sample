<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Group;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * @Route("/groups")
 */
class GroupsController extends Controller
{
    /**
     * @Route("", name = "groups_collection")
     * @Method("GET")
     */
    public function collection()
    {
        $groups = $this->getDoctrine()
            ->getRepository(Group::class)
            ->findAll();

        return $this->json($groups);
    }

    /**
     * @Route("/{id}", name = "groups_item", requirements = {
     *      "id": "\d+"
     * }))
     * @Method("GET")
     */
    public function item(Group $group)
    {
        return $this->json($group);
    }

    /**
     * @Route("", name = "groups_create")
     * @Method("POST")
     */
    public function create(Request $request)
    {
        $json = $request->getContent() ?: '{}';
        $serializer = $this->get('serializer');
        $group = $serializer->deserialize($json, Group::class, 'json');

        $validator = $this->get('validator');
        $errors = $validator->validate($group);

        if (count($errors) > 0) {
            $messages = array_map(function (ConstraintViolation $error) {
                return [$error->getPropertyPath() => $error->getMessage()];
            }, iterator_to_array($errors));

            return $this->json(['error' => $messages], Response::HTTP_BAD_REQUEST);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($group);
        $manager->flush();

        return $this->json([], Response::HTTP_CREATED, [
            'Location' => $this->generateUrl('groups_item', ['id' => $group->getId()]),
        ]);
    }
}
