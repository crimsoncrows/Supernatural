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

    // PERFORM SKILL
    private const SKILL_FEROCITY_GAIN_MIN = 5;
    private const SKILL_FEROCITY_GAIN_MAX = 15;

    // EXPEDITION / GO OUTSIDE / LURK
    private const EXPEDITION_HEALTH_COST = 0.5;
    private const GO_OUTSIDE_HEALTH_COST = 0.5;
    private const LURK_HEALTH_COST = 0.5;

    // MONITOR MOON STATUS
    private const MOON_FEROCITY_GAIN_MIN = 2;
    private const MOON_FEROCITY_GAIN_MAX = 8;

    // CHASE
    private const CHASE_HEALTH_COST_MIN = 3;
    private const CHASE_HEALTH_COST_MAX = 8;
    private const CHASE_CATCH_CHANCE_PER_100 = 20;
    private const CHASE_ESCAPE_DAMAGE_MIN = 5;
    private const CHASE_ESCAPE_DAMAGE_MAX = 15;

    // POUNCE
    private const POUNCE_HEALTH_COST_MIN = 5;
    private const POUNCE_HEALTH_COST_MAX = 12;
    private const POUNCE_CATCH_CHANCE_PER_100 = 15;
    private const POUNCE_ESCAPE_DAMAGE_MIN = 8;
    private const POUNCE_ESCAPE_DAMAGE_MAX = 20;

    // ATTACK
    private const ATTACK_POWER_MIN = 8;
    private const ATTACK_POWER_MAX = 18;
    private const ATTACK_FEROCITY_GAIN_MIN = 2;
    private const ATTACK_FEROCITY_GAIN_MAX = 5;
    private const ATTACK_HEALTH_COST_MIN = 1;
    private const ATTACK_HEALTH_COST_MAX = 3;

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
        $this->setFerocity($this->getFerocity() + rand(self::SKILL_FEROCITY_GAIN_MIN, self::SKILL_FEROCITY_GAIN_MAX));
        return $this->getName() . " transforms and ferocity heightens to " . $this->getFerocity() . "!";
    }

    public function goForExpedition()
    {
        $this->setHumanEncounters($this->getHumanEncounters() + 1);
        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::EXPEDITION_HEALTH_COST));
        return $this->getName() . " prowls the night hunting prey.";
    }

    // =============================================
    // 7. NOCTURNAL INTERFACE METHODS
    // =============================================

    public function goOutside()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::GO_OUTSIDE_HEALTH_COST));
        return $this->getName() . " steps out into the moonlit forest.";
    }

    public function monitorMoonStatus()
    {
        $this->setFerocity($this->getFerocity() + rand(self::MOON_FEROCITY_GAIN_MIN, self::MOON_FEROCITY_GAIN_MAX));
        return $this->getName() . " senses the full moon's power. Ferocity increased to " . $this->getFerocity() . "!";
    }

    public function lurkInTheDark()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::LURK_HEALTH_COST));
        return $this->getName() . " lurks silently in the shadows.";
    }

    // =============================================
    // 8. WEREWOLF UNIQUE ABILITIES
    // =============================================

    public function chase($prey = null)
    {
        $healthCost = rand(self::CHASE_HEALTH_COST_MIN, self::CHASE_HEALTH_COST_MAX);
        $this->setHealthLevel(max(0, $this->getHealthLevel() - $healthCost));

        $log = $this->getName() . " chases after " . ($prey ? $prey->getPreyName() : "the prey") . "! (Cost: " . $healthCost . " health)";

        if (rand(1, 100) <= self::CHASE_CATCH_CHANCE_PER_100) {
            if ($prey !== null) {
                $prey->setPreyHealth(0);
                $_SESSION['prey_health'] = 0;
                $log .= " CAUGHT! " . $prey->getPreyName() . " has been caught!";
                $log .= " " . $this->getName() . " is victorious!";
            }
        } else {
            $escapeDamage = rand(self::CHASE_ESCAPE_DAMAGE_MIN, self::CHASE_ESCAPE_DAMAGE_MAX);
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
        $healthCost = rand(self::POUNCE_HEALTH_COST_MIN, self::POUNCE_HEALTH_COST_MAX);
        $this->setHealthLevel(max(0, $this->getHealthLevel() - $healthCost));

        $log = $this->getName() . " pounces on " . ($prey ? $prey->getPreyName() : "the prey") . "! (Cost: " . $healthCost . " health)";

        if (rand(1, 100) <= self::POUNCE_CATCH_CHANCE_PER_100) {
            if ($prey !== null) {
                $prey->setPreyHealth(0);
                $_SESSION['prey_health'] = 0;
                $log .= " CAUGHT! " . $prey->getPreyName() . " has been caught!";
                $log .= " " . $this->getName() . " is victorious!";
            }
        } else {
            $escapeDamage = rand(self::POUNCE_ESCAPE_DAMAGE_MIN, self::POUNCE_ESCAPE_DAMAGE_MAX);
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
            $power = rand(self::ATTACK_POWER_MIN, self::ATTACK_POWER_MAX);
            $prey->setPreyHealth(max(0, $prey->getPreyHealth() - $power));
            $this->setFerocity($this->getFerocity() + rand(self::ATTACK_FEROCITY_GAIN_MIN, self::ATTACK_FEROCITY_GAIN_MAX));
            $this->setHealthLevel(max(0, $this->getHealthLevel() - rand(self::ATTACK_HEALTH_COST_MIN, self::ATTACK_HEALTH_COST_MAX)));
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
    'Greyback', 500, 45, 'Alpha Howl', 92.0, 100.0,
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