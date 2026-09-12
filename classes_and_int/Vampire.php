<?php
// 1. REQUIRE DEPENDENCIES (parent, interface and trait)
require_once 'Supernatural.php';
require_once 'Nocturnal.php';
require_once 'Logger.php';


// 2. CLASS DECLARATION
class Vampire extends Supernatural implements Nocturnal
{
    use Logger;

    // ATTACK AND BITE
    private const LOW_BITE_DAMAGE_MIN = 1;
    private const LOW_BITE_DAMAGE_MAX = 3;
    private const NORMAL_BITE_DAMAGE_MIN = 5;
    private const NORMAL_BITE_DAMAGE_MAX = 10;
    private const BITE_FORCE_COST = 25;
    private const DRAIN_HEALTH_GAIN_MIN = 5;
    private const DRAIN_HEALTH_GAIN_MAX = 8;
    private const DRAIN_HEALTH_CAP = 100;
    private const WEAKNESS_TRIGGER_CHANCE_PER_100 = 30;

    // WEAKNESSES
    private const GARLIC_HEALTH_PENALTY = 50;

    private const GARLIC_DODGE_CHANCE = 20;
    private const GARLIC_SKILL_PENALTY = 20;
    private const SUNLIGHT_HEALTH_PENALTY = 70;
    private const SUNLIGHT_SKILL_PENALTY = 30;

    private const HOLY_WATER_HEALTH_PENALTY = 40;
    private const HOLY_WATER_SKILL_PENALTY = 25;

    //properties exclusive for vampire only
    private float $bloodDrank;
    private float $biteForce;
    private string $mood;
    private array $preyList;

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
        $bloodDrank = 0.0,
        $biteForce = 75.5
    )
    {
        parent::__construct($name, $age, $humanEncounters, $ability, $skillLevel, $healthLevel);
        $this->bloodDrank = $bloodDrank;
        $this->biteForce = max(0, $biteForce);
        $this->mood = ($bloodDrank > 0) ? "Neutral" : "Not Hungry";
        $this->preyList = [];
    }

    // =============================================
    // 5. ATTRIBUTE ACCESS (GETTERS & SETTERS)
    // =============================================

    public function getBloodDrank()
    {
        return $this->bloodDrank;
    }

    public function setBloodDrank($bloodDrank)
    {
        $this->bloodDrank = $bloodDrank;
    }

    public function getBiteForce()
    {
        return $this->biteForce;
    }

    public function setBiteForce($biteForce)
    {
        $this->biteForce = max(0, $biteForce);
    }

    public function getMood()
    {
        return $this->mood;
    }

    public function setMood($mood)
    {
        $this->mood = $mood;
    }

    public function getPreyList()
    {
        return $this->preyList;
    }

    public function setPreyList($preyList)
    {
        $this->preyList = $preyList;
    }

    // =============================================
    // 6. OVERRIDDEN ABSTRACT METHODS (from Supernatural)
    // =============================================

    public function performSkill()
    {
        return $this->getName() . " uses " . $this->getAbility() .
            " with skill level " . $this->getSkillLevel() . ".";
    }

    public function goForExpedition()
    {
        // Increment human encounters when going on expedition
        $this->setHumanEncounters($this->getHumanEncounters() + 1);

        return $this->getName() .
            " goes on a night expedition and encountered total of " .
            $this->getHumanEncounters() . " humans.";
    }

    // =============================================
    // 7. NOCTURNAL INTERFACE METHODS
    // =============================================

    public function goOutside()
    {
        // Increment human encounters when going outside
        $this->setHumanEncounters($this->getHumanEncounters() + 1);

        return $this->getName() . " silently steps into the night, sees someone and encountered " .
            $this->getHumanEncounters() . " humans in total.";
    }

    public function monitorMoonStatus()
    {
        return $this->getName() . " watches the moon carefully.";
    }

    public function lurkInTheDark()
    {
        // Increment human encounters when lurking
        $this->setHumanEncounters($this->getHumanEncounters() + 1);

        return $this->getName() . " lurks in the shadows, sees a prey and encountered " .
            $this->getHumanEncounters() . " humans in total.";
    }

    // =============================================
    // 8. VAMPIRE BEHAVIOR
    // =============================================

    public function attackAndBite($prey, $bloodToDrain = null)
    {
        $log = "";

        // Calculate damage based on bite force
        $biteForce = $this->getBiteForce();

        if ($biteForce <= 0) {
            // If bite force is 0 or less, only do 1-3 damage
            $damage = rand(self::LOW_BITE_DAMAGE_MIN, self::LOW_BITE_DAMAGE_MAX);
            $log = $this->getName() . " has no bite force left! Only manages " . $damage . " damage!\n";
        } else {
            // Normal damage based on bite force (5-10)
            $damage = rand(self::NORMAL_BITE_DAMAGE_MIN, self::NORMAL_BITE_DAMAGE_MAX);
        }

        $newPreyHealth = $prey->getPreyHealth() - $damage;
        $prey->setPreyHealth(max(0, $newPreyHealth));

        if ($bloodToDrain === null) {
            // BITE: attack without draining blood
            if ($biteForce <= 0) {
                $log .= $this->getName() . " tries to bite but has no strength left.";
            } else {
                $log .= $this->getName() . " attacks and bites " .
                    $prey->getPreyName() . ", but fails to drain blood.\n";
                $log .= "\n" . $prey->getPreyName() . " takes " . $damage . " damage!";
            }

            $this->setBiteForce($this->getBiteForce() - self::BITE_FORCE_COST);
            $log .= "\nBite force now: " . $this->getBiteForce();
        } else {
            // DRAIN BLOOD: attack with blood drain
            if ($biteForce <= 0) {
                $log .= $this->getName() . " has no bite force left! Cannot drain blood.";
            } else {
                $log = $this->getName() . " bites " .
                    $prey->getPreyName() . " and drains " . $bloodToDrain . " blood.";

                $this->setBloodDrank($this->getBloodDrank() + $bloodToDrain);
                $log .= " " . $prey->getPreyName() . " takes " . $damage . " damage!";

                // Vampire gains 5-8 health from draining blood
                $healthGain = rand(self::DRAIN_HEALTH_GAIN_MIN, self::DRAIN_HEALTH_GAIN_MAX);
                $newHealth = $this->getHealthLevel() + $healthGain;
                $this->setHealthLevel(min(self::DRAIN_HEALTH_CAP, $newHealth)); // Cap at 100
                $log .= " " . $this->getName() . " gains health from the blood!";
            }
        }

        // =============================================
        // 9. WEAKNESSES / SENSITIVITIES (triggered after attack)
        // =============================================

        // Randomly trigger a weakness (30% chance)
        if (rand(1, 100) <= self::WEAKNESS_TRIGGER_CHANCE_PER_100) {
            $weakness = rand(1, 3);

            switch ($weakness) {
                case 1:
                    $log .= "\n" . $this->garlicThrown();
                    break;
                case 2:
                    $log .= "\n" . $this->sunlightExposure();
                    break;
                case 3:
                    $log .= "\n" . $this->holyWaterSprayed();
                    break;
            }
        }

        return $log;
    }

    // =============================================
    // 10. WEAKNESSES / SENSITIVITIES
    // =============================================

    public function garlicThrown()
    {
        // Chance to dodge the garlic entirely
        if (rand(1, 100) <= self::GARLIC_DODGE_CHANCE) {
            $this->setMood("Amused");
            return $this->getName() . " swiftly dodges the thrown garlic! The vampire laughs at the prey's attempted retaliation.";
        }
        // Everything below only runs if the dodge check fails
        $newHealth = $this->getHealthLevel() - self::GARLIC_HEALTH_PENALTY;
        $this->setHealthLevel(max(0, $newHealth));

        $newSkill = $this->getSkillLevel() - self::GARLIC_SKILL_PENALTY;
        $this->setSkillLevel(max(0, $newSkill));

        $this->setMood("Annoyed");

        return $this->getName() .
            " is hit by garlic! Health: " . $this->getHealthLevel() .
            ", Skill level is reduced by " . $this->getSkillLevel() .
            " and mood changed into " . $this->getMood();
    }

    public function sunlightExposure()
    {
        $newHealth = $this->getHealthLevel() - self::SUNLIGHT_HEALTH_PENALTY;
        $this->setHealthLevel(max(0, $newHealth));

        $newSkill = $this->getSkillLevel() - self::SUNLIGHT_SKILL_PENALTY;
        $this->setSkillLevel(max(0, $newSkill));

        $this->setMood("Furious");

        return $this->getName() .
            " burns in sunlight! Health: " . $this->getHealthLevel() .
            ", Skill level is reduced by " . $this->getSkillLevel() .
            ", and mood changed into " . $this->getMood();
    }

    public function holyWaterSprayed()
    {
        $newHealth = $this->getHealthLevel() - self::HOLY_WATER_HEALTH_PENALTY;
        $this->setHealthLevel(max(0, $newHealth));

        $newSkill = $this->getSkillLevel() - self::HOLY_WATER_SKILL_PENALTY;
        $this->setSkillLevel(max(0, $newSkill));

        $this->setMood("Terrified");

        return $this->getName() .
            " is struck by holy water! Health: " . $this->getHealthLevel() .
            ", Skill level is reduced by " . $this->getSkillLevel() .
            ", and mood changed into " . $this->getMood();
    }
}

// =============================================
// 11. VAMPIRE PRESETS / INSTANCES
// =============================================

// ---- YOUNG VAMPIRE ----
$youngVampire = new Vampire(
    'Lucian', 25, 2, 'Hypnosis', 35.0, 55.0,
    5.0, 65.0
);

// ---- DEFAULT VAMPIRE ----
$defaultVampire = new Vampire(
    'Vladimir', 350, 25, 'Shadow Walk', 80.0, 95.0,
    45.0, 85.0
);

// ---- ANCIENT VAMPIRE ----
$ancientVampire = new Vampire(
    'Dracula', 800, 60, 'Blood Magic', 95.0, 100.0,
    120.0, 100.0
);

// ---- NOBLE VAMPIRE ----
$nobleVampire = new Vampire(
    'Isabella', 200, 15, 'Charm', 70.0, 85.0,
    25.0, 78.0
);

// ---- BESTIAL VAMPIRE ----
$bestialVampire = new Vampire(
    'Nosferatu', 150, 40, 'Feral Rage', 75.0, 80.0,
    35.0, 95.0
);

// =============================================
// 12. VAMPIRE COLLECTION
// =============================================

$vampireCollection = [
    $youngVampire,
    $defaultVampire,
    $ancientVampire,
    $nobleVampire,
    $bestialVampire
    // Add future vampires here
];
?>