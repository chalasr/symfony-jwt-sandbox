<?php

namespace App\SportBundle\Command;

use App\SportBundle\Entity\Sport;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
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
            ->setDescription('Import sports fixtures')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $ignoreFirstl = $this->options['ignoreFirstl'];
        $path = $this->getContainer()->get('kernel')->locateResource('@AppSportBundle/Resources/public/'.$this->options['filename']);
        $output->writeln('<comment>Start : '.$now->format('d-m-Y G:i:s').' ---</comment>');

        $rows = array();
        if (($handle = fopen($path, 'r')) !== false) {
            $i = 0;
            $pageSize = 100;
            while (($data = fgetcsv($handle, null, ',', '"')) !== false) {
                ++$i;
                if ($ignoreFirstl && $i == 1) {
                    continue;
                }
                $rows[] = $data;
                if ($i % $pageSize == 0) {
                    $this->import($input, $output, $rows);
                    $rows = array();
                }
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
     *
     * @return [type] [description]
     */
    protected function import(InputInterface $input, OutputInterface $output, $data)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('AppSportBundle:Sport');
        $em->getConnection()->getConfiguration()->setSQLLogger(null);
        $batchSize = 20;
        for ($i = 0; $i < count($data); ++$i) {
            $sport = new Sport();
            $categoryRepo = $em->getRepository('AppSportBundle:Category');
            $category = $categoryRepo->findOneByOrCreate(['name' => $data[$i][0]]);
            $sport->addCategory($category);
            $sport->setName($data[$i][1]);
            $tags = explode(', ', $data[$i][2]);
            $tagRepo = $em->getRepository('AppSportBundle:Tag');
            foreach ($tags as $tagName) {
                $tag = $tagRepo->findOneByOrCreate(['name' => $tagName]);
                $sport->addTag($tag);
            }

            $sport->setIsActive(intval($data[$i][3]));
            $sport->setIcon($data[$i][4]);
            try {
                $em->persist($sport);
                $output->writeln(sprintf('%s created', $sport->getName()));
            } catch (Exception $e) {
                $output->writeln($e->getMessage());
            }

            if (($count % $batchSize) === 0) {
                $em->flush();
                $em->clear();
            }
        }

        $em->flush();
    }

    /**
     * Parse a csv file.
     *
     * @return array
     */
    private function parseCSV()
    {
        $ignoreFirstl = $this->options['ignoreFirstl'];
        $path = __DIR__.'/../Data/'.$this->options['filename'];
        $rows = array();

        if (($handle = fopen($path, 'r')) !== false) {
            $i = 0;
            while (($data = fgetcsv($handle, null, ',', '"')) !== false) {
                ++$i;
                if ($ignoreFirstl && $i == 1) {
                    continue;
                }
                $rows[] = $data;
            }
            fclose($handle);
        }

        return $rows;
    }
}
