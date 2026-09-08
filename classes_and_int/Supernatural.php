<?php
abstract class Supernatural{

    private string $name;
    private int $age;
    private int $humanEncounters;
    private string $ability;
    private float $skillLevel;
    private float $healthLevel;


    function __construct($name, $age, $humanEncounters, $ability, $skillLevel, $healthLevel){
        $this->name = $name;
        $this->age = $age;
        $this->humanEncounters = $humanEncounters;
        $this->ability = $ability;
        $this->skillLevel = $skillLevel;
        $this->healthLevel = $healthLevel;
    }

    public function getName(){
        return $this->name;
    }
    public function getAge(){
        return $this->age;
    }
    public function getHumanEncounters(){
        return $this->humanEncounters;
    }
    public function getAbility(){
        return $this->ability;
    }
    public function getSkillLevel(){
        return $this->skillLevel;
    }
    public function getHealthLevel(){
        return $this->healthLevel;
    }

    public function setName($name){
        $this->name = $name;
    }
    public function setAge($age){
        $this->age = $age;
    }
    public function setHumanEncounters($humanEncounters){
        $this->humanEncounters = $humanEncounters;
    }
    public function setAbility($ability){
        $this->ability = $ability;
    }
    public function setSkillLevel($skillLevel){
        $this->skillLevel = $skillLevel;
    }
    public function setHealthLevel($healthLevel){
        $this->healthLevel = $healthLevel;
    }


    abstract function performSkill();
    abstract function goForExpedition();

}