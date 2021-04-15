<?php

namespace App\Command;

use App\Entity\FacebookUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Psr\Log\LoggerInterface;
use League\Csv\Reader;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class ScrapAppCommand extends Command
{
    protected static $defaultName = 'app:scrap-app';
    protected static $defaultDescription = 'scrap app';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /** @var LoggerInterface $logger */
    private $logger;


    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        @ini_set("memory_limit", -1);

        $this->em = $em;
        $this->em
            ->getConnection()
            ->getConfiguration()
            ->setSQLLogger(null);
        $this->logger = $logger;

        parent::__construct();

    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription);
//            ->addArgument('fileName', InputArgument::REQUIRED, 'facebook csv file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $browser = new HttpBrowser(HttpClient::create());
        $browser->request('GET', 'https://github.com');


        try {
            $this->csvReader = Reader::createFromPath($csvPath, 'r');
            $this->csvReader->setDelimiter(':');
            $this->csvReader->setHeaderOffset(0);
        } catch (\Exception $ex) {
            $this->logger->error("Error opening csv file", ['error' => $ex]);
            throw $ex;
        }

        $headers = $this->csvReader->getHeader(); //returns the CSV header record
        $this->logger->info('headers', $headers);


        $records = $this->csvReader->getRecords(); //returns all the CSV records as an Iterator object
        $totalRecords = iterator_count($records);
        $this->logger->info('Total records found:' . $totalRecords);
        $io->info('Total records found:' . $totalRecords);

        $i = 1;
        gc_enable();

        foreach ($records as $record) {
            if ($io->isDebug() or $io->isVerbose()) {
//                echo $i;
//                $this->logger->info($i, $record);
            }

            $faceBookUser = $this->importUser($record);
            $this->em->persist($faceBookUser);
            if (++$i % 1000 == 0) {
                echo $i . PHP_EOL;
                $this->em->flush();
                gc_collect_cycles();
                $this->em->clear();
            }

        }

        $this->em->flush();
        return Command::SUCCESS;
    }




}
