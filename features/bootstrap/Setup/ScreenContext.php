<?php

namespace shinage\server\behat\Setup;

use App\Entity\Screen;
use App\Entity\ScreenAssociation;
use App\Entity\User;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;

class ScreenContext implements Context
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Given /^There is a screen with guid "([^"]*)"$/
     */
    public function thereIsAScreenWithGuid(string $guid)
    {
        $screen = new Screen();
        $screen->setGuid($guid);
        $screen->setName('Screen ' . $guid);
        $screen->setFirstConnect(new \DateTime());
        $screen->setLastConnect(new \DateTime('@0'));
        $screen->setLocation('Somewhere');
        $this->entityManager->persist($screen);
        $this->entityManager->flush();
    }

     // * @Given The screen :screen belongs to an arbitrary user
    /**
     * @Given /^(The screen "[^"]+") belongs to an arbitrary user$/
     */
    public function theScreenBelongsToAnArbitraryUser(Screen $screen)
    {
        $user = new User();
        $user->setName('Owner of ' . $screen->getGuid());
        $user->setEmail('default-owner@test.test');
        $user->setPassword('testpasswordunusable');
        $this->entityManager->persist($user);

        $this->entityManager->flush();

        $association = new ScreenAssociation();
        $association->setRoles(['view_screenshot', 'manage', 'schedule']);
        $association->setUser($user);
        $association->setScreen($screen);
        $this->entityManager->persist($association);
        $this->entityManager->flush();
    }
}
