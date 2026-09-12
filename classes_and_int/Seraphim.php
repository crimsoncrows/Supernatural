<?php

//REQUIRE DEPENDENCIES
require_once 'Angel.php';
require_once 'FlyandChange.php';
require_once 'Logger.php';

//CLASS DECLARATION
class Seraphim extends Angel
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

    // FLY — SUN & FALL RISK
    private const FLY_SUN_DEATH_MIN_COUNT = 3;
    private const FLY_SUN_DEATH_CHANCE_PER_100 = 30;
    private const FLY_LOW_HEALTH_THRESHOLD = 10;
    private const FLY_FALL_DEATH_CHANCE_PER_100 = 10;

    // REVEAL GLORY
    private const GLORY_FAINT_CHANCE_PER_100 = 30;
    private const GLORY_DIVINITY_COST_MIN = 3;
    private const GLORY_DIVINITY_COST_MAX = 8;

    // HEAL
    private const HEAL_AMOUNT_MIN = 10;
    private const HEAL_AMOUNT_MAX = 20;
    private const HEAL_HEALTH_CAP = 100;
    private const HEAL_DIVINITY_COST_MIN = 2;
    private const HEAL_DIVINITY_COST_MAX = 5;

    // BLESS
    private const BLESS_SKILL_BOOST_MIN = 5;
    private const BLESS_SKILL_BOOST_MAX = 15;
    private const BLESS_DIVINITY_COST_MIN = 1;
    private const BLESS_DIVINITY_COST_MAX = 3;

    // PROPERTIES
    private int $wingsCount;
    private int $flyCount;

    //CONSTRUCTORS
    public function __construct(
        $name = "",
        $age = 0,
        $humanEncounters = 0,
        $ability = "",
        $skillLevel = 0.0,
        $healthLevel = 0.0,
        $divinityLevel = 50.0,
        $wingsCount = 6
    )
    {
        parent::__construct($name, $age, $humanEncounters, $ability, $skillLevel, $healthLevel, $divinityLevel);
        $this->wingsCount = $wingsCount;
        $this->flyCount = 0;
    }


    // ATTRIBUTE ACCESS (GETTERS & SETTERS)

    public function getWingsCount()
    {
        return $this->wingsCount;
    }

    public function setWingsCount($wingsCount)
    {
        $this->wingsCount = max(0, $wingsCount);
    }

    public function getFlyCount()
    {
        return $this->flyCount;
    }

    public function setFlyCount($flyCount)
    {
        $this->flyCount = max(0, $flyCount);
    }

    // OVERRIDDEN ABSTRACT METHODS (from Supernatural)

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
        //adds encounters and loses a little energy from guarding
        $this->setHumanEncounters($this->getHumanEncounters() + 1);
        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::EXPEDITION_HEALTH_COST));
        return $this->getName() . " descends from the heavens to watch over mortals.";
    }

    // FLYANDCHANGE INTERFACE METHODS
    public function fly()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::FLY_HEALTH_COST));
        $this->setFlyCount($this->getFlyCount() + 1);

        $log = $this->getName() . " soars with " . $this->getWingsCount() . " majestic wings!";

        // Flying too much - chance to fly too close to the sun (KILLS the Seraphim)
        if ($this->getFlyCount() >= self::FLY_SUN_DEATH_MIN_COUNT && rand(1, 100) <= self::FLY_SUN_DEATH_CHANCE_PER_100) {
            $this->setHealthLevel(0);
            $log .= " Oh No! " . $this->getName() . " flew too close to the sun and perished!";
            $this->setFlyCount(0);
        }

        // potential to die and fall due to low health
        if ($this->getHealthLevel() <= self::FLY_LOW_HEALTH_THRESHOLD && rand(1, 100) <= self::FLY_FALL_DEATH_CHANCE_PER_100) {
            $this->setHealthLevel(0);
            $log .= " Ouch... " . $this->getName() . " flew but fell due to extreme health weakness!";
            $this->setFlyCount(0);
        }
        return $log;
    }

    public function spawn()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::SPAWN_HEALTH_COST));
        return $this->getName() . " summons a celestial light to guide the lost.";
    }

    public function teleport()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::TELEPORT_HEALTH_COST));
        return $this->getName() . " teleports between the heavenly realms.";
    }

    // =============================================
    // 8. SERAPHIM UNIQUE ABILITIES
    // =============================================

    public function revealGlory($prey = null)
    {
        $log = $this->getName() . " reveals divine glory with " . $this->getWingsCount() . " wings!";

        if ($prey !== null) {
            // 30% chance prey faints (health = 0)
            if (rand(1, 100) <= self::GLORY_FAINT_CHANCE_PER_100) {
                $prey->setPreyHealth(0);
                $_SESSION['prey_health'] = 0;
                $log .= " " . $prey->getPreyName() . " is overwhelmed and faints! Health reduced to 0!";
                $log .= " " . $this->getName() . " is victorious!";
            } else {
                // Otherwise prey just watches
                $log .= " " . $prey->getPreyName() . " watches in awe, unable to act.";
            }
        } else {
            $log .= " The light shines across the realm.";
        }

        // Revealing glory costs divinity
        $this->setDivinityLevel(max(0, $this->getDivinityLevel() - rand(self::GLORY_DIVINITY_COST_MIN, self::GLORY_DIVINITY_COST_MAX)));

        return $log;
    }

    // =============================================
    // 9. OVERRIDDEN ANGEL METHODS
    // =============================================

    public function heal()
    {
        $healAmount = rand(self::HEAL_AMOUNT_MIN, self::HEAL_AMOUNT_MAX);
        $newHealth = $this->getHealthLevel() + $healAmount;
        $this->setHealthLevel(min(self::HEAL_HEALTH_CAP, $newHealth));
        $this->setDivinityLevel(max(0, $this->getDivinityLevel() - rand(self::HEAL_DIVINITY_COST_MIN, self::HEAL_DIVINITY_COST_MAX)));
        return $this->getName() . " heals for " . $healAmount . " health!";
    }

    public function bless()
    {
        $this->setSkillLevel($this->getSkillLevel() + rand(self::BLESS_SKILL_BOOST_MIN, self::BLESS_SKILL_BOOST_MAX));
        $this->setDivinityLevel(max(0, $this->getDivinityLevel() - rand(self::BLESS_DIVINITY_COST_MIN, self::BLESS_DIVINITY_COST_MAX)));
        return $this->getName() . " blesses the battlefield! Skill increased.";
    }
}

// =============================================
// 10. SERAPHIM PRESETS / INSTANCES
// =============================================

// ---- YOUNG SERAPHIM ----
// A novice seraphim with few wings
$youngSeraphim = new Seraphim(
    'Ariel', 500, 10, 'Holy Light', 40.0, 60.0,
    35.0, 4
);

// ---- DEFAULT SERAPHIM ----
// A standard seraphim with moderate power
$defaultSeraphim = new Seraphim(
    'Seraphiel', 1500, 30, 'Divine Judgement', 80.0, 100.0,
    65.0, 6
);

// ---- ANCIENT SERAPHIM ----
// A legendary seraphim with immense power
$ancientSeraphim = new Seraphim(
    'Metatron', 5000, 60, 'Eternal Glory', 95.0, 100.0,
    95.0, 12
);

// =============================================
// 11. SERAPHIM COLLECTION
// =============================================

$seraphimCollection = [
    $youngSeraphim,
    $defaultSeraphim,
    $ancientSeraphim
    // Add future seraphims here
];
?>