<?php

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationTest extends KernelTestCase {

    
    public function testInvalidData() {

        $kernel = self::bootKernel();
        $container = static::getContainer();

        $validator = $container->get(ValidatorInterface::class);

        //pour récupérer un repository
        $repository = $container->get(UserRepository::class);


        //Vérification de l'email
        $user = new User();
        $user->setEmail('maud.trisson@gmail');

        $errors = $validator->validate($user);

        $errorMessages = [];
        foreach ($errors as $error) {
            // Récupérer le message d'erreur de l'objet ConstraintViolation
            $errorMessages[] = $error->getMessage();
        }
        $this->assertContains('adresse mail non valide.', $errorMessages, 'email - non valide');



        $user->setEmail('maud.trisson@gmail.com');

        $errors = $validator->validate($user);

        $errorMessages = [];
        foreach ($errors as $error) {
            // Récupérer le message d'erreur de l'objet ConstraintViolation
            $errorMessages[] = $error->getMessage();
        }

        $this->assertContains('There is already an account with this email', $errorMessages, 'email - déjà utilisé');
        

        $user->setEmail('');

        $errors = $validator->validate($user);

        $errorMessages = [];
        foreach ($errors as $error) {
            // Récupérer le message d'erreur de l'objet ConstraintViolation
            $errorMessages[] = $error->getMessage();
        }

        $this->assertContains('L\'email ne doit pas être vide', $errorMessages, 'email - vide');


        //Vérification du mot de passe 

        $user = new User();
        $user->setPassword('motde');

        $errors = $validator->validate($user);

        $errorMessages = [];
        foreach ($errors as $error) {
            // Récupérer le message d'erreur de l'objet ConstraintViolation
            $errorMessages[] = $error->getMessage();
        }

        $this->assertContains('Le mot de passe doit contenir au moins 6 caractères.', $errorMessages, 'password - pas assez de caractère');
        $this->assertContains('Le mot de passe doit contenir au moins une lettre, au moins un chiffre et au moins un caractère spécial.', $errorMessages, 'password - pas de chiffre, lettre ou caractère spécial');


        $user->setPassword('');

        $errors = $validator->validate($user);

        $errorMessages = [];
        foreach ($errors as $error) {
            // Récupérer le message d'erreur de l'objet ConstraintViolation
            $errorMessages[] = $error->getMessage();
        }

        $this->assertContains('Le password ne doit pas être vide', $errorMessages, 'password - vide');

        //php bin/phpunit tests/RegistrationTest.php --filter RegistrationTest::testInvalidData
    }



    public function testValidData() {
        
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $validator = $container->get(ValidatorInterface::class);

        //pour récupérer un repository
        $repository = $container->get(UserRepository::class);

        $user = new User();

        $user->setEmail('jerome.giraudeau@gmail.com');

        $errors = $validator->validate($user);

        $errorMessages = [];
        foreach ($errors as $error) {
            // Récupérer le message d'erreur de l'objet ConstraintViolation
            $errorMessages[] = $error->getMessage();
        }

        $this->assertNotContains('There is already an account with this email', $errorMessages, 'email - Non déjà utilisé');
        $this->assertNotContains('adresse mail non valide.', $errorMessages, 'email - valide');
        $this->assertNotContains('L\'email ne doit pas être vide', $errorMessages, 'email - non vide');



        $user = new User();
        $user->setPassword('motdepasse42@');

        $errors = $validator->validate($user);

        $errorMessages = [];
        foreach ($errors as $error) {
            // Récupérer le message d'erreur de l'objet ConstraintViolation
            $errorMessages[] = $error->getMessage();
        }

        $this->assertNotContains('Le mot de passe doit contenir au moins 6 caractères.', $errorMessages, 'password - suffisement de caractère');
        $this->assertNotContains('Le mot de passe doit contenir au moins une lettre, au moins un chiffre et au moins un caractère spécial.', $errorMessages, 'password - contient chiffre, lettre et caractère spécial');
        $this->assertNotContains('Le password ne doit pas être vide', $errorMessages, 'password - non vide');
    
        //php bin/phpunit tests/RegistrationTest.php --filter RegistrationTest::testValidData
    }


        
}

//php bin/phpunit tests/RegistrationTest.php