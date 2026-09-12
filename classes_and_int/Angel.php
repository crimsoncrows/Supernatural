<?php

// REQUIRE DEPENDENCIES (parent, interface and trait)
require_once 'Supernatural.php';
require_once 'FlyandChange.php';
require_once 'Logger.php';

//class declaration
class Angel extends Supernatural implements FlyandChange
{
    use Logger;

    // SKILL
    private const SKILL_BOOST = 5;

    // HEAL PREY
    private const HEAL_SUCCESS_CHANCE_PER_100 = 20;
    private const HEAL_SKILL_BOOST = 5;

    // BLESS PREY
    private const BLESS_DAMAGE_MIN = 3;
    private const BLESS_DAMAGE_MAX = 8;
    private const BLESS_DIVINITY_GAIN = 10;

    // SPAWN
    private const SPAWN_HEALTH_COST = 0.75;
    private const SPAWN_DEMON_CHANCE_PER_1000 = 5;
    private const SPAWN_DEMON_HEALTH_PENALTY = 40;

    // MORPH FLAVOR
    private const MORPH_TYPES = ["seraph", "white dove", "pillar of light", "winged flame", "cherub", "watcher", "herald", "veiled figure", "robed elder", "child of light", "wandering pilgrim", "blind prophet", "silent choir", "star-eyed stranger", "old priest", "barefoot orphan"];

    // ATTACK
    private const ATTACK_POWER_MIN = 5;
    private const ATTACK_POWER_MAX = 15;
    private const ATTACK_DIVINITY_GAIN_MIN = 1;
    private const ATTACK_DIVINITY_GAIN_MAX = 3;

    // PROPERTIES exclusive to angel
    private float $divinityLevel;
    private string $aura;

    //constructor (with parent constructor)
    public function __construct(
        $name = "",
        $age = 0,
        $humanEncounters = 0,
        $ability = "",
        $skillLevel = 0.0,
        $healthLevel = 0.0,
        $divinityLevel = 100.0,
        $aura = "Radiant"
    )
    {
        parent::__construct($name, $age, $humanEncounters, $ability, $skillLevel, $healthLevel);
        $this->divinityLevel = $divinityLevel;
        $this->aura = $aura;
    }

    //get set
    public function getDivinityLevel() { return $this->divinityLevel; }
    public function setDivinityLevel($divinityLevel){ $this->divinityLevel = max(0, $divinityLevel); }

    public function getAura() { return $this->aura; }
    public function setAura($aura){ $this->aura = $aura; }


    //OVERRIDDEN ABSTRACT METHODS (from Supernatural)
    public function performSkill()
    {
        $this->setSkillLevel($this->getSkillLevel() + self::SKILL_BOOST);
        return $this->getName() . " performs " . $this->getAbility() .
            " with skill level " . $this->getSkillLevel() . ".";
    }

    public function goForExpedition()
    {
        $this->setHumanEncounters($this->getHumanEncounters() + 1);
        return $this->getName() . " descends to the human realm to guide lost souls.";
    }

    //ANGEL METHODS
    public function healPrey($prey, $healAmount)
    {
        // 20% chance to redeem the prey (set mood to "redeemed" and vitality to 0)
        if (rand(1, 100) <= self::HEAL_SUCCESS_CHANCE_PER_100) {
            // Success! Prey is redeemed
            $prey->setPreyMood("redeemed");
            $prey->setPreyHealth(0);
            $_SESSION['prey_mood'] = "redeemed";
            $_SESSION['prey_health'] = 0;

            $this->setSkillLevel($this->getSkillLevel() + self::HEAL_SKILL_BOOST);
            return $this->getName() . " attempts to heal " . $prey->getPreyName() .
                " and SUCCEEDS! " . $prey->getPreyName() . " is REDEEMED!";

        } else {
            // Failed attempt - prey remains unchanged
            $this->setSkillLevel($this->getSkillLevel() + self::HEAL_SKILL_BOOST);
            return $this->getName() . " attempts to heal " . $prey->getPreyName() .
                " but fails. " . $prey->getPreyName() . " remains unchanged.";
        }
    }

    public function blessPrey($prey, $blessing)
    {
        // Reduce cursed prey health by 3-8 after being blessed
        $damage = rand(self::BLESS_DAMAGE_MIN, self::BLESS_DAMAGE_MAX);
        $newHealth = $prey->getPreyHealth() - $damage;
        $prey->setPreyHealth(max(0, $newHealth));
        $_SESSION['prey_health'] = $prey->getPreyHealth();

        //add divinity level after blessing
        $this->setDivinityLevel($this->getDivinityLevel() + self::BLESS_DIVINITY_GAIN);
        return $this->getName() . " blesses " . $prey->getPreyName() .
            " with: " . $blessing . "! " . $prey->getPreyName() . " takes " . $damage . " damage!";
    }

    //FLYANDCHANGE INTERFACE METHODS
    public function fly()
    {
        return $this->getName() . " soars gracefully through the skies.";
    }

    public function spawn()
    {
        $this->setHealthLevel(max(0, $this->getHealthLevel() - self::SPAWN_HEALTH_COST));

        // 0.5% chance to spawn a malicious demon pretending to be an angel
        if (rand(1, 1000) <= self::SPAWN_DEMON_CHANCE_PER_1000) {
            $this->setHealthLevel(max(0, $this->getHealthLevel() - self::SPAWN_DEMON_HEALTH_PENALTY));
            return "Bad health drop!! " . $this->getName() . " attempted to spawn a guardian, but a malicious demon slipped through disguised as an angel!";
        }

        return $this->getName() . " spawns a protective guardian to shield the prey.";
    }

    public function teleport()
    {
        return $this->getName() . " instantly teleports to aid a nearby soul in danger.";
    }

    public function morph()
    {
        $morphedInto = self::MORPH_TYPES[array_rand(self::MORPH_TYPES)];
        return $this->getName() . " morphs into a radiant form, inspiring hope in prey. " . $this->getName() . " is now a " .$morphedInto .".";
    }
    public function attack($prey)
    {
        //random attack number from 5-15 power. gains a little divinity when warding off demon
        if ($prey !== null) {
            $power = rand(self::ATTACK_POWER_MIN, self::ATTACK_POWER_MAX);
            $prey->setPreyHealth(max(0, $prey->getPreyHealth() - $power));
            $this->setDivinityLevel($this->getDivinityLevel() + rand(self::ATTACK_DIVINITY_GAIN_MIN, self::ATTACK_DIVINITY_GAIN_MAX));
            $logMessage = $this->getName() . " smites the prey with holy light, " . $power . " damage!";
            return $logMessage;
        }
        return $this->getName() . " has no prey to smite.";
    }
}

//ANGEL PRESETS / INSTANCES

// ---- YOUNG ANGEL ----
// A novice angel with low divinity
$youngAngel = new Angel(
    'Ariel', 500, 10, 'Holy Light', 40.0, 60.0,
    35.0, 'Faint'
);

// ---- DEFAULT ANGEL ----
// A standard angel with moderate power
$defaultAngel = new Angel(
    'Gabriel', 1500, 30, 'Divine Protection', 80.0, 100.0,
    65.0, 'Radiant'
);

// ---- ANCIENT ANGEL ----
// A legendary angel with immense power
$ancientAngel = new Angel(
    'Michael', 5000, 60, 'Heavenly Judgement', 95.0, 100.0,
    95.0, 'Overwhelming'
);

// ANGEL COLLECTION
$angelCollection = [
    $youngAngel,
    $defaultAngel,
    $ancientAngel
    // Add future angels here
];
?>