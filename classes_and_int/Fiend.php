<?php

//where presets are stored
$fiendCollection=[];

// REQUIRE DEPENDENCIES (parent, interface and trait)
require_once 'Supernatural.php';
require_once 'FlyandChange.php';
require_once 'Logger.php';


//class declaration
class Fiend extends Supernatural implements FlyandChange
{
    use Logger;

    //properties exclusive for fiend only

    private float $corruptionLevel;
    private int $pactCount;
    private string $fearAura;


    // CONSTRUCTOR (with parent constructor)
    function __construct($name = "Fiend", $age = 0, $humanEncounters = 0, $ability = "None", $skillLevel = 0.0, $healthLevel = 0.0,
                         $corruptionLevel = 50.0, $pactCount = 0, $fearAura = "Weak")
    {
        parent::__construct($name, $age, $humanEncounters, $ability, $skillLevel, $healthLevel);
        $this->corruptionLevel = $corruptionLevel;
        $this->pactCount = $pactCount;
        $this->fearAura = $fearAura;
    }

    // GETTERS
    public function getCorruptionLevel()
    {
        return $this->corruptionLevel;
    }

    public function getPactCount()
    {
        return $this->pactCount;
    }

    public function getFearAura()
    {
        return $this->fearAura;
    }

    // SETTERS
    public function setCorruptionLevel($corruptionLevel)
    {
        $this->corruptionLevel = max(0, $corruptionLevel);
    }

    public function setPactCount($pactCount)
    {
        $this->pactCount = max(0, $pactCount);
    }

    public function setFearAura($fearAura)
    {
        $this->fearAura = $fearAura;
    }

    // CORE ABILITIES ABSTRACT METHODS
    public function performSkill()
    {
        //weak performance boost if low health level
        if ($this->getHealthLevel() <= 20) {
            $this->setSkillLevel($this->getSkillLevel() + rand(1, 5));
            return $this->getName() . " tries to perform " . $this->getAbility() .
                " with skill level " . $this->getSkillLevel() . ".";

            //moderate performance boost if moderate health level
        } elseif ($this->getHealthLevel() <= 40) {
            $this->setSkillLevel($this->getSkillLevel() + rand(5, 10));
            return $this->getName() . " performs " . $this->getAbility() .
                " with skill level " . $this->getSkillLevel() . ".";

            //high performance boost if high health level
        } else {
            $this->setSkillLevel($this->getSkillLevel() + rand(10, 20));
            return $this->getName() . " unleashes " . $this->getAbility() .
                " with skill level " . $this->getSkillLevel() . ".";
        }
    }

    public function goForExpedition()
    {
        //small health decrease after using energy to travel
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 0.5));
        return $this->getName() . " moves through dark realms seeking lost souls.";
    }



    // FLYANDCHANGE INTERFACE METHODS
    public function fly()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 1));
        return $this->getName() . " takes flight, gliding through the shadows.";
    }

    public function spawn()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 0.75));
        return $this->getName() . " spawns a dark minion to aid in its schemes.";
    }

    public function teleport()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 1));
        return $this->getName() . " instantly teleports to a distant location.";
    }



    // SPECIAL ABILITIES
    public function morph()
    {
        $morphTypes = ["raven", "bat", "moth", "crow", "vulture", "shadow wing", "wraith", "gargoyle", "phantom hawk", "ash phoenix", "old man", "young woman", "stray child", "traveling monk", "beggar", "nameless stranger"];
        $morphedInto = $morphTypes[array_rand($morphTypes)];

        $this->setHealthLevel(max(0, $this->getHealthLevel() - 2));
        return $this->getName() . " morphs into a ". $morphedInto.  ", causing shock.";
    }
    public function attack($prey)
    {
        if ($prey !== null) {

            //attack power is based on random number generation.
            // corruption level increases and prey loses health
            $power = rand(5, 20);
            $this->setCorruptionLevel(max(0, $this->getCorruptionLevel() + rand(3, 5)));
            $prey->setPreyHealth(max(0, $prey->getPreyHealth() - $power));
            $logMessage = $this->getName() . " attacks their target, " . $power . " damage!";
            return $logMessage;
        }
    }

    public function drainSoul($prey = null)
    {
        //Random soul taste
        $soulTasteAdjectives = ["sweet", "bitter", "metallic", "scalding", "sour", "warm", "bland", "rotten", "clean", "jagged"];
        $taste = $soulTasteAdjectives[array_rand($soulTasteAdjectives)];

        //EAT only if prey is alive
        if ($prey !== null && $prey->getPreyHealth() > 0) {
            $logMessage = $this->getName() . " drains a human soul. It tastes " . $taste .".";
            $logMessage .= "A prey has been eaten: " . $prey->getPreyName() . ", aged " . $prey->getPreyAge() . ".";

                // Gain 20-30 health (cap at 100)
                $healthGain = rand(20, 30);
                $newHealth = $this->getHealthLevel() + $healthGain;
                $this->setHealthLevel(min(100, $newHealth));

                //corruption level increases after eating
                $this->setCorruptionLevel(max(0, $this->getCorruptionLevel() + rand(3, 10)));
                $prey->setPreyHealth(max(0, $prey->getPreyHealth() - 100));

                $logMessage .= " " . $this->getName() . " was satisfied and gained " . $healthGain . " health!";
                return $logMessage;

            //DONT EAT if prey is dead
        } elseif ($prey !== null && $prey->getPreyHealth() <= 0) {
                return $this->getName() . " finds " . $prey->getPreyName() . " already drained — nothing left to consume.";

                //DEFAULT message if conditions are not met (or if null)
        } else {
                $logMessage = $this->getName() . " devours a wandering soul.";

                // Gain 20-30 health (cap at 100)
                $healthGain = rand(20, 30);
                $newHealth = $this->getHealthLevel() + $healthGain;
                $this->setHealthLevel(min(100, $newHealth));

                //corruption level increases after eating
                this->setCorruptionLevel(max(0, $this->getCorruptionLevel() + rand(3, 10)));

                $logMessage .= " " . $this->getName() . " was satisfied and gained " . $healthGain . " health!";
                return $logMessage;
        }
    }

    public function createPact()
    {
        $logMessage = "A deal was done. " . $this->getName() . ", " . $this->getAge() . ": forms a dangerous pact with a mortal.";
        $this->setPactCount($this->getPactCount() + 1);
        $this->setSkillLevel($this->getSkillLevel() + rand(10, 25));
        $this->setFearAura("Medium");

        return $logMessage;
    }

}


// FIEND PRESETS / INSTANCES

// ---- YOUNG FIEND ----
// A novice fiend just beginning its dark journey
$youngFiend = new Fiend(
    'Kael', 45, 3, 'Necromancy', 40.0, 60.0,
    25.0, 2, 'Faint'
);

// ---- DEFAULT FIEND ----
// A standard fiend with moderate power
$defaultFiend = new Fiend(
    'Damien', 500, 20, 'Corruption', 85.0, 100.0,
    60.0, 12, 'Dread'
);

// ---- ANCIENT FIEND ----
// A legendary fiend of immense power
$ancientFiend = new Fiend(
    'Elisius', 1200, 47, 'Soul Rending', 98.0, 100.0,
    92.0, 38, 'Overwhelming'
);


// FIEND COLLECTION

$fiendCollection = [
    $youngFiend,
    $defaultFiend,
    $ancientFiend
    // Add future fiends here
];
?>