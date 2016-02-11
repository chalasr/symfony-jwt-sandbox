<?php

namespace App\SportBundle\Command;

use App\SportBundle\Entity\Sport;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

error_reporting('E_ALL');

class ImportCommand extends ContainerAwareCommand
{
    protected $options = array(
        'filename'     => 'sports.csv',
        'ignoreFirstl' => false,
    );

    protected function configure()
    {
        $this
            ->setName('sports:import')
            ->setDescription('Imports Sport fixtures')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ignoreFirstl = $this->options['ignoreFirstl'];
        $path = $this->getContainer()
            ->get('kernel')
            ->locateResource('@AppSportBundle/Resources/public/'.$this->options['filename'])
        ;

        $now = new \DateTime();
        $output->writeln('<comment>Start : '.$now->format('d-m-Y G:i:s').' ---</comment>');

        $rows = array();
        if (($handle = fopen($path, 'r')) !== false) {
            $i = 0;
            while (($data = fgetcsv($handle, null, ',', '"')) !== false) {
                ++$i;

                if ($ignoreFirstl && $i == 1) {
                    continue;
                }

                $rows[] = $data;
                $this->import($input, $output, $rows);
                $rows = [];
            }
            fclose($handle);
        }

        if (count($rows)) {
            $this->import($input, $output, $rows);
        }

        $now = new \DateTime();
        $output->writeln('<comment>End : '.$now->format('d-m-Y G:i:s').' ---</comment>', $output);
    }

    /**
     * Import.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param array           $data
     */
    protected function import(InputInterface $input, OutputInterface $output, $data)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        $repo = $em->getRepository('AppSportBundle:Sport');
        $categoryRepo = $em->getRepository('AppSportBundle:Category');
        $tagRepo = $em->getRepository('AppSportBundle:Tag');
        $batchSize = 20;
        for ($i = 0; $i < count($data); ++$i) {
            $tags = explode(', ', $data[$i][2]);
            $category = $categoryRepo->findOneByOrCreate(array(
                'name' => $data[$i][0],
            ));

            $sport = new Sport();
            $sport->setName($data[$i][1]);
            $sport->setIsActive(intval($data[$i][3]));
            $sport->setIcon($data[$i][4]);
            $sport->addCategory($category);

            foreach ($tags as $tagName) {
                $tag = $tagRepo->findOneByOrCreate(['name' => $tagName]);
                $sport->addTag($tag);
            }

            $em->persist($sport);
            $output->writeln(sprintf('%s created', $sport->getName()));

            if (($i % $batchSize) === 0) {
                $em->flush();
                $em->clear();
            }
        }

        $em->flush();
    }
}
