<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTest extends WebTestCase
{
    public function testGetWeatherData()
    {
        $client = static::createClient();

        $queryParameters = [
            'access_key' => '8f2192e08d96112470ef4fb23e7cbf31', // Remplacez par votre clé API
            'query' => 'Paris',
            'historical_date' => '2023-04-21; 2023-04-22', // Exemple de paramètre
            'hourly' => 1,
            'interval' => 24,
        ];

        // Envoie une requête GET à l'API Météo
        $client->request('GET', 'https://api.weatherstack.com/historical', $queryParameters); // Remplacez par l'URL de l'endpoint API de l'application

        // Vérifie le code de statut de la réponse (par exemple, 200 pour succès)
        $this->assertSame(200, $client->getResponse()->getStatusCode(), 'Le code de statut de la réponse est incorrect.');

        // Vérifie que la réponse est au format JSON
        $this->assertJson($client->getResponse()->getContent(), 'La réponse n\'est pas au format JSON.');

        // Vérifie que la réponse contient les données météo attendues
        $responseDatas = json_decode($client->getResponse()->getContent(), true);

        foreach($responseDatas as $responseData) {
            $this->assertArrayHasKey('historical', $responseData, 'La réponse ne contient pas la clé "historical".');
            $this->assertIsArray($responseData['historical'], 'La clé "historical" ne contient pas un tableau.');

            foreach($responseData['historical'] as $historical_date) {
                $this->assertArrayHasKey('precip', $historical_date['hourly'][0], 'La réponse ne contient pas la clé "precip" dans les données horaires.');
                $this->assertIsNumeric($historical_date['hourly'][0]['precip'], 'La valeur de la clé "precip" n\'est pas numérique.');
            }
        }
    }
}

//php bin/phpunit tests/ApiTest.php --filter ApiTest::testGetWeatherData