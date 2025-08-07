<?php
namespace App\ex13\Controller;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    #[Route('/ex13', name: 'ex13_list')]
    public function list(EntityManagerInterface $em, Request $request): Response
    {
        $employees = $em->getRepository(Employee::class)->findAll();
        $message = $request->query->get('message', '');
        $error = $request->query->get('error', '');
        return $this->render('ex13/list.html.twig', [
            'employees' => $employees,
            'message' => $message,
            'error' => $error,
        ]);
    }

    #[Route('/ex13/create', name: 'ex13_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $employee = new Employee();
        $managers = $em->getRepository(Employee::class)->findAll();
        if ($request->isMethod('POST')) {
            $data = $request->request;
            $employee->setFirstname($data->get('firstname'));
            $employee->setLastname($data->get('lastname'));
            $employee->setEmail($data->get('email'));
            $employee->setBirthdate(new \DateTime($data->get('birthdate')));
            $employee->setActive($data->get('active') ? true : false);
            $employee->setEmployedSince(new \DateTime($data->get('employed_since')));
            $employee->setEmployedUntil($data->get('employed_until') ? new \DateTime($data->get('employed_until')) : null);
            $employee->setHours($data->get('hours'));
            $employee->setSalary((int)$data->get('salary'));
            $employee->setPosition($data->get('position'));
            $managerId = $data->get('manager');
            if ($managerId) {
                $manager = $em->getRepository(Employee::class)->find($managerId);
                $employee->setManager($manager);
            }
            // Validation (simple)
            $error = '';
            if (!$employee->getFirstname() || !$employee->getLastname() || !$employee->getEmail()) {
                $error = 'Firstname, lastname, and email are required.';
            } elseif (!filter_var($employee->getEmail(), FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email format.';
            } elseif ($em->getRepository(Employee::class)->findOneBy(['email' => $employee->getEmail()])) {
                $error = 'Email must be unique.';
            } elseif ($employee->getManager() && $employee->getManager() === $employee) {
                $error = 'An employee cannot be their own manager.';
            }
            if (!$error) {
                $em->persist($employee);
                $em->flush();
                return $this->redirectToRoute('ex13_list', ['message' => 'Employee created successfully!']);
            }
            return $this->render('ex13/form.html.twig', [
                'employee' => $employee,
                'managers' => $managers,
                'error' => $error,
                'edit' => false,
            ]);
        }
        return $this->render('ex13/form.html.twig', [
            'employee' => $employee,
            'managers' => $managers,
            'error' => '',
            'edit' => false,
        ]);
    }

    #[Route('/ex13/edit/{id}', name: 'ex13_edit')]
    public function edit($id, Request $request, EntityManagerInterface $em): Response
    {
        $employee = $em->getRepository(Employee::class)->find($id);
        if (!$employee) {
            return $this->redirectToRoute('ex13_list', ['error' => 'Employee not found.']);
        }
        $managers = $em->getRepository(Employee::class)->findAll();
        if ($request->isMethod('POST')) {
            $data = $request->request;
            $employee->setFirstname($data->get('firstname'));
            $employee->setLastname($data->get('lastname'));
            $employee->setEmail($data->get('email'));
            $employee->setBirthdate(new \DateTime($data->get('birthdate')));
            $employee->setActive($data->get('active') ? true : false);
            $employee->setEmployedSince(new \DateTime($data->get('employed_since')));
            $employee->setEmployedUntil($data->get('employed_until') ? new \DateTime($data->get('employed_until')) : null);
            $employee->setHours($data->get('hours'));
            $employee->setSalary((int)$data->get('salary'));
            $employee->setPosition($data->get('position'));
            $managerId = $data->get('manager');
            if ($managerId) {
                $manager = $em->getRepository(Employee::class)->find($managerId);
                $employee->setManager($manager);
            } else {
                $employee->setManager(null);
            }
            // Validation (simple)
            $error = '';
            if (!$employee->getFirstname() || !$employee->getLastname() || !$employee->getEmail()) {
                $error = 'Firstname, lastname, and email are required.';
            } elseif (!filter_var($employee->getEmail(), FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email format.';
            } elseif ($employee->getManager() && $employee->getManager()->getId() === $employee->getId()) {
                $error = 'An employee cannot be their own manager.';
            } else {
                $existing = $em->getRepository(Employee::class)->findOneBy(['email' => $employee->getEmail()]);
                if ($existing && $existing->getId() !== $employee->getId()) {
                    $error = 'Email must be unique.';
                }
            }
            if (!$error) {
                $em->flush();
                return $this->redirectToRoute('ex13_list', ['message' => 'Employee updated successfully!']);
            }
            return $this->render('ex13/form.html.twig', [
                'employee' => $employee,
                'managers' => $managers,
                'error' => $error,
                'edit' => true,
            ]);
        }
        return $this->render('ex13/form.html.twig', [
            'employee' => $employee,
            'managers' => $managers,
            'error' => '',
            'edit' => true,
        ]);
    }

    #[Route('/ex13/delete/{id}', name: 'ex13_delete')]
    public function delete($id, EntityManagerInterface $em): Response
    {
        $employee = $em->getRepository(Employee::class)->find($id);
        if (!$employee) {
            return $this->redirectToRoute('ex13_list', ['error' => 'Employee not found.']);
        }
        $em->remove($employee);
        $em->flush();
        return $this->redirectToRoute('ex13_list', ['message' => 'Employee deleted successfully!']);
    }
}
