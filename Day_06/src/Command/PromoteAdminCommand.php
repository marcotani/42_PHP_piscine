<?php
// Usage: php bin/console app:promote-admin user@example.com
namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:promote-admin',
    description: 'Assign ROLE_ADMIN to a user by email.',
)]
class PromoteAdminCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'User email');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user) {
            $output->writeln('<error>User not found.</error>');
            return Command::FAILURE;
        }
        $roles = $user->getRoles();
        if (!in_array('ROLE_ADMIN', $roles)) {
            $roles[] = 'ROLE_ADMIN';
            $user->setRoles($roles);
            $this->em->flush();
            $output->writeln('<info>ROLE_ADMIN assigned to ' . $email . '.</info>');
        } else {
            $output->writeln('<comment>User already has ROLE_ADMIN.</comment>');
        }
        return Command::SUCCESS;
    }
}
