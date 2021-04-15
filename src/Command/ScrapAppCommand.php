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
        $page = $crawler->filter('.list-group-item.list-group-item-result.list-header-margin .list-group-item-heading');

        foreach ($page as $item){
            dump('-----START-----');
            $n = new Crawler($item);
            $c = $n->children('.row');
            if($c->count() == 2){
                $firstRow = $c->eq(0);
                $secondRow = $c->eq(1);

                if($firstRow){
                    $firstRow = $firstRow->eq(0);
                    if($firstRow){

                        dump($firstRow->filter(":not(span)")->text());
                        dump($firstRow->filter('strong span')->text());
                        dump($firstRow->filter('.more-info')->text());
                        dump($firstRow->text());
                    }
                }
            }
            die;
            $c->each(function (Crawler $node) {

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




}
