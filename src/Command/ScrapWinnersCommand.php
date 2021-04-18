<?php

namespace App\Command;

use App\Entity\Agency;
use App\Entity\Document;
use App\Entity\FacebookUser;
use App\Entity\Tender;
use App\Repository\AgencyRepository;
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

class ScrapWinnersCommand extends Command
{
    protected static $defaultName = 'app:wscrap-app';
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

    /**
     * @var Crawler
     */
    private $modalsCrawler;

    /**
     * @var AgencyRepository $agencyRepository
     */
    private $agencyRepository;


    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $em, AgencyRepository $agencyRepository, LoggerInterface $logger)
    {
        @ini_set("memory_limit", -1);

        $this->httpClient = $httpClient;
        $this->em = $em;
        $this->em
            ->getConnection()
            ->getConfiguration()
            ->setSQLLogger(null);
        $this->logger = $logger;
        $this->agencyRepository = $agencyRepository;

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

        $url = 'http://www.app.gov.al/njoftimi-i-fituesit/';
        $browser = new HttpBrowser($this->httpClient);
        $crawler = $browser->request('GET', $url);

        for ($p = 1; $p <= 50; ++$p) {

            $form = null;
            try {
                $button = $crawler->filter('.pagination-form')->selectButton($p);
                if(!$button->count()){
                    dump("Page $p not fund");
                    exit;
                }
                $form = $button->form();
            } catch (\Exception $ex) {
                dump($ex);
                dump("exception page " . $p);
                continue;
            }

            if ($form) {
                $crawler = $browser->submit($form);
            }

//            if($p <= 514){
//                dump($p);
//
//                continue;
//            }

            $tenderList = $crawler->filter('.list-group-item.list-group-item-result.list-header-margin .list-group-item-heading');

            gc_enable();
            foreach ($tenderList as $tender) {

                $tenderCrawler = new Crawler($tender);
                $tenderEntry = $tenderCrawler->children('.row');
                $this->modalsCrawler = $crawler->filter('.modal');
                if ($tenderEntry->count() == 2) {
                    $scrapedData = $this->scrapEntry($tenderEntry);
                    dd($scrapedData);
                    $tenderEntity = new Tender();

                    $agencyName = $scrapedData['agency'];
                    $agency = $this->agencyRepository->findOneByName($agencyName);

                    if($agency == null){
                        $agency = new Agency();
                        $agency->setName($agencyName);
                        $this->em->persist($agency);
                        $this->em->flush();
                    }

                    $tenderEntity->setAgency($agency);
                    $tenderEntity->setName($scrapedData['name']);
                    $tenderEntity->setPublicationDate($scrapedData['publicationDate']);
                    $tenderEntity->setOpenDate($scrapedData['openDate']);
                    $tenderEntity->setCloseDate($scrapedData['closeDate']);
                    $tenderEntity->setReferenceNumber($scrapedData['referenceNumber']);
                    $tenderEntity->setHasLot($scrapedData['hasLot']);
                    $tenderEntity->setCanceled($scrapedData['canceled']);
                    $tenderEntity->setSuspended($scrapedData['suspended']);
                    $tenderEntity->setType($scrapedData['type']);
                    $tenderEntity->setNotificationNumber($scrapedData['notificationNumber']);
                    $tenderEntity->setCpvCode($scrapedData['cpvCode']);
                    $this->em->persist($tenderEntity);

                    foreach ($scrapedData['tenderDocuments'] as $docUrl) {
                        $document = new Document();
                        $document->setTender($tenderEntity);
                        $document->setType('tenderDocument');
                        $document->setUrl($docUrl);
                        $tenderEntity->addTenderDocument($document);
                        $this->em->persist($document);
                    }

                    foreach ($scrapedData['notificationDocuments'] as $docUrl) {
                        $document = new Document();
                        $document->setTender($tenderEntity);
                        $document->setType('notificationDocument');
                        $document->setUrl($docUrl);
                        $tenderEntity->addTenderDocument($document);
                        $this->em->persist($document);
                    }

                }
            }

            $this->em->flush();
            gc_collect_cycles();
            $this->em->clear();
            dump($p);
        }

        return Command::SUCCESS;
    }

    private function scrapEntry(Crawler $tenderEntry): array
    {
        $tender = [
            'name' => null,
            'agency' => null,
            'publicationDate' => null,
            'openDate' => null,
            'closeDate' => null,
            'referenceNumber' => null,
            'hasLot' => null,
            'canceled' => null,
            'suspended' => null,
            'type' => null,
            'notificationNumber' => null,
            'cpvCode' => null,
            'tenderDocuments' => [],
            'notificationDocuments' => [],
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

            $modal = $this->getModal($firstRow, $this->modalsCrawler);
            $tender['type'] = $this->getType($modal);
            $tender['notificationNumber'] = $this->getNotificationNr($modal);
            $tender['cpvCode'] = $this->getCpvCode($modal);
            $tender['publicationDate'] = $this->getPublicationDate($modal);
            $tender['tenderDocuments'] = $this->getTenderDocuments($modal, $tender['notificationNumber']);
            $tender['notificationDocuments'] = $this->getNotificationDocuments($modal, $tender['notificationNumber']);
        }

        if ($secondRow) {
            $tender['agency'] = $this->getTenderAgency($secondRow);
            $tender['openDate'] = $this->getOpenDate($secondRow);
            $tender['closeDate'] = $this->getCloseDate($secondRow);
            $tender['referenceNumber'] = $this->getReferenceNumber($secondRow);
            $tender['hasLot'] = $this->hasLot($secondRow);
            $tender['canceled'] = $this->isCanceled($secondRow);
            $tender['suspended'] = $this->isSuspended($secondRow);
        }

        return $tender;

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
        return strcasecmp(trim($hasLot), 'po') == 0 ? true : false;
    }

    private function isCanceled(Crawler $row): bool
    {
        $hasLot = trim($row->filter('ul > li:nth-child(11) > span > span > i')->text());
        return strcasecmp(trim($hasLot), 'po') == 0;
    }

    private function isSuspended(Crawler $row): bool
    {
        $suspended = trim($row->filter('ul > li:nth-child(13) > span > span > i')->text());
        return strcasecmp(trim($suspended), 'po') == 0;
    }

    private function getModal(Crawler $row, Crawler $modalsCrawler): ?Crawler
    {
        $modalId = trim($row->filter('div.more-info a')->attr('href'));
        $modal = $modalsCrawler->filter($modalId);
        return $modal;
    }

    private function getType(Crawler $modal): ?string
    {
        $type = trim($modal->filter('ul > li:nth-child(3) > div > div.col-lg-8.col-md-8.col-sm-12.col-xs-12 > small')->text());
        return $type;
    }

    private function getNotificationNr(Crawler $modal): ?string
    {
        return trim($modal->filter('ul > li:nth-child(5) > div > div.col-lg-8.col-md-8.col-sm-12.col-xs-12 > small')->text());
    }

    private function getCpvCode(Crawler $modal): ?string
    {
        $cvp = $modal->filter('ul > li:nth-child(10) > div > div.col-lg-8.col-md-8.col-sm-12.col-xs-12 > small');

        if ($cvp->count()) {
            return $cvp->text();
        }

        return null;
    }

    private function getPublicationDate(Crawler $modal): ?\DateTime
    {
        $date = trim($modal->filter('ul > li:nth-child(6) > div > div.col-lg-8.col-md-8.col-sm-12.col-xs-12 > small:nth-child(1)')->text());
        $time = trim($modal->filter('ul > li:nth-child(6) > div > div.col-lg-8.col-md-8.col-sm-12.col-xs-12 > small:nth-child(3)')->text());
        return new \DateTime("$date $time");
    }

    private function getTenderDocuments(Crawler $modal, string $notificationNumber): array
    {
        $documents = [];

        $divId = '#tenderdocuments_' . str_replace('/', '-', $notificationNumber);
        $links = $modal->filter($divId . " a")->links();
        foreach ($links as $document) {
            $link = $document->getUri();
            $documents[] = $link;
        }
        return $documents;
    }

    private function getNotificationDocuments(Crawler $modal, string $notificationNumber): array
    {
        $documents = [];

        $divId = '#noticedocuments_' . str_replace('/', '-', $notificationNumber);
        $links = $modal->filter($divId . " a")->links();
        foreach ($links as $document) {
            $link = $document->getUri();
            $documents[] = $link;
        }
        return $documents;
    }


}
