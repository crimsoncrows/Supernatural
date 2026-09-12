<?php

// =============================================
// PREY CLASS
// Represents a human victim that creatures hunt
// =============================================

class Prey
{
    use Logger;

    // ATTACK DAMAGE BY MOOD
    private const LIGHT_DAMAGE_MIN = 5;
    private const LIGHT_DAMAGE_MAX = 15;
    private const AGGRESSIVE_DAMAGE_MIN = 20;
    private const AGGRESSIVE_DAMAGE_MAX = 30;
    private const MODERATE_DAMAGE_MIN = 10;
    private const MODERATE_DAMAGE_MAX = 19;
    private const VIOLENT_DAMAGE_MIN = 30;
    private const VIOLENT_DAMAGE_MAX = 50;
    private const SCARED_DAMAGE_MIN = 2;
    private const SCARED_DAMAGE_MAX = 8;
    private const NEUTRAL_DAMAGE_MIN = 3;
    private const NEUTRAL_DAMAGE_MAX = 8;

    // =============================================
    // PROPERTIES
    // =============================================

    private string $preyName;
    private int $preyAge;
    private float $preyHealth;
    private string $preyMood;

    // =============================================
    // CONSTRUCTOR
    // =============================================

    function __construct($preyName = "Lucian", $preyAge = 20, $preyHealth = 100.0, $preyMood = "Neutral")
    {
        $this->preyName = $preyName;
        $this->preyAge = $preyAge;
        $this->preyHealth = max(0, $preyHealth);
        $this->preyMood = $preyMood;
    }

    // =============================================
    // GETTERS
    // =============================================

    public function getPreyName() {
        return $this->preyName;
    }

    public function getPreyMood(){
        return $this->preyMood;
    }

    public function getPreyAge() {
        return $this->preyAge;
    }

    public function getPreyHealth() {
        return $this->preyHealth;
    }

    // =============================================
    // SETTERS
    // =============================================

    public function setPreyName($preyName) {
        $this->preyName = $preyName;
    }

    public function setPreyMood($preyMood){
        $this->preyMood = $preyMood;
    }

    public function setPreyAge($preyAge) {
        $this->preyAge = max(0, $preyAge);
    }

    public function setPreyHealth($preyHealth) {
        $this->preyHealth = max(0, $preyHealth);
    }

    // =============================================
    // COMBAT METHODS
    // =============================================

    public function attack($target) {
        $mood = $this->getPreyMood();

        // Check if target is a Netherlord
        $isNetherlord = ($target instanceof Netherlord);

        // ---- LIGHT MOOD ----
        // Prey is calm but will defend itself
        if ($mood === 'light' || $mood === 'good' || $mood === 'unreadable') {
            $damage = rand(self::LIGHT_DAMAGE_MIN, self::LIGHT_DAMAGE_MAX);
            $target->setHealthLevel(max(0, $target->getHealthLevel() - $damage));
            return $this->preyName . " attacks the creature, dealing " . $damage . " damage!";

            // ---- AGGRESSIVE MOOD ----
            // Prey fights back with vicious fury
        } elseif ($mood === 'aggressive') {
            $damage = rand(self::AGGRESSIVE_DAMAGE_MIN, self::AGGRESSIVE_DAMAGE_MAX);
            $target->setHealthLevel(max(0, $target->getHealthLevel() - $damage));
            return $this->preyName . " lashes out viciously, dealing " . $damage . " damage!";

            // ---- MODERATE MOOD ----
            // Prey fights back with balanced force
        } elseif ($mood === 'moderate') {
            $damage = rand(self::MODERATE_DAMAGE_MIN, self::MODERATE_DAMAGE_MAX);
            $target->setHealthLevel(max(0, $target->getHealthLevel() - $damage));
            return $this->preyName . " strikes back firmly, dealing " . $damage . " damage!";

            // ---- VIOLENT MOOD ----
            // Prey becomes extremely dangerous (Netherlord only)
        } elseif ($mood === 'violent') {
            $damage = rand(self::VIOLENT_DAMAGE_MIN, self::VIOLENT_DAMAGE_MAX);
            $target->setHealthLevel(max(0, $target->getHealthLevel() - $damage));
            return $this->preyName . " attacks with deadly rage, dealing " . $damage . " damage!";

            // ---- SCARED, STARTLED, MORTIFIED ----
            // Prey is frightened but still fights weakly
        } elseif ($mood === 'scared' || $mood === 'startled' || $mood === 'mortified') {
            $damage = rand(self::SCARED_DAMAGE_MIN, self::SCARED_DAMAGE_MAX);
            $target->setHealthLevel(max(0, $target->getHealthLevel() - $damage));
            return $this->preyName . " is scared and barely manages to defend, dealing " . $damage . " damage!";

            // ---- NEUTRAL/PASSIVE MOOD ----
            // Only Netherlord prey watches warily
        } else {
            // If target is Netherlord, prey watches warily
            if ($isNetherlord) {
                return $this->preyName . " watches warily, unsure what to do.";
            } else {
                // For other creatures, prey still attacks with basic damage
                $damage = rand(self::NEUTRAL_DAMAGE_MIN, self::NEUTRAL_DAMAGE_MAX);
                $target->setHealthLevel(max(0, $target->getHealthLevel() - $damage));
                return $this->preyName . " cautiously defends, dealing " . $damage . " damage!";
            }
        }
    }
}