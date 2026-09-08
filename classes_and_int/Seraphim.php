<?php

// =============================================
// 1. REQUIRE DEPENDENCIES
// =============================================

require_once 'Angel.php';
require_once 'FlyandChange.php';
require_once 'Logger.php';

// =============================================
// 2. CLASS DECLARATION
// =============================================

class Seraphim extends Angel  // REMOVED: implements FlyandChange (already inherited from Angel)
{
    use Logger;

    // =============================================
    // 3. PROPERTIES
    // =============================================

    private int $wingsCount;
    private int $flyCount;

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
        $divinityLevel = 50.0,
        $wingsCount = 6
    )
    {
        parent::__construct($name, $age, $humanEncounters, $ability, $skillLevel, $healthLevel, $divinityLevel);
        $this->wingsCount = $wingsCount;
        $this->flyCount = 0;
    }

    // =============================================
    // 5. ATTRIBUTE ACCESS (GETTERS & SETTERS)
    // =============================================

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

    // =============================================
    // 6. OVERRIDDEN ABSTRACT METHODS (from Supernatural)
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
        $this->setHumanEncounters($this->getHumanEncounters() + 1);
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 0.5));
        return $this->getName() . " descends from the heavens to watch over mortals.";
    }

    // =============================================
    // 7. FLYANDCHANGE INTERFACE METHODS
    // =============================================

    public function fly()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 1));
        $this->setFlyCount($this->getFlyCount() + 1);

        $log = $this->getName() . " soars with " . $this->getWingsCount() . " majestic wings!";

        // Flying too much - chance to fly too close to the sun (KILLS the Seraphim)
        if ($this->getFlyCount() >= 3 && rand(1, 100) <= 30) {
            $this->setHealthLevel(0);
            $log .= " " . $this->getName() . " flew too close to the sun and perished!";
            $this->setFlyCount(0);
        }

        return $log;
    }

    public function spawn()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 0.75));
        return $this->getName() . " summons a celestial light to guide the lost.";
    }

    public function teleport()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 1));
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
            if (rand(1, 100) <= 30) {
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
        $this->setDivinityLevel(max(0, $this->getDivinityLevel() - rand(3, 8)));

        return $log;
    }

    // =============================================
    // 9. OVERRIDDEN ANGEL METHODS
    // =============================================

    public function heal()
    {
        $healAmount = rand(10, 20);
        $newHealth = $this->getHealthLevel() + $healAmount;
        $this->setHealthLevel(min(100, $newHealth));
        $this->setDivinityLevel(max(0, $this->getDivinityLevel() - rand(2, 5)));
        return $this->getName() . " heals for " . $healAmount . " health!";
    }

    public function bless()
    {
        $this->setSkillLevel($this->getSkillLevel() + rand(5, 15));
        $this->setDivinityLevel(max(0, $this->getDivinityLevel() - rand(1, 3)));
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
    'Metatron', 5000, 60, 'Eternal Glory', 95.0, 140.0,
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