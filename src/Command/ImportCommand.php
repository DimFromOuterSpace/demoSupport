<?php

namespace App\Command;

use App\Import\UserImport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ImportCommand extends Command
{
    use LockableTrait;

    private $userImport;

    public function __construct(
        UserImport $userImport
    )
    {
        parent::__construct();

        $this->userImport = $userImport;
    }

    protected function configure()
    {
        $this
            ->setName('import')
            ->addArgument('filePath', InputArgument::REQUIRED, 'Chemin du fichier à importer')
            ->addArgument('delimitor', InputArgument::OPTIONAL, 'Délimiteur de champ', ';');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        if (!$this->lock()) {
            return 0;
        }

        $fileImport = $input->getArgument('filePath');
        $delimitor = $input->getArgument('delimitor');

        if (!file_exists($fileImport)) {
            throw new \InvalidArgumentException('Fichier '.$fileImport.' inexistant');
        }

        $this->userImport->setData(file_get_contents($fileImport));
        $this->userImport->deserializeUsers($delimitor);
        $this->userImport->process();

        $this->release();
    }
}
