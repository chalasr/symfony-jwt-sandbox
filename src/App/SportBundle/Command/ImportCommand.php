<?php

namespace App\SportBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\SportBundle\Entity\Sport;


class ImportCommand extends ContainerAwareCommand
{
    protected $options = array(
        'filename' => 'sports.csv',
        'ignoreFirstl' => true
    );

    protected function configure()
    {
        $this
            ->setName('personne:import')
            ->setDescription('Import personne data')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $mes = '<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>';
        $this->_logOut($mes, $output);

        $ignoreFirstl = $this->options['ignoreFirstl'];

        $path = $this->getContainer()->get('kernel')->locateResource('@AppSportBundle/Resources/public/sports.csv' . $this->options['filename'];

        $rows = array();
        if (($handle = fopen($path, "r")) !== FALSE) {
            $i = 0;
            $pageSize = 100;
            while (($data = fgetcsv($handle, null, ",", '"')) !== FALSE) {
                $i++;
                if ($ignoreFirstl && $i == 1) { continue; }
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
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>', $output);
    }

    /**
     * Import.
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @param  array           $data
     * @return [type]                  [description]
     */
    protected function import(InputInterface $input, OutputInterface $output, $data)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repo = $em->getRepository('AppSportBundle:Sport');
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        for ($i = 0; $i < count($data); $i++) {
            new Sport();
            $personne->setName($data[$i][0]);
            $personne->setIcon($data[$i][3]);
            $personne->setIndication($data[$i][4]);
            $personne->setCountry($country);
            $personne->setJournal($data[$i][13]);
            try {
                $em->persist($personne);
            } catch(Exception $e) {
                $this->_log($e->getMessage());
            }
        }

        $em->flush();
        $em->clear();
    }

    /**
     * Parse a csv file
     *
     * @return array
     */
    private function parseCSV()
    {
        $ignoreFirstl = $this->options['ignoreFirstl'];
        $path = __DIR__ . '/../Data/' . $this->options['filename'];
        $rows = array();

        if (($handle = fopen($path, "r")) !== FALSE) {
            $i = 0;
            while (($data = fgetcsv($handle, null, ",", '"')) !== FALSE) {
                $i++;
                if ($ignoreFirstl && $i == 1) { continue; }
                $rows[] = $data;
            }
            fclose($handle);
        }

        return $rows;
    }
}
