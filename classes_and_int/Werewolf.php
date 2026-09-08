<?php

// =============================================
// 1. REQUIRE DEPENDENCIES
// =============================================

require_once 'Supernatural.php';
require_once 'Nocturnal.php';
require_once 'Logger.php';

// =============================================
// 2. CLASS DECLARATION
// =============================================

class Werewolf extends Supernatural implements Nocturnal
{
    use Logger;

    // =============================================
    // 3. PROPERTIES
    // =============================================

    private float $ferocity;

    // =============================================
    // 4. CONSTRUCTORS
    // =============================================

    public function __construct(
        $name = "",
        $age = 0,
        $humanEncounters = 0,
        $ability = "",
        $skillLevel = 0.0,
        $healthLevel = 0.0,
        $ferocity = 50.0
    )
    {
        parent::__construct($name, $age, $humanEncounters, $ability, $skillLevel, $healthLevel);
        $this->ferocity = $ferocity;
    }

    // =============================================
    // 5. ATTRIBUTE ACCESS (GETTERS & SETTERS)
    // =============================================

    public function getFerocity()
    {
        return $this->ferocity;
    }

    public function setFerocity($ferocity)
    {
        $this->ferocity = max(0, $ferocity);
    }

    // =============================================
    // 6. OVERRIDDEN ABSTRACT METHODS (from Supernatural)
    // =============================================

    public function performSkill()
    {
        $this->setFerocity($this->getFerocity() + rand(5, 15));
        return $this->getName() . " transforms and ferocity heightens to " . $this->getFerocity() . "!";
    }

    public function goForExpedition()
    {
        $this->setHumanEncounters($this->getHumanEncounters() + 1);
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 0.5));
        return $this->getName() . " prowls the night hunting prey.";
    }

    // =============================================
    // 7. NOCTURNAL INTERFACE METHODS
    // =============================================

    public function goOutside()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 0.5));
        return $this->getName() . " steps out into the moonlit forest.";
    }

    public function monitorMoonStatus()
    {
        $this->setFerocity($this->getFerocity() + rand(2, 8));
        return $this->getName() . " senses the full moon's power. Ferocity increased to " . $this->getFerocity() . "!";
    }

    public function lurkInTheDark()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 0.5));
        return $this->getName() . " lurks silently in the shadows.";
    }

    // =============================================
    // 8. WEREWOLF UNIQUE ABILITIES
    // =============================================

    public function chase($prey = null)
    {
        $healthCost = rand(3, 8);
        $this->setHealthLevel(max(0, $this->getHealthLevel() - $healthCost));

        $log = $this->getName() . " chases after " . ($prey ? $prey->getPreyName() : "the prey") . "! (Cost: " . $healthCost . " health)";

        if (rand(1, 100) <= 20) {
            if ($prey !== null) {
                $prey->setPreyHealth(0);
                $_SESSION['prey_health'] = 0;
                $log .= " CAUGHT! " . $prey->getPreyName() . " has been caught!";
                $log .= " " . $this->getName() . " is victorious!";
            }
        } else {
            $escapeDamage = rand(5, 15);
            $this->setHealthLevel(max(0, $this->getHealthLevel() - $escapeDamage));
            $log .= " The prey escaped! " . $this->getName() . " takes " . $escapeDamage . " damage from exhaustion!";

            if ($this->getHealthLevel() <= 0) {
                $log .= " " . $this->getName() . " has perished from exhaustion!";
            }
        }

        return $log;
    }

    public function pounce($prey = null)
    {
        $healthCost = rand(5, 12);
        $this->setHealthLevel(max(0, $this->getHealthLevel() - $healthCost));

        $log = $this->getName() . " pounces on " . ($prey ? $prey->getPreyName() : "the prey") . "! (Cost: " . $healthCost . " health)";

        if (rand(1, 100) <= 15) {
            if ($prey !== null) {
                $prey->setPreyHealth(0);
                $_SESSION['prey_health'] = 0;
                $log .= " CAUGHT! " . $prey->getPreyName() . " has been caught!";
                $log .= " " . $this->getName() . " is victorious!";
            }
        } else {
            $escapeDamage = rand(8, 20);
            $this->setHealthLevel(max(0, $this->getHealthLevel() - $escapeDamage));
            $log .= " The prey escaped! " . $this->getName() . " takes " . $escapeDamage . " damage from exhaustion!";

            if ($this->getHealthLevel() <= 0) {
                $log .= " " . $this->getName() . " has perished from exhaustion!";
            }
        }

        return $log;
    }

    // =============================================
    // 9. ATTACK METHOD
    // =============================================

    public function attack($prey)
    {
        if ($prey !== null) {
            $power = rand(8, 18);
            $prey->setPreyHealth(max(0, $prey->getPreyHealth() - $power));
            $this->setFerocity($this->getFerocity() + rand(2, 5));
            $this->setHealthLevel(max(0, $this->getHealthLevel() - rand(1, 3)));
            $logMessage = $this->getName() . " attacks with claws, " . $power . " damage!";
            return $logMessage;
        }
        return $this->getName() . " has no prey to attack.";
    }
}

// =============================================
// 10. WEREWOLF PRESETS / INSTANCES
// =============================================

$youngWerewolf = new Werewolf(
    'Fenrir', 25, 3, 'Claw Strike', 35.0, 55.0,
    30.0
);

$defaultWerewolf = new Werewolf(
    'Lupus', 150, 20, 'Moon Rage', 75.0, 95.0,
    65.0
);

$ancientWerewolf = new Werewolf(
    'Greyback', 500, 45, 'Alpha Howl', 92.0, 130.0,
    95.0
);

// =============================================
// 11. WEREWOLF COLLECTION
// =============================================

$werewolfCollection = [
    $youngWerewolf,
    $defaultWerewolf,
    $ancientWerewolf
];
?>