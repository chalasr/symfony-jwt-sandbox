<?php

namespace App\UserBundle\Command;

use App\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use RCH\Importer\Importer;
use RCH\Importer\Reader\CsvReader;
use RCH\Importer\Reader\ReaderInterface;
use RCH\Importer\Recorder\RecorderInterface;
use RCH\Importer\Recorder\DoctrineORMEntityRecorder;

error_reporting(E_ALL);

class ImportCommand extends ContainerAwareCommand
{
    protected $options = array(
        'filename'     => 'users.csv',
    );

    protected function configure()
    {
        $this
            ->setName('users:import')
            ->setDescription('Imports User fixtures')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $path = $this->getContainer()
            ->get('kernel')
            ->locateResource('@AppUserBundle/Resources/public/'.$this->options['filename'])
        ;

        $now = new \DateTime();
        $output->writeln('<comment>Start import : '.$now->format('d-m-Y G:i:s').' ---</comment>');

        $reader = new CsvReader($path);
        $recorder = new DoctrineORMEntityRecorder('AppUserBundle:User', $em);
        $recorder->setDateTimeFormat('m/d/Y')
            ->setKeymap(array('group' => 'group.name'));

        Importer::create($reader, $recorder)->import();

        // $now = new \DateTime();
        // $output->writeln('<comment>End : '.$now->format('d-m-Y G:i:s').' ---</comment>', $output);

        $users = $em->getRepository('AppUserBundle:User')->findAll();

        foreach($users as $user) {
          $output->writeln($user->getId().' '.$user->getEmail().PHP_EOL);
        }
    }
}
