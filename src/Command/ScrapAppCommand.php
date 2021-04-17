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
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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
    /**
     * @var HttpClientInterface
     */
    private $httpClient;


    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $em, LoggerInterface $logger)
    {
        @ini_set("memory_limit", -1);

        $this->httpClient = $httpClient;
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

        $url = 'http://www.app.gov.al/prokurimet-me-vlere-te-vogel/';
        $browser = new HttpBrowser($this->httpClient);
        $crawler = $browser->request('GET', $url);
        $tenderList = $crawler->filter('.list-group-item.list-group-item-result.list-header-margin .list-group-item-heading');

        foreach ($tenderList as $tender) {

            $tenderCrawler = new Crawler($tender);
            $tenderEntry = $tenderCrawler->children('.row');
            if ($tenderEntry->count() == 2) {
                $this->scrapEntry($tenderEntry);
            }

            $tenderEntry->each(function (Crawler $node) {

            });
//            foreach ($c as $el)
//                dump($el->nodeName);
////                dump($el->textContent);
//            dump('-----END-----');
        }
        die;

//        foreach ($items as $item){
//            $c = new Crawler($item->);
//            $t = $c->children();
//            foreach ($t as $d){
//                dump($d->nodeName);
//            }
//
//            die();
//        }


//        gc_enable();
//
//
//            $this->em->persist($faceBookUser);
//                $this->em->flush();
//                gc_collect_cycles();
//                $this->em->clear();
//
//
//        $this->em->flush();
        return Command::SUCCESS;
    }

    private function scrapEntry(Crawler $tenderEntry)
    {
        $tender = [
            'name' => null,
            'agency' => null,
            'openDate' => null,
            'closeDate' => null,
            'referenceNumber' => null,
            'hasLot' => null,
            'canceled' => null,
            'suspended' => null,
            'type' => null,
        ];

        $firstRow = $tenderEntry->eq(0);
        $secondRow = $tenderEntry->eq(1);

        if ($firstRow) {
            $firstRow = $firstRow->eq(0);
            if ($firstRow) {

                $tenderNameLabel = $firstRow->filter('strong')->text();
                $moreLinkLabel = $firstRow->filter('.more-info')->text();

                $allText = $firstRow->text();
                $toRemove = [trim($tenderNameLabel), trim($moreLinkLabel)];
                $cleanedText = trim(str_replace($toRemove, '', $allText));
                $tender['name'] = $cleanedText;
            }

            $modal = $this->getModal($firstRow);
            $type = $this->getType($modal);
        }

        if ($secondRow) {
            $tender['agency'] = $this->getTenderAgency($secondRow);
            $tender['openDate'] = $this->getOpenDate($secondRow);
            $tender['closeDate'] = $this->getCloseDate($secondRow);
            $tender['referenceNumber'] = $this->getReferenceNumber($secondRow);
            $tender['hasLot'] = $this->hasLot($secondRow);
            $tender['canceled'] = $this->isCanceled($secondRow);
            $tender['suspended'] = $this->isSuspended($secondRow);
            $tender['suspended'] = $this->isSuspended($secondRow);
        }

        dump($tender);
        die;
    }

    private function getTenderAgency(Crawler $row): string
    {
        return trim($row->filter('span[style="color: #607D8B"]')->text());
    }

    private function getOpenDate(Crawler $row): \DateTime
    {
        $date = trim($row->filter('ul > li:nth-child(3) > span > span')->text());
        $hour = trim($row->filter('ul > li:nth-child(3) > span > small')->text());
        return new \DateTime("$date $hour");
    }

    private function getCloseDate(Crawler $row): \DateTime
    {
        $date = trim($row->filter('ul > li:nth-child(5) > span > span')->text());
        $hour = trim($row->filter('ul > li:nth-child(5) > span > small')->text());
        return new \DateTime("$date $hour");
    }

    private function getReferenceNumber(Crawler $row): string
    {
        $referenceNumber = trim($row->filter('ul > li:nth-child(7) > span > span')->text());
        return $referenceNumber;
    }

    private function hasLot(Crawler $row): bool
    {
        $hasLot = trim($row->filter('ul > li:nth-child(9) > span > span > i')->text());
        dump($hasLot);
        return strcasecmp(trim($hasLot), 'po') == 0 ? true : false;
    }

    private function isCanceled(Crawler $row): bool
    {
        $hasLot = trim($row->filter('ul > li:nth-child(11) > span > span > i')->text());
        dump($hasLot);
        return strcasecmp(trim($hasLot), 'po') == 0;
    }

    private function isSuspended(Crawler $row): bool
    {
        $suspended = trim($row->filter('ul > li:nth-child(13) > span > span > i')->text());
        dump($suspended);
        return strcasecmp(trim($suspended), 'po') == 0;
    }

    private function getModal(Crawler $row): ?Crawler
    {
        $modalId = trim($row->filter('div.more-info a')->attr('href'));
        $modal = $row->filter($modalId);
        return $modal;
    }

    private function getType(Crawler $modal): ?string
    {
        dump($modal->text());
        $type = trim($modal->filter('ul > li:nth-child(3) > div > div.col-lg-8.col-md-8.col-sm-12.col-xs-12 > small')->text());
        dump($type);
        return $type;
    }


}
