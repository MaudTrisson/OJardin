<?php

namespace App\Command;

use DateTime;
use App\Entity\User;
use App\Entity\GardenUser;
use App\Service\MailService;
use App\Entity\PlantMaintenanceAction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use App\Entity\FlowerbedPlantMaintenanceAction;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ToDoListCommand extends Command {
    private $entityManager;
    private $mailService;

    public function __construct(EntityManagerInterface $entityManager, MailService $mailService) {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->mailService = $mailService;
    }

    protected function configure() {
        $this->setName('app:todolist')
            ->setDescription("Envoi un mail aux utilisateurs pour les informer des actions de maintenance à effectuer");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $doctrine = $this->entityManager;
        $userRepository = $doctrine->getRepository(User::class);
        $users = $userRepository->findAll();

        if ($users) {
            $subject = 'La TO DO liste du jour !';

            foreach ($users as $user) {
                $body = '';
                $userEmail = $user->getEmail();
                $userGardens = $doctrine->getRepository(GardenUser::class)->findBy(['user' => $user]);

                foreach ($userGardens as $userGarden) {
                    $garden = $userGarden->getGarden();
                    $gardenName = $garden->getTitle();
                    $flowerbedsPlants = $garden->getFlowerbedPlants();

                    if ($userGardens && $flowerbedsPlants->count() > 0) {
                        $body .= '<h2>' . $gardenName . '</h2>';

                        foreach ($flowerbedsPlants as $flowerbedsPlant) {
                            $plant = $flowerbedsPlant->getPlant();
                            $flowerbedPlantMaintenanceAction = $this->entityManager->getRepository(FlowerbedPlantMaintenanceAction::class)->findBy(['flowerbedPlant' => $flowerbedsPlant->getId()]);
                            $maintenanceAction = $flowerbedPlantMaintenanceAction[0]->getMaintenanceAction();

                            $waterUrgencyLevel = $this->calculateWaterUrgencyLevel($garden, $plant, $flowerbedPlantMaintenanceAction[0]);
                            if ($waterUrgencyLevel > 0) {
                                
                                $color = $this->getWaterUrgencyColor($waterUrgencyLevel);
                                $body .= '<p style="color: ' . $color . '">' . $plant->getName() . ': ' . $maintenanceAction->getName() . '</p>';
                            }
                            
                           
                        }
                    }
                }

                if ($this->mailService->sendEmail($userEmail, $subject, $body)) {
                    $output->writeln('E-mail envoyé avec succès !');
                } else {
                    $output->writeln('Erreur lors de l\'envoi de l\'e-mail.');
                }
            }

        }
    }
    

    private function calculateWaterUrgencyLevel($garden, $plant, $flowerbedPlantMaintenanceAction) {

        $plantMaintenanceAction =  $this->entityManager->getRepository(PlantMaintenanceAction::class)->findBy(['plant' => $plant]);
        
        $flowerbedPlantMaintenanceActionLastAchievementDate = $flowerbedPlantMaintenanceAction->getAchievementDate();

        $maintenanceActionFrequency = $plantMaintenanceAction[0]->getFrequencyDays();

        $plantWaterNeedPerMaintenanceAction = $plant->getRainfallRateNeed() / 365 * $maintenanceActionFrequency;

        
        $waterUrgencyLevel = 0;
        
        $today = new DateTime();

        // Ajoutez les jours relatifs à la fréquence par rapport à la date actuelle
        $datePlusFrequencyDays = clone $flowerbedPlantMaintenanceActionLastAchievementDate;
        $datePlusFrequencyDays->modify('+' . $maintenanceActionFrequency . ' days');

        //Si la date de la nouvelle action à faire est arrivée ou dépassée
        if ($datePlusFrequencyDays <= $today) {
            $rainfallSinceLastMaintenanceAction = 0;
            $dates = array();

            //on récupère les jours qui sont passés depuis la date de la nouvelle action à faire.
            while ($datePlusFrequencyDays <= $today) {
                array_push($dates, $datePlusFrequencyDays->format('Y-m-d'));
                $datePlusFrequencyDays->modify('+1 day');
            }

            // Formater les dates dans le format adapté à l'API : "date1; date2; date3; ..."
            $formattedDates = implode('; ', $dates);

            //on appel l'api météo pour récupérer la pluviométrie des jours passées depuis la nouvelle action à faire
            $apiKey = '8f2192e08d96112470ef4fb23e7cbf31';
            $baseUrl = "https://api.weatherstack.com/historical";
            $params = [
                'access_key' => $apiKey,
                'query' => $garden->getCity(),
                'historical_date' => $formattedDates,
                'hourly' => 1,
                'interval' => 24
            ];


            $queryString = http_build_query($params);
            $url = "$baseUrl?$queryString";

            $response = file_get_contents($url);

            if ($response !== false) {
                $data = json_decode($response, true);
                foreach($data['historical'] as $historical_date) {
                    $rainfallSinceLastMaintenanceAction += $historical_date['hourly'][0]['precip'];
                }

                //on attribue le niveau d'urgence d'arrosage en fonction du ratio (besoin en eau de la plante pour cette action / jours dépassés depuis l'action à faire / l'eau de pluie tombée depuis)
                if (count($data['historical']) < ($maintenanceActionFrequency * 0.3)) {
                    $waterUrgencyLevel = 1;
                } else if (count($data['historical']) < ($maintenanceActionFrequency * 0.7)) {
                    if ($plantWaterNeedPerMaintenanceAction - $rainfallSinceLastMaintenanceAction > ($plantWaterNeedPerMaintenanceAction * 0.5)) {
                        $waterUrgencyLevel = 2;
                    } else {
                        $waterUrgencyLevel = 1;
                    }
                } else if (count($data['historical']) < ($maintenanceActionFrequency)) {
                    if ($plantWaterNeedPerMaintenanceAction - $rainfallSinceLastMaintenanceAction > ($plantWaterNeedPerMaintenanceAction * 0.5)) {
                        $waterUrgencyLevel = 3;
                    } else {
                        $waterUrgencyLevel = 2;
                    }
                }
                else if (count($data['historical']) > ($maintenanceActionFrequency)) {
                    if ($plantWaterNeedPerMaintenanceAction * (count($data['historical']) / $maintenanceActionFrequency)  - $rainfallSinceLastMaintenanceAction 
                        > (($plantWaterNeedPerMaintenanceAction * (count($data['historical']) / $maintenanceActionFrequency))  * 0.5)) {
                        $waterUrgencyLevel = 4;
                    } else {
                        $waterUrgencyLevel = 3;
                    }
                }
            }  

        }

        return $waterUrgencyLevel;
    
    }

    private function getWaterUrgencyColor($waterUrgencyLevel) {
        switch ($waterUrgencyLevel) {
            case 1:
                $color = 'green';
                break;
            case 2:
                $color = 'yellow';
                break;
            case 3:
                $color = 'orange';
                break;
            case 4:
                $color = 'red';
                break;
        }

        return $color;
    }

}