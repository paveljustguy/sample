<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * @Route("/users")
 */
class UsersController extends Controller
{
    /**
     * @Route("", name = "users_collection")
     * @Method("GET")
     */
    public function collection()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->json($users);
    }

    /**
     * @Route("", name = "users_create")
     * @Method("POST")
     */
    public function create(Request $request)
    {
        $json = $request->getContent() ?: '{}';
        $serializer = $this->get('serializer');
        $user = $serializer->deserialize($json, User::class, 'json');

        $validator = $this->get('validator');
        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $messages = array_map(function (ConstraintViolation $error) {
                return [$error->getPropertyPath() => $error->getMessage()];
            }, iterator_to_array($errors));

            return $this->json(['error' => $messages], Response::HTTP_BAD_REQUEST);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();

        return $this->json([], Response::HTTP_CREATED, [
            'Location' => $this->generateUrl('users_item', ['id' => $user->getId()]),
        ]);
    }

    /**
     * @Route("/{id}", name = "users_item", requirements = {
     *      "id": "\d+"
     * }))
     * @Method("GET")
     */
    public function item(User $user)
    {
        return $this->json($user);
    }

    /**
     * @Route("/{id}", name = "users_modify", requirements = {
     *      "id": "\d+"
     * }))
     * @Method("POST")
     */
    public function modify(User $user, Request $request)
    {
        $json = $request->getContent() ?: '{}';
        $serializer = $this->get('serializer');
        $serializer->deserialize($json, User::class, 'json', ['user' => $user]);

        $validator = $this->get('validator');
        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            $messages = array_map(function (ConstraintViolation $error) {
                return [$error->getPropertyPath() => $error->getMessage()];
            }, iterator_to_array($errors));

            return $this->json(['error' => $messages], Response::HTTP_BAD_REQUEST);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();

        return $this->json([]);
    }
}
