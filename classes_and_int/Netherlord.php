<?php

//REQUIRE DEPENDENCIES
require_once 'Supernatural.php';
require_once 'FlyandChange.php';
require_once 'Logger.php';

//CLASS DECLARATION
class Netherlord extends Supernatural implements FlyandChange
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

    // FLY
    private const FLY_SCARE_CHANCE_PER_100 = 30;

    // SPAWN
    private const SPAWN_DEATH_CHANCE_PER_1000 = 5;
    private const SPAWN_DEATH_CAUSES = [
        "a failed binding ritual",
        "insufficient dark energy",
        "premature exposure to light",
        "a fractured summoning circle",
        "the master's own instability",
        "a rival shadow's ambush",
        "the weight of its own hunger",
        "an unraveling of its form",
    ];
    private const SPAWN_FAILED_SUMMON_CHANCE_PER_100 = 2;
    private const SPAWN_FAILED_SUMMON_MIN_PENALTY = 1;
    private const SPAWN_FAILED_SUMMON_MAX_PENALTY = 5;

    // TELEPORT
    private const TELEPORT_MORTIFY_CHANCE_PER_100 = 30;
    private const TELEPORT_FEAR_DAMAGE_MIN = 5;
    private const TELEPORT_FEAR_DAMAGE_MAX = 10;

    // MORPH
    private const MORPH_VIOLENT_CHANCE_PER_100 = 20;

    // COLLECT SOUL
    private const COLLECT_SOUL_DAMAGE_MIN = 15;
    private const COLLECT_SOUL_DAMAGE_MAX = 25;
    private const COLLECT_SOUL_REALM_POWER_GAIN_MIN = 2;
    private const COLLECT_SOUL_REALM_POWER_GAIN_MAX = 5;
    private const COLLECT_SOUL_NO_PREY_REALM_POWER_GAIN_MIN = 1;
    private const COLLECT_SOUL_NO_PREY_REALM_POWER_GAIN_MAX = 3;

    // NETHER STORM
    private const STORM_DAMAGE_MIN = 10;
    private const STORM_DAMAGE_MAX = 20;
    private const STORM_REALM_POWER_COST_MIN = 3;
    private const STORM_REALM_POWER_COST_MAX = 8;
    private const STORM_HEALTH_COST_MIN = 2;
    private const STORM_HEALTH_COST_MAX = 5;
    private const STORM_NO_PREY_REALM_POWER_COST_MIN = 2;
    private const STORM_NO_PREY_REALM_POWER_COST_MAX = 5;

    // ATTACK
    private const ATTACK_POWER_MIN = 10;
    private const ATTACK_POWER_MAX = 25;
    private const ATTACK_MINION_BONUS_MULTIPLIER = 0.5;
    private const ATTACK_HEALTH_REGEN_MIN = 3;
    private const ATTACK_HEALTH_REGEN_MAX = 8;
    private const ATTACK_HEALTH_CAP = 100;
    private const ATTACK_REALM_POWER_GAIN_MIN = 1;
    private const ATTACK_REALM_POWER_GAIN_MAX = 3;

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
        if ($this->getHealthLevel() <= self::SKILL_LOW_HEALTH_THRESHOLD) {
            $this->setSkillLevel($this->getSkillLevel() + rand(self::SKILL_BOOST_LOW_MIN, self::SKILL_BOOST_LOW_MAX));
            return $this->getName() . " tries to perform " . $this->getAbility() .
                " with skill level " . $this->getSkillLevel() . ".";
        } elseif ($this->getHealthLevel() <= self::SKILL_MODERATE_HEALTH_THRESHOLD) {
            $this->setSkillLevel($this->getSkillLevel() + rand(self::SKILL_BOOST_MODERATE_MIN, self::SKILL_BOOST_MODERATE_MAX));
            return $this->getName() . " performs " . $this->getAbility() .
                " with skill level " . $this->getSkillLevel() . ".";
        } else {
            $this->setSkillLevel($this->getSkillLevel() + rand(self::SKILL_BOOST_HIGH_MIN, self::SKILL_BOOST_HIGH_MAX));
            return $this->getName() . " unleashes " . $this->getAbility() .
                " with skill level " . $this->getSkillLevel() . ".";
        }
    }

    public function goForExpedition()
    {
        // Increment human encounters
        $this->setHumanEncounters($this->getHumanEncounters() + 1);

        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::EXPEDITION_HEALTH_COST));
        return $this->getName() . " roams through the nether realms, seeking new souls to command.";
    }

    // FLYANDCHANGE INTERFACE METHODS
    public function fly()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::FLY_HEALTH_COST));

        $log = $this->getName() . " soars through the dark dimensions on shadow wings.";

        // 30% chance to affect prey mood
        if (rand(1, 100) <= self::FLY_SCARE_CHANCE_PER_100) {
            $log .= " The prey is scared by the dark presence!";
            $_SESSION['prey_mood'] = 'scared';
        }


        return $log;
    }

    public function spawn()
    {
        // Spawn a minion
        $this->setMinionCount($this->getMinionCount() + 1);
        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::SPAWN_HEALTH_COST));

        $log = $this->getName() . " spawns a new minion from the shadows. (+1 minion)";

        if (rand(1, 1000) <= self::SPAWN_DEATH_CHANCE_PER_1000) {
            $this->setMinionCount($this->getMinionCount() - 1);

            $causeOfDeath = self::SPAWN_DEATH_CAUSES[array_rand(self::SPAWN_DEATH_CAUSES)];

            $log .= " Someone has fallen! The minion unfortunately perishes due to " . $causeOfDeath . ".";
        }

        // 2% chance of a failed summon, separate from minion death
        if (rand(1, 100) <= self::SPAWN_FAILED_SUMMON_CHANCE_PER_100) {
            $failedSummonPenalty = rand(self::SPAWN_FAILED_SUMMON_MIN_PENALTY, self::SPAWN_FAILED_SUMMON_MAX_PENALTY); // random -1 to -5
            $this->setHealthLevel(max(0, $this->getHealthLevel() - $failedSummonPenalty));

            $log .= " The summoning falters, draining " . $this->getName() . " of " . $failedSummonPenalty . " health.";
        }

        return $log;
    }

    public function teleport()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::TELEPORT_HEALTH_COST));

        $log = $this->getName() . " instantly teleports through the void.";

        // 30% chance to affect prey mood
        if (rand(1, 100) <= self::TELEPORT_MORTIFY_CHANCE_PER_100) {
            $log .= " The prey is mortified by the netherlord's power!";
            $_SESSION['prey_mood'] = 'mortified';

            // If mortified, prey loses health
            $damage = rand(self::TELEPORT_FEAR_DAMAGE_MIN, self::TELEPORT_FEAR_DAMAGE_MAX);
            $log .= " The prey takes " . $damage . " damage from fear!";
            $_SESSION['prey_health'] = max(0, $_SESSION['prey_health'] - $damage);
        }

        return $log;
    }

    public function morph()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::MORPH_HEALTH_COST));

        $log = $this->getName() . " morphs into a terrifying form!";

        // 20% chance to make prey violent
        if (rand(1, 100) <= self::MORPH_VIOLENT_CHANCE_PER_100) {
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
            $damage = rand(self::COLLECT_SOUL_DAMAGE_MIN, self::COLLECT_SOUL_DAMAGE_MAX);
            $prey->setPreyHealth(max(0, $prey->getPreyHealth() - $damage));

            // Increase realm power from collecting souls
            $this->setRealmPower($this->getRealmPower() + rand(self::COLLECT_SOUL_REALM_POWER_GAIN_MIN, self::COLLECT_SOUL_REALM_POWER_GAIN_MAX));

            $log = $this->getName() . " rips a part of a soul from " . $prey->getPreyName() . "! (+1 soul)";
            $log .= " " . $prey->getPreyName() . " takes " . $damage . " damage!";
            $log .= " Realm power increased to " . $this->getRealmPower() . ".";

            return $log;
        } elseif ($prey !== null && $prey->getPreyHealth() <= 0) {
            return $this->getName() . " finds " . $prey->getPreyName() . " already dead — no soul to collect.";
        } else {
            // No prey, collect wandering soul
            $this->setSoulsCollected($this->getSoulsCollected() + 1);
            $this->setRealmPower($this->getRealmPower() + rand(self::COLLECT_SOUL_NO_PREY_REALM_POWER_GAIN_MIN, self::COLLECT_SOUL_NO_PREY_REALM_POWER_GAIN_MAX));

            return $this->getName() . " collects a wandering soul from the void. (+1 soul)";
        }
    }

    public function netherStorm($prey)
    {
        if ($prey !== null && $prey->getPreyHealth() > 0) {
            // Unleash dark magic storm
            $damage = rand(self::STORM_DAMAGE_MIN, self::STORM_DAMAGE_MAX);
            $prey->setPreyHealth(max(0, $prey->getPreyHealth() - $damage));

            // Costs realm power
            $this->setRealmPower($this->getRealmPower() - rand(self::STORM_REALM_POWER_COST_MIN, self::STORM_REALM_POWER_COST_MAX));

            // Small health cost
            $this->setHealthLevel(max(0, $this->getHealthLevel() - rand(self::STORM_HEALTH_COST_MIN, self::STORM_HEALTH_COST_MAX)));

            $log = $this->getName() . " unleashes a nether storm upon " . $prey->getPreyName() . "!";
            $log .= " " . $prey->getPreyName() . " takes " . $damage . " damage!";
            $log .= " Realm power decreased to " . $this->getRealmPower() . ".";

            return $log;
        } elseif ($prey !== null && $prey->getPreyHealth() <= 0) {
            return $this->getName() . " unleashes a nether storm, but " . $prey->getPreyName() . " is already destroyed.";
        } else {
            // No prey, storm the void
            $this->setRealmPower($this->getRealmPower() - rand(self::STORM_NO_PREY_REALM_POWER_COST_MIN, self::STORM_NO_PREY_REALM_POWER_COST_MAX));
            return $this->getName() . " unleashes a nether storm into the void. Realm power decreased.";
        }
    }

    //ATTACK METHOD (for FlyandChange interface)

    public function attack($prey)
    {
        if ($prey !== null) {
            // Netherlord attack with minion support
            $power = rand(self::ATTACK_POWER_MIN, self::ATTACK_POWER_MAX);

            // Bonus damage from minions
            $minionBonus = $this->getMinionCount() * self::ATTACK_MINION_BONUS_MULTIPLIER;
            $totalPower = $power + $minionBonus;

            $this->setHealthLevel(min(self::ATTACK_HEALTH_CAP, $this->getHealthLevel() + rand(self::ATTACK_HEALTH_REGEN_MIN, self::ATTACK_HEALTH_REGEN_MAX)));
            $this->setRealmPower($this->getRealmPower() + rand(self::ATTACK_REALM_POWER_GAIN_MIN, self::ATTACK_REALM_POWER_GAIN_MAX));

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

// NETHERLORD COLLECTION
$netherlordCollection = [
    $youngNetherlord,
    $defaultNetherlord,
    $ancientNetherlord
    // Add future netherlords here
];
?>