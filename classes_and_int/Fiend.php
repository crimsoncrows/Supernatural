<?php
$fiendCollection=[];

class Fiend extends Supernatural implements FlyandChange
{
    use Logger;

    // =============================================
    // PROPERTIES
    // =============================================

    private float $corruptionLevel;
    private int $pactCount;
    private string $fearAura;

    // =============================================
    // CONSTRUCTOR
    // =============================================

    function __construct($name = "", $age = 0, $humanEncounters = 0, $ability = "", $skillLevel = 0.0, $healthLevel = 0.0,
                         $corruptionLevel = 50.0, $pactCount = 0, $fearAura = "Low")
    {
        parent::__construct($name, $age, $humanEncounters, $ability, $skillLevel, $healthLevel);
        $this->corruptionLevel = $corruptionLevel;
        $this->pactCount = $pactCount;
        $this->fearAura = $fearAura;
    }

    // =============================================
    // GETTERS
    // =============================================

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

    // =============================================
    // SETTERS
    // =============================================

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

    // =============================================
    // CORE ABILITIES
    // =============================================

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
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 0.5));
        return $this->getName() . " moves through dark realms seeking lost souls.";
    }

    // =============================================
    // FLYANDCHANGE INTERFACE METHODS
    // =============================================

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

    public function morph()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 2));
        return $this->getName() . " morphs into a terrifying form, striking fear into humans.";
    }


    // =============================================
    // SPECIAL ABILITIES
    // =============================================
    public function attack($prey)
    {
        if ($prey !== null) {
            $power = rand(5, 20);
            // REMOVED: Fiend does NOT gain health from attacking
            // $this->setHealthLevel(max(0, $this->getHealthLevel() + rand(5,10)));
            $this->setCorruptionLevel(max(0, $this->getCorruptionLevel() + rand(3, 5)));
            $prey->setPreyHealth(max(0, $prey->getPreyHealth() - $power));
            $logMessage = $this->getName() . " fights back, " . $power . " damage!";
            return $logMessage;
        }
    }

    public function eatSoul($prey = null)
    {
        if ($prey !== null && $prey->getPreyHealth() > 0) {
            $logMessage = $this->getName() . " devours a human soul. ";
            $logMessage .= "Eaten: " . $prey->getPreyName() . " age " . $prey->getPreyAge() . ".";

            // Gain 20-30 health (cap at 100)
            $healthGain = rand(20, 30);
            $newHealth = $this->getHealthLevel() + $healthGain;
            $this->setHealthLevel(min(100, $newHealth));

            $this->setCorruptionLevel(max(0, $this->getCorruptionLevel() + rand(3, 10)));
            $prey->setPreyHealth(max(0, $prey->getPreyHealth() - 100));

            $logMessage .= " " . $this->getName() . " gains " . $healthGain . " health!";
            return $logMessage;
        } elseif ($prey !== null && $prey->getPreyHealth() <= 0) {
            return $this->getName() . " finds " . $prey->getPreyName() . " already drained — nothing left to consume.";
        } else {
            $logMessage = $this->getName() . " devours a wandering soul.";

            // Gain 20-30 health (cap at 100)
            $healthGain = rand(20, 30);
            $newHealth = $this->getHealthLevel() + $healthGain;
            $this->setHealthLevel(min(100, $newHealth));

            $this->setCorruptionLevel(max(0, $this->getCorruptionLevel() + rand(3, 10)));

            $logMessage .= " " . $this->getName() . " gains " . $healthGain . " health!";
            return $logMessage;
        }
    }

    public function createPact()
    {
        $logMessage = $this->getName() . ", " . $this->getAge() . ": forms a dangerous pact with a mortal.";
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