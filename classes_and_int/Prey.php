<?php

// =============================================
// PREY CLASS
// Represents a human victim that creatures hunt
// =============================================

class Prey
{
    use Logger;

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
            $damage = rand(5, 15);
            $target->setHealthLevel(max(0, $target->getHealthLevel() - $damage));
            return $this->preyName . " attacks the creature, dealing " . $damage . " damage!";

            // ---- AGGRESSIVE MOOD ----
            // Prey fights back with vicious fury
        } elseif ($mood === 'aggressive') {
            $damage = rand(20, 30);
            $target->setHealthLevel(max(0, $target->getHealthLevel() - $damage));
            return $this->preyName . " lashes out viciously, dealing " . $damage . " damage!";

            // ---- MODERATE MOOD ----
            // Prey fights back with balanced force
        } elseif ($mood === 'moderate') {
            $damage = rand(10, 19);
            $target->setHealthLevel(max(0, $target->getHealthLevel() - $damage));
            return $this->preyName . " strikes back firmly, dealing " . $damage . " damage!";

            // ---- VIOLENT MOOD ----
            // Prey becomes extremely dangerous (Netherlord only)
        } elseif ($mood === 'violent') {
            $damage = rand(30, 50);
            $target->setHealthLevel(max(0, $target->getHealthLevel() - $damage));
            return $this->preyName . " attacks with deadly rage, dealing " . $damage . " damage!";

            // ---- SCARED, STARTLED, MORTIFIED ----
            // Prey is frightened but still fights weakly
        } elseif ($mood === 'scared' || $mood === 'startled' || $mood === 'mortified') {
            $damage = rand(2, 8);
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
                $damage = rand(3, 8);
                $target->setHealthLevel(max(0, $target->getHealthLevel() - $damage));
                return $this->preyName . " cautiously defends, dealing " . $damage . " damage!";
            }
        }
    }
}