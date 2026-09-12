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

    // PERFORM SKILL THRESHOLDS & BOOSTS
    private const SKILL_LOW_HEALTH_THRESHOLD = 20;
    private const SKILL_MODERATE_HEALTH_THRESHOLD = 40;
    private const SKILL_BOOST_LOW_MIN = 1;
    private const SKILL_BOOST_LOW_MAX = 5;
    private const SKILL_BOOST_MODERATE_MIN = 5;
    private const SKILL_BOOST_MODERATE_MAX = 10;
    private const SKILL_BOOST_HIGH_MIN = 10;
    private const SKILL_BOOST_HIGH_MAX = 20;

    // HEALTH COSTS
    private const EXPEDITION_HEALTH_COST = 0.5;
    private const FLY_HEALTH_COST = 1;
    private const SPAWN_HEALTH_COST = 0.75;
    private const TELEPORT_HEALTH_COST = 1;
    private const MORPH_HEALTH_COST = 2;

    // MORPH FLAVOR
    private const MORPH_TYPES = ["raven", "bat", "moth", "crow", "vulture", "shadow wing", "wraith", "gargoyle", "phantom hawk", "ash phoenix", "old man", "young woman", "stray child", "traveling monk", "beggar", "nameless stranger"];

    // ATTACK
    private const ATTACK_POWER_MIN = 5;
    private const ATTACK_POWER_MAX = 20;
    private const ATTACK_CORRUPTION_GAIN_MIN = 3;
    private const ATTACK_CORRUPTION_GAIN_MAX = 5;

    // DRAIN SOUL
    private const SOUL_TASTE_ADJECTIVES = ["sweet", "bitter", "metallic", "scalding", "sour", "warm", "bland", "rotten", "clean", "jagged"];
    private const DRAIN_HEALTH_GAIN_MIN = 20;
    private const DRAIN_HEALTH_GAIN_MAX = 30;
    private const DRAIN_HEALTH_CAP = 100;
    private const DRAIN_CORRUPTION_GAIN_MIN = 3;
    private const DRAIN_CORRUPTION_GAIN_MAX = 10;
    private const DRAIN_PREY_DAMAGE = 100;

    // CREATE PACT
    private const PACT_SKILL_BOOST_MIN = 10;
    private const PACT_SKILL_BOOST_MAX = 25;

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
        if ($this->getHealthLevel() <= self::SKILL_LOW_HEALTH_THRESHOLD) {
            $this->setSkillLevel($this->getSkillLevel() + rand(self::SKILL_BOOST_LOW_MIN, self::SKILL_BOOST_LOW_MAX));
            return $this->getName() . " tries to perform " . $this->getAbility() .
                " with skill level " . $this->getSkillLevel() . ".";

            //moderate performance boost if moderate health level
        } elseif ($this->getHealthLevel() <= self::SKILL_MODERATE_HEALTH_THRESHOLD) {
            $this->setSkillLevel($this->getSkillLevel() + rand(self::SKILL_BOOST_MODERATE_MIN, self::SKILL_BOOST_MODERATE_MAX));
            return $this->getName() . " performs " . $this->getAbility() .
                " with skill level " . $this->getSkillLevel() . ".";

            //high performance boost if high health level
        } else {
            $this->setSkillLevel($this->getSkillLevel() + rand(self::SKILL_BOOST_HIGH_MIN, self::SKILL_BOOST_HIGH_MAX));
            return $this->getName() . " unleashes " . $this->getAbility() .
                " with skill level " . $this->getSkillLevel() . ".";
        }
    }

    public function goForExpedition()
    {
        //small health decrease after using energy to travel
        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::EXPEDITION_HEALTH_COST));
        return $this->getName() . " moves through dark realms seeking lost souls.";
    }



    // FLYANDCHANGE INTERFACE METHODS
    public function fly()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::FLY_HEALTH_COST));
        return $this->getName() . " takes flight, gliding through the shadows.";
    }

    public function spawn()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::SPAWN_HEALTH_COST));
        return $this->getName() . " spawns a dark minion to aid in its schemes.";
    }

    public function teleport()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::TELEPORT_HEALTH_COST));
        return $this->getName() . " instantly teleports to a distant location.";
    }



    // SPECIAL ABILITIES
    public function morph()
    {
        $morphedInto = self::MORPH_TYPES[array_rand(self::MORPH_TYPES)];

        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::MORPH_HEALTH_COST));
        return $this->getName() . " morphs into a ". $morphedInto.  ", causing shock.";
    }
    public function attack($prey)
    {
        if ($prey !== null) {

            //attack power is based on random number generation.
            // corruption level increases and prey loses health
            $power = rand(self::ATTACK_POWER_MIN, self::ATTACK_POWER_MAX);
            $this->setCorruptionLevel(max(0, $this->getCorruptionLevel() + rand(self::ATTACK_CORRUPTION_GAIN_MIN, self::ATTACK_CORRUPTION_GAIN_MAX)));
            $prey->setPreyHealth(max(0, $prey->getPreyHealth() - $power));
            $logMessage = $this->getName() . " attacks their target, " . $power . " damage!";
            return $logMessage;
        }
    }

    public function drainSoul($prey = null)
    {
        //Random soul taste
        $taste = self::SOUL_TASTE_ADJECTIVES[array_rand(self::SOUL_TASTE_ADJECTIVES)];

        //EAT only if prey is alive
        if ($prey !== null && $prey->getPreyHealth() > 0) {
            $logMessage = $this->getName() . " drains a human soul. It tastes " . $taste .".";
            $logMessage .= "A prey has been eaten: " . $prey->getPreyName() . ", aged " . $prey->getPreyAge() . ".";

            // Gain 20-30 health (cap at 100)
            $healthGain = rand(self::DRAIN_HEALTH_GAIN_MIN, self::DRAIN_HEALTH_GAIN_MAX);
            $newHealth = $this->getHealthLevel() + $healthGain;
            $this->setHealthLevel(min(self::DRAIN_HEALTH_CAP, $newHealth));

            //corruption level increases after eating
            $this->setCorruptionLevel(max(0, $this->getCorruptionLevel() + rand(self::DRAIN_CORRUPTION_GAIN_MIN, self::DRAIN_CORRUPTION_GAIN_MAX)));
            $prey->setPreyHealth(max(0, $prey->getPreyHealth() - self::DRAIN_PREY_DAMAGE));

            $logMessage .= " " . $this->getName() . " was satisfied and gained " . $healthGain . " health!";
            return $logMessage;

            //DONT EAT if prey is dead
        } elseif ($prey !== null && $prey->getPreyHealth() <= 0) {
            return $this->getName() . " finds " . $prey->getPreyName() . " already drained — nothing left to consume.";

            //DEFAULT message if conditions are not met (or if null)
        } else {
            $logMessage = $this->getName() . " devours a wandering soul.";

            // Gain 20-30 health (cap at 100)
            $healthGain = rand(self::DRAIN_HEALTH_GAIN_MIN, self::DRAIN_HEALTH_GAIN_MAX);
            $newHealth = $this->getHealthLevel() + $healthGain;
            $this->setHealthLevel(min(self::DRAIN_HEALTH_CAP, $newHealth));

            //corruption level increases after eating
            this->setCorruptionLevel(max(0, $this->getCorruptionLevel() + rand(self::DRAIN_CORRUPTION_GAIN_MIN, self::DRAIN_CORRUPTION_GAIN_MAX)));

            $logMessage .= " " . $this->getName() . " was satisfied and gained " . $healthGain . " health!";
            return $logMessage;
        }
    }

    public function createPact()
    {
        $logMessage = "A deal was done. " . $this->getName() . ", " . $this->getAge() . ": forms a dangerous pact with a mortal.";
        $this->setPactCount($this->getPactCount() + 1);
        $this->setSkillLevel($this->getSkillLevel() + rand(self::PACT_SKILL_BOOST_MIN, self::PACT_SKILL_BOOST_MAX));
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