<?php

//REQUIRE DEPENDENCIES
require_once 'Supernatural.php';
require_once 'FlyandChange.php';
require_once 'Logger.php';

//CLASS DECLARATION
class Netherlord extends Supernatural implements FlyandChange
{
    use Logger;

    // PROPERTIES
    private int $soulsCollected;
    private float $realmPower;
    private int $minionCount;

    //CONSTRUCTORS
    public function __construct(
        $name = "",
        $age = 0,
        $humanEncounters = 0,
        $ability = "",
        $skillLevel = 0.0,
        $healthLevel = 0.0,
        $soulsCollected = 0,
        $realmPower = 30.0,
        $minionCount = 0
    )
    {
        parent::__construct($name, $age, $humanEncounters, $ability, $skillLevel, $healthLevel);
        $this->soulsCollected = $soulsCollected;
        $this->realmPower = $realmPower;
        $this->minionCount = $minionCount;
    }

    // ATTRIBUTE ACCESS (GETTERS & SETTERS)

    public function getSoulsCollected()
    {
        return $this->soulsCollected;
    }

    public function setSoulsCollected($soulsCollected)
    {
        $this->soulsCollected = max(0, $soulsCollected);
    }

    public function getRealmPower()
    {
        return $this->realmPower;
    }

    public function setRealmPower($realmPower)
    {
        $this->realmPower = max(0, $realmPower);
    }

    public function getMinionCount()
    {
        return $this->minionCount;
    }

    public function setMinionCount($minionCount)
    {
        $this->minionCount = max(0, $minionCount);
    }

    // OVERRIDDEN ABSTRACT METHODS (from Supernatural)

    public function performSkill()
    {
        if ($this->getHealthLevel() <= 20) {
            $this->setSkillLevel($this->getSkillLevel() + rand(1, 5));
            return $this->getName() . " tries to perform " . $this->getAbility() .
                " with skill level " . $this->getSkillLevel() . ".";
        } elseif ($this->getHealthLevel() <= 40) {
            $this->setSkillLevel($this->getSkillLevel() + rand(5, 10));
            return $this->getName() . " performs " . $this->getAbility() .
                " with skill level " . $this->getSkillLevel() . ".";
        } else {
            $this->setSkillLevel($this->getSkillLevel() + rand(10, 20));
            return $this->getName() . " unleashes " . $this->getAbility() .
                " with skill level " . $this->getSkillLevel() . ".";
        }
    }

    public function goForExpedition()
    {
        // Increment human encounters
        $this->setHumanEncounters($this->getHumanEncounters() + 1);

        $this->setHealthLevel(max(0, $this->getHealthLevel() - 0.5));
        return $this->getName() . " roams through the nether realms, seeking new souls to command.";
    }

    // FLYANDCHANGE INTERFACE METHODS
    public function fly()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 1));

        $log = $this->getName() . " soars through the dark dimensions on shadow wings.";

        // 30% chance to affect prey mood
        if (rand(1, 100) <= 30) {
            $log .= " The prey is scared by the dark presence!";
            $_SESSION['prey_mood'] = 'scared';
        }

        return $log;
    }

    public function spawn()
    {
        // Spawn a minion
        $this->setMinionCount($this->getMinionCount() + 1);
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 0.75));

        $log = $this->getName() . " spawns a new minion from the shadows. (+1 minion)";

        // 30% chance to affect prey mood
        if (rand(1, 100) <= 30) {
            $log .= " The prey is startled by the sudden apparition!";
            $_SESSION['prey_mood'] = 'startled';
        }

        return $log;
    }

    public function teleport()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 1));

        $log = $this->getName() . " instantly teleports through the void.";

        // 30% chance to affect prey mood
        if (rand(1, 100) <= 30) {
            $log .= " The prey is mortified by the netherlord's power!";
            $_SESSION['prey_mood'] = 'mortified';

            // If mortified, prey loses health
            $damage = rand(5, 10);
            $log .= " The prey takes " . $damage . " damage from fear!";
            $_SESSION['prey_health'] = max(0, $_SESSION['prey_health'] - $damage);
        }

        return $log;
    }

    public function morph()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 2));

        $log = $this->getName() . " morphs into a terrifying form!";

        // 30% chance to make prey violent
        if (rand(1, 100) <= 30) {
            $log .= " The prey becomes VIOLENT and turns on the netherlord!";
            $_SESSION['prey_mood'] = 'violent';

            // Predator takes massive damage (death)
            $this->setHealthLevel(0);
            $log .= " " . $this->getName() . " has been killed by the violent prey!";
        } else {
            $log .= " The prey cowers in fear.";
        }

        return $log;
    }

    //NETHERLORD UNIQUE ABILITIES
    public function collectSoul($prey)
    {
        if ($prey !== null && $prey->getPreyHealth() > 0) {
            // Collect soul from prey
            $this->setSoulsCollected($this->getSoulsCollected() + 1);

            // Damage prey
            $damage = rand(15, 25);
            $prey->setPreyHealth(max(0, $prey->getPreyHealth() - $damage));

            // Increase realm power from collecting souls
            $this->setRealmPower($this->getRealmPower() + rand(2, 5));

            $log = $this->getName() . " rips the soul from " . $prey->getPreyName() . "! (+1 soul)";
            $log .= " " . $prey->getPreyName() . " takes " . $damage . " damage!";
            $log .= " Realm power increased to " . $this->getRealmPower() . ".";

            return $log;
        } elseif ($prey !== null && $prey->getPreyHealth() <= 0) {
            return $this->getName() . " finds " . $prey->getPreyName() . " already dead — no soul to collect.";
        } else {
            // No prey, collect wandering soul
            $this->setSoulsCollected($this->getSoulsCollected() + 1);
            $this->setRealmPower($this->getRealmPower() + rand(1, 3));

            return $this->getName() . " collects a wandering soul from the void. (+1 soul)";
        }
    }

    public function netherStorm($prey)
    {
        if ($prey !== null && $prey->getPreyHealth() > 0) {
            // Unleash dark magic storm
            $damage = rand(10, 20);
            $prey->setPreyHealth(max(0, $prey->getPreyHealth() - $damage));

            // Costs realm power
            $this->setRealmPower($this->getRealmPower() - rand(3, 8));

            // Small health cost
            $this->setHealthLevel(max(0, $this->getHealthLevel() - rand(2, 5)));

            $log = $this->getName() . " unleashes a nether storm upon " . $prey->getPreyName() . "!";
            $log .= " " . $prey->getPreyName() . " takes " . $damage . " damage!";
            $log .= " Realm power decreased to " . $this->getRealmPower() . ".";

            return $log;
        } elseif ($prey !== null && $prey->getPreyHealth() <= 0) {
            return $this->getName() . " unleashes a nether storm, but " . $prey->getPreyName() . " is already destroyed.";
        } else {
            // No prey, storm the void
            $this->setRealmPower($this->getRealmPower() - rand(2, 5));
            return $this->getName() . " unleashes a nether storm into the void. Realm power decreased.";
        }
    }

    //ATTACK METHOD (for FlyandChange interface)

    public function attack($prey)
    {
        if ($prey !== null) {
            // Netherlord attack with minion support
            $power = rand(10, 25);

            // Bonus damage from minions
            $minionBonus = $this->getMinionCount() * 0.5;
            $totalPower = $power + $minionBonus;

            $this->setHealthLevel(min(100, $this->getHealthLevel() + rand(3, 8)));
            $this->setRealmPower($this->getRealmPower() + rand(1, 3));

            $prey->setPreyHealth(max(0, $prey->getPreyHealth() - $totalPower));

            $logMessage = $this->getName() . " commands minions to attack! " . $totalPower . " damage!";
            $logMessage .= " (" . $power . " base + " . $minionBonus . " from minions)";

            return $logMessage;
        }
        return $this->getName() . " has no prey to attack.";
    }
}

// NETHERLORD PRESETS / INSTANCES

// ---- YOUNG NETHERLORD ----
// A fledgling netherlord with a small domain
$youngNetherlord = new Netherlord(
    'Malakar', 150, 5, 'Shadow Manipulation', 40.0, 60.0,
    10, 15.0, 2
);

// ---- DEFAULT NETHERLORD ----
// A standard netherlord with moderate power
$defaultNetherlord = new Netherlord(
    'Morrath', 600, 30, 'Soul Binding', 80.0, 100.0,
    65, 45.0, 12
);

// ---- ANCIENT NETHERLORD ----
// A legendary netherlord of immense power
$ancientNetherlord = new Netherlord(
    'Xal\'vath', 1500, 60, 'Void Mastery', 95.0, 100.0,
    250, 85.0, 45
);

// =============================================
// 11. NETHERLORD COLLECTION
// =============================================

$netherlordCollection = [
    $youngNetherlord,
    $defaultNetherlord,
    $ancientNetherlord
    // Add future netherlords here
];
?>