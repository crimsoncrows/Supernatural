# Supernatural
A PHP practice project — an OOP battle simulator where creatures face off against prey in a turn-based encounter with a human/demon "Prey" NPC caught in the middle. 
This is a **remake of a 2025 Java CLI OOP project**, rebuilt in PHP as a study in translating class hierarchies, interfaces, and inheritance across languages.
It includes Player vs. Bot with RNG Entity Generation.

![Screenshot 1](https://i.postimg.cc/ZnYQjTjC/image.png)
![Screenshot 2](https://i.postimg.cc/xCLQBTKq/image.png)

## Concept
> Born once in Java, reborn now in PHP — every predator and prey below carries the same blood, the same instincts, translated line by line across languages as a study in Object-Oriented Programming.

Pick a creature, then battle a randomly generated human prey (or, for Angel/Seraphim, a demon) across a series of skill-based actions until one side's health hits zero.


## Playable Creatures (More to Come soon)

| Creature | Role | Unique Traits |
|---|---|---|
| Red Vampire | Predator | Blood Drank, Bite Force, Mood |
| Lone Wolf (Werewolf) | Predator | Ferocity |
| Crimson Fiend | Predator | Corruption Level, Pact Count, Fear Aura |
| Netherlord | Predator | Souls Collected, Realm Power, Minion Count |
| Guardian Angel | Guardian | Divinity Level, Aura |
| Divine Seraphim | Guardian | Divinity Level, Wings Count |

## How to Play

1. **Choose your side** — pick a predator to hunt, or a guardian to protect/resist.
2. **Face your foe** — each round your creature and the prey exchange actions: predators attack and drain, guardians heal and bless.
3. **Watch your stats** — Health, Skill Level, and creature-specific traits shift with every action. Rare events happen based on mood, stats and probability.
4. **Survive or feast** — the round ends when one side's health reaches zero.

## Project Structure

```
Supernatural/
├── index.html              # Entry point
├── home.php                 # Character select screen
├── prepare.php               # Pre-battle setup (creates session state)
├── battlefield.php           # Main battle loop — session-driven combat UI + logic
├── reset.php                  # Resets session/battle state
├── style.css                   # Styling
├── classes_and_int/
│   ├── Supernatural.php       # Base class for all creatures
│   ├── FlyandChange.php        # Interface: fly, spawn, teleport, morph, attack
│   ├── Nocturnal.php            # Interface: goOutside, monitorMoonStatus, lurkInTheDark
│   ├── Logger.php                # Battle log trait/helper
│   ├── Prey.php                   # Human/demon prey NPC
│   ├── Fiend.php
│   ├── Vampire.php
│   ├── Netherlord.php
│   ├── Angel.php
│   ├── Seraphim.php
│   └── Werewolf.php
├── N109-LOGO.png
└── bg-n109-main.png
```

## OOP Design

- **`Supernatural`** — abstract base class shared by every creature (name, age, human encounters, ability, skill level, health level).
- **`FlyandChange`** — interface implemented by creatures that fly/spawn/teleport/morph/attack (e.g. Fiend, Netherlord, Angel, Seraphim).
- **`Nocturnal`** — interface implemented by night-bound creatures (e.g. Vampire, Werewolf) with moon/dark-related behavior.
- **`Logger`** — shared battle-log functionality used across creatures and prey.
- Each concrete creature class (`Fiend`, `Vampire`, `Netherlord`, `Angel`, `Seraphim`, `Werewolf`) extends `Supernatural`, implements the relevant interface(s), and adds its own stats and abilities.
- Combat state is persisted via PHP `$_SESSION`, so a creature/prey pair survives across form POSTs in `battlefield.php` until reset.

## Tech Stack

- PHP (procedural front controller + OOP class model, session-based state)
- HTML / CSS
- Built with **PhpStorm**

## Status

Practice project — not production code. Built to reinforce OOP concepts (inheritance, interfaces, encapsulation) by porting an existing Java implementation to PHP. Bugs may unknowingly be present, but will be fixed if there are any.

## Notes
AI assistance was used for repetitive/boilerplate tasks during development (e.g. repeated form/markup blocks, boilerplate class scaffolding).
