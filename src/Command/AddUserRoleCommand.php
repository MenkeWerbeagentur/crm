<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:add-user-role',
    description: 'Fügt einer Benutzerentität eine Rolle hinzu.',
)]
class AddUserRoleCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Die E-Mail des Benutzers')
            ->addArgument('role', InputArgument::REQUIRED, 'Die hinzuzufügende Rolle (z. B. ROLE_ADMIN)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $role = strtoupper($input->getArgument('role'));

        // Benutzer suchen
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $output->writeln('<error>Benutzer mit der E-Mail "' . $email . '" wurde nicht gefunden.</error>');
            return Command::FAILURE;
        }

        // Rolle hinzufügen, falls noch nicht vorhanden
        $roles = $user->getRoles();
        if (!in_array($role, $roles, true)) {
            $roles[] = $role;
            $user->setRoles($roles);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $output->writeln('<info>Rolle "' . $role . '" erfolgreich zum Benutzer "' . $email . '" hinzugefügt.</info>');
        } else {
            $output->writeln('<comment>Benutzer hat bereits die Rolle "' . $role . '".</comment>');
        }

        return Command::SUCCESS;
    }
}
