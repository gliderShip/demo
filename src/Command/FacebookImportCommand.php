<?php

namespace App\Command;

use App\Entity\FacebookUser;
use App\Repository\FacebookUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\UnexpectedValueException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Psr\Log\LoggerInterface;
use League\Csv\Reader;

class FacebookImportCommand extends Command
{
    protected static $defaultName = 'app:facebook-import';
    protected static $defaultDescription = 'Import facebook user from csv leaked file';
    protected static $defaultDirectory = 'fixtures';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FacebookUserRepository
     */
    private $facebookUserRepository;

    /** @var LoggerInterface $logger */
    private $logger;

    /** @var Reader $csvReader */
    private $csvReader;


    public function __construct(EntityManagerInterface $em, FacebookUserRepository $facebookUserRepository, LoggerInterface $logger)
    {
        @ini_set("memory_limit",-1);

        $this->em = $em;
        $this->em
            ->getConnection()
            ->getConfiguration()
            ->setSQLLogger(null);
        $this->facebookUserRepository = $facebookUserRepository;
        $this->logger = $logger;

        parent::__construct();

    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('fileName', InputArgument::REQUIRED, 'facebook csv file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $csvPath = self::$defaultDirectory . DIRECTORY_SEPARATOR . $input->getArgument('fileName');
        $io->info('csvPath ->' . $csvPath);

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
            if( ++$i % 1000 == 0) {
                echo $i.PHP_EOL;
                $this->em->flush();
                gc_collect_cycles();
//                $this->em->commit();
                $this->em->clear();
            }

//            if (++$i >= 5000) {
//                break;
//            }
        }

        $this->em->flush();
        return Command::SUCCESS;
    }

    private function importUser($userRecord): FacebookUser
    {

        $user = new FacebookUser();
        $user->setCountryLeak('Albania');

        $user->setMobile($userRecord['mobile']);
        $user->setFacebookId($userRecord['facebookId']);
        $user->setFirstName(trim($userRecord['firstName'], " \t\n\r\0\x0B\'-)("));
        $user->setLastName(trim($userRecord['lastName']), " \t\n\r\0\x0B\'-)(");
        $user->setSex($this->getSex($userRecord['sex']) );

        $currentAddress = $this->getAddress($userRecord['currentAddress']);
        $hometownAddress = $this->getAddress($userRecord['hometownAddress']);

        $user->setFacebookCurrentAddress($userRecord['currentAddress']);
        $user->setCurrentDistrict($currentAddress['district']);
        $user->setCurrentCountry($currentAddress['country']);
        $user->setCurrentState($currentAddress['state']);

        $user->setFacebookHometownAddress($userRecord['hometownAddress']);
        $user->setHometownDistrict($hometownAddress['district']);
        $user->setHometownCountry($hometownAddress['country']);
        $user->setHometownState($hometownAddress['state']);

        $user->setRelationshipStatus($userRecord['relationshipStatus']);
        $user->setWorkCompany($userRecord['company']);
        $user->setDate10($userRecord['date10']);
        $user->setEmail($userRecord['email']);
        $user->setFacebookBirthDate($userRecord['birthDate']);
        if(!empty($userRecord['birthDate'])){
            $birthDate = new \DateTimeImmutable($userRecord['birthDate']);
            if($birthDate <= new \DateTimeImmutable("2011/01/01")){
                $user->setBirthDate($birthDate);
            }
        }


        return $user;

    }

    private function getAddress(?string $facebookAddress): array
    {
        $record = explode(',', $facebookAddress);
        $parts = count($record);
        $address = [
            'district' => null,
            'country' => null,
            'state' => null,
        ];

        if(empty($facebookAddress)){
            return $address;
        }

        if ($parts == 1) {
            $address['district'] = trim($record[0]);
            $address['country'] = trim($record[0]);
            $address['state'] = trim($record[0]);
            return $address;
        }
        elseif ($parts == 2) {
            $address['district'] = trim($record[0]);
            $address['country'] = trim($record[0]);
            $address['state'] = trim($record[1]);
            return $address;
        } elseif ($parts == 3) {
            $address['district'] = trim($record[0]);
            $address['country'] = trim($record[1]);
            $address['state'] = trim($record[2]);
            return $address;
        }elseif ($parts == 4) {
            $address['district'] = trim($record[1]);
            $address['country'] = trim($record[2]);
            $address['state'] = trim($record[3]);
            return $address;
        }

        $this->logger->error("Address parts Error ->:".$facebookAddress);
        throw new \UnexpectedValueException('Address parts Error!');
    }

    private function getSex(?string $sex): string{

        if(empty($sex)){
            return 'u';
        }

        $sex = trim($sex);
        if($sex == 'male'){
            return 'm';
        }
        if($sex == 'female'){
            return 'f';
        }

        return 'u';
    }
}
