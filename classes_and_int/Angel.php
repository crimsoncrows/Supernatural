<?php

// REQUIRE DEPENDENCIES (parent, interface and trait)
require_once 'Supernatural.php';
require_once 'FlyandChange.php';
require_once 'Logger.php';

//class declaration
class Angel extends Supernatural implements FlyandChange
{
    use Logger;

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
        $this->setSkillLevel($this->getSkillLevel() + 5);
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
        if (rand(1, 100) <= 20) {
            // Success! Prey is redeemed
            $prey->setPreyMood("redeemed");
            $prey->setPreyHealth(0);
            $_SESSION['prey_mood'] = "redeemed";
            $_SESSION['prey_health'] = 0;

            $this->setSkillLevel($this->getSkillLevel() + 5);
            return $this->getName() . " attempts to heal " . $prey->getPreyName() .
                " and SUCCEEDS! " . $prey->getPreyName() . " is REDEEMED!";

        } else {
            // Failed attempt - prey remains unchanged
            $this->setSkillLevel($this->getSkillLevel() + 5);
            return $this->getName() . " attempts to heal " . $prey->getPreyName() .
                " but fails. " . $prey->getPreyName() . " remains unchanged.";
        }
    }

    public function blessPrey($prey, $blessing)
    {
        // Reduce cursed prey health by 3-8 after being blessed
        $damage = rand(3, 8);
        $newHealth = $prey->getPreyHealth() - $damage;
        $prey->setPreyHealth(max(0, $newHealth));
        $_SESSION['prey_health'] = $prey->getPreyHealth();

        //add divinity level after blessing
        $this->setDivinityLevel($this->getDivinityLevel() + 10);
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
        $this->setHealthLevel(max(0, $this->getHealthLevel() - 0.75));

        // 0.5% chance to spawn a malicious demon pretending to be an angel
        if (rand(1, 1000) <= 5) {
            $this->setHealthLevel(max(0, $this->getHealthLevel() - 40));
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
        $morphTypes = ["seraph", "white dove", "pillar of light", "winged flame", "cherub", "watcher", "herald", "veiled figure", "robed elder", "child of light", "wandering pilgrim", "blind prophet", "silent choir", "star-eyed stranger", "old priest", "barefoot orphan"];
        $morphedInto = $morphTypes[array_rand($morphTypes)];
        return $this->getName() . " morphs into a radiant form, inspiring hope in prey. " . $this->getName() . " is now a " .$morphedInto .".";
    }
    public function attack($prey)
    {
        //random attack number from 5-15 power. gains a little divinity when warding off demon
        if ($prey !== null) {
            $power = rand(5, 15);
            $prey->setPreyHealth(max(0, $prey->getPreyHealth() - $power));
            $this->setDivinityLevel($this->getDivinityLevel() + rand(1, 3));
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