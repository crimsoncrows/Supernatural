<?php

// =============================================
// 1. REQUIRE DEPENDENCIES
// =============================================

require_once 'Supernatural.php';
require_once 'FlyandChange.php';
require_once 'Logger.php';

// =============================================
// 2. CLASS DECLARATION
// =============================================

class Angel extends Supernatural implements FlyandChange
{
    use Logger;

    // =============================================
    // 3. PROPERTIES
    // =============================================

    private float $divinityLevel;
    private string $aura;

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
        $divinityLevel = 100.0,
        $aura = "Radiant"
    )
    {
        parent::__construct($name, $age, $humanEncounters, $ability, $skillLevel, $healthLevel);
        $this->divinityLevel = $divinityLevel;
        $this->aura = $aura;
    }

    // =============================================
    // 5. ATTRIBUTE ACCESS (GETTERS & SETTERS)
    // =============================================

    public function getDivinityLevel()
    {
        return $this->divinityLevel;
    }

    public function setDivinityLevel($divinityLevel)
    {
        $this->divinityLevel = max(0, $divinityLevel);
    }

    public function getAura()
    {
        return $this->aura;
    }

    public function setAura($aura)
    {
        $this->aura = $aura;
    }

    // =============================================
    // 6. OVERRIDDEN ABSTRACT METHODS (from Supernatural)
    // =============================================

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

    // =============================================
    // 7. ANGEL METHODS
    // =============================================

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
                " and SUCCEEDS! " . $prey->getPreyName() . " is REDEEMED! Vitality reduced to 0!";
        } else {
            // Failed attempt - prey remains unchanged
            $this->setSkillLevel($this->getSkillLevel() + 5);
            return $this->getName() . " attempts to heal " . $prey->getPreyName() .
                " but fails. " . $prey->getPreyName() . " remains unchanged.";
        }
    }

    public function blessPrey($prey, $blessing)
    {
        // Reduce prey health by 3-8
        $damage = rand(3, 8);
        $newHealth = $prey->getPreyHealth() - $damage;
        $prey->setPreyHealth(max(0, $newHealth));

        // Update session
        $_SESSION['prey_health'] = $prey->getPreyHealth();

        $this->setDivinityLevel($this->getDivinityLevel() + 10);
        return $this->getName() . " blesses " . $prey->getPreyName() .
            " with: " . $blessing . "! " . $prey->getPreyName() . " takes " . $damage . " damage!";
    }

    // =============================================
    // 8. FLYANDCHANGE INTERFACE METHODS
    // =============================================

    public function fly()
    {
        return $this->getName() . " soars gracefully through the skies.";
    }

    public function spawn()
    {
        return $this->getName() . " spawns a protective guardian to shield the prey.";
    }

    public function teleport()
    {
        return $this->getName() . " instantly teleports to aid a nearby soul in danger.";
    }

    public function morph()
    {
        return $this->getName() . " morphs into a radiant form, inspiring hope in prey.";
    }

    // =============================================
    // 9. ATTACK METHOD (for FlyandChange interface)
    // =============================================

    public function attack($prey)
    {
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

// =============================================
// 10. ANGEL PRESETS / INSTANCES
// =============================================

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

// =============================================
// 11. ANGEL COLLECTION
// =============================================

$angelCollection = [
    $youngAngel,
    $defaultAngel,
    $ancientAngel
    // Add future angels here
];
?>