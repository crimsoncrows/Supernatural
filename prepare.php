<?php
// =============================================
// REQUIRE DEPENDENCIES
// =============================================

require_once 'classes_and_int/Supernatural.php';
require_once 'classes_and_int/FlyandChange.php';
require_once 'classes_and_int/Nocturnal.php';
require_once 'classes_and_int/Logger.php';
require_once 'classes_and_int/Fiend.php';
require_once 'classes_and_int/Vampire.php';
require_once 'classes_and_int/Netherlord.php';
require_once 'classes_and_int/Angel.php';
require_once 'classes_and_int/Seraphim.php';
require_once 'classes_and_int/Werewolf.php';
require_once 'classes_and_int/Prey.php';

// =============================================
// SESSION INITIALIZATION
// =============================================

session_start();

if (!isset($_SESSION['presets'])) {
    $_SESSION['presets'] = [];
}

// =============================================
// HANDLE PLAYER SELECTION
// =============================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['player'])) {
    $_SESSION['player'] = $_POST['player'];
}

$player = $_SESSION['player'] ?? '';

// =============================================
// PLAYER IMAGE & TITLE MAPPING
// =============================================

if ($player === 'Fiend') {
    $img = 'https://i.pinimg.com/736x/55/3f/cd/553fcd94e06b4e79d039179265b79cc4.jpg';
    $title = 'Crimson Fiend';
} elseif ($player === 'Werewolf') {
    $img = 'https://m.media-amazon.com/images/I/71a75Jhy3qL._AC_UF894,1000_QL80_.jpg';
    $title = 'Lone Wolf';
} elseif ($player === 'Seraphim') {
    $img = 'https://image.tensorartassets.com/cdn-cgi/image/anim=true,plain=false,w=500,q=85/model_showcase/707950927233096208/1e229740-48c4-ee6e-777f-edd02654646a.jpeg';
    $title = 'Divine Seraphim';
} elseif ($player === 'Angel') {
    $img = 'https://cdn.talkie-ai.com/talkie/prod/img/2024-02-02/6f30c1ab-dee9-46c8-9b65-6858d0dc6bd8.jpeg?x-oss-process=image/resize,w_1024/format,webp';
    $title = 'Guardian Angel';
} elseif ($player === 'Vampire') {
    $img = 'https://creator.nightcafe.studio/jobs/Yh2f73gnUNMLBGNlZ6ye/Yh2f73gnUNMLBGNlZ6ye--1--ba861.jpg';
    $title = 'Red Vampire';
} elseif ($player === 'Netherlord') {
    $img = 'https://i.pinimg.com/736x/55/4e/60/554e6019d08d9cb72f06b75585a0ad8a.jpg';
    $title = 'Netherlord';
} else {
    $img = '';
    $title = 'Unknown Creature';
}

// =============================================
// HANDLE CREATURE CREATION / SELECTION
// =============================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['player'])
        && (isset($_POST['presetIndex']) || isset($_POST['name']))) {

    // ---- FIEND CREATION ----
    if ($player === 'Fiend') {

        if (isset($_POST['presetIndex']) && isset($fiendCollection[(int)$_POST['presetIndex']])) {
            // Preset card was clicked
            $creature = $fiendCollection[(int)$_POST['presetIndex']];
        } else {
            // Custom form was submitted
            $creature = new Fiend(
                    $_POST['name'] ?? '',
                    (int)($_POST['age'] ?? 0),
                    (int)($_POST['humanEncounters'] ?? 0),
                    $_POST['ability'] ?? '',
                    (float)($_POST['skillLevel'] ?? 0),
                    (float)($_POST['healthLevel'] ?? 0),
                    (float)($_POST['corruptionLevel'] ?? 50.0),
                    (int)($_POST['pactCount'] ?? 0),
                    $_POST['fearAura'] ?? 'Low'
            );
        }

        // Store Fiend in session
        $_SESSION['creature_type']   = 'Fiend';
        $_SESSION['name']            = $creature->getName();
        $_SESSION['age']             = $creature->getAge();
        $_SESSION['humanEncounters'] = $creature->getHumanEncounters();
        $_SESSION['ability']         = $creature->getAbility();
        $_SESSION['skillLevel']      = $creature->getSkillLevel();
        $_SESSION['healthLevel']     = $creature->getHealthLevel();
        $_SESSION['corruptionLevel'] = $creature->getCorruptionLevel();
        $_SESSION['pactCount']       = $creature->getPactCount();
        $_SESSION['fearAura']        = $creature->getFearAura();

        header('Location: battlefield.php');
        exit;

        // ---- VAMPIRE CREATION ----
    } elseif ($player === 'Vampire') {

        if (isset($_POST['presetIndex']) && isset($vampireCollection[(int)$_POST['presetIndex']])) {
            // Preset card was clicked
            $creature = $vampireCollection[(int)$_POST['presetIndex']];
        } else {
            // Custom form was submitted
            $creature = new Vampire(
                    $_POST['name'] ?? '',
                    (int)($_POST['age'] ?? 0),
                    (int)($_POST['humanEncounters'] ?? 0),
                    $_POST['ability'] ?? '',
                    (float)($_POST['skillLevel'] ?? 0),
                    (float)($_POST['healthLevel'] ?? 0),
                    (float)($_POST['bloodDrank'] ?? 0.0),
                    (float)($_POST['biteForce'] ?? 75.5)
            );
        }

        // Store Vampire in session
        $_SESSION['creature_type']   = 'Vampire';
        $_SESSION['name']            = $creature->getName();
        $_SESSION['age']             = $creature->getAge();
        $_SESSION['humanEncounters'] = $creature->getHumanEncounters();
        $_SESSION['ability']         = $creature->getAbility();
        $_SESSION['skillLevel']      = $creature->getSkillLevel();
        $_SESSION['healthLevel']     = $creature->getHealthLevel();
        $_SESSION['bloodDrank']      = $creature->getBloodDrank();
        $_SESSION['biteForce']       = $creature->getBiteForce();
        $_SESSION['mood']            = $creature->getMood();

        header('Location: battlefield.php');
        exit;

        // ---- NETHERLORD CREATION ----
    } elseif ($player === 'Netherlord') {

        if (isset($_POST['presetIndex']) && isset($netherlordCollection[(int)$_POST['presetIndex']])) {
            // Preset card was clicked
            $creature = $netherlordCollection[(int)$_POST['presetIndex']];
        } else {
            // Custom form was submitted
            $creature = new Netherlord(
                    $_POST['name'] ?? '',
                    (int)($_POST['age'] ?? 0),
                    (int)($_POST['humanEncounters'] ?? 0),
                    $_POST['ability'] ?? '',
                    (float)($_POST['skillLevel'] ?? 0),
                    (float)($_POST['healthLevel'] ?? 0),
                    (int)($_POST['soulsCollected'] ?? 0),
                    (float)($_POST['realmPower'] ?? 30.0),
                    (int)($_POST['minionCount'] ?? 0)
            );
        }

        // Store Netherlord in session
        $_SESSION['creature_type']   = 'Netherlord';
        $_SESSION['name']            = $creature->getName();
        $_SESSION['age']             = $creature->getAge();
        $_SESSION['humanEncounters'] = $creature->getHumanEncounters();
        $_SESSION['ability']         = $creature->getAbility();
        $_SESSION['skillLevel']      = $creature->getSkillLevel();
        $_SESSION['healthLevel']     = $creature->getHealthLevel();
        $_SESSION['soulsCollected']  = $creature->getSoulsCollected();
        $_SESSION['realmPower']      = $creature->getRealmPower();
        $_SESSION['minionCount']     = $creature->getMinionCount();

        header('Location: battlefield.php');
        exit;

        // ---- ANGEL CREATION ----
    } elseif ($player === 'Angel') {

        if (isset($_POST['presetIndex']) && isset($angelCollection[(int)$_POST['presetIndex']])) {
            // Preset card was clicked
            $creature = $angelCollection[(int)$_POST['presetIndex']];
        } else {
            // Custom form was submitted
            $creature = new Angel(
                    $_POST['name'] ?? '',
                    (int)($_POST['age'] ?? 0),
                    (int)($_POST['humanEncounters'] ?? 0),
                    $_POST['ability'] ?? '',
                    (float)($_POST['skillLevel'] ?? 0),
                    (float)($_POST['healthLevel'] ?? 0),
                    (float)($_POST['divinityLevel'] ?? 100.0),
                    $_POST['aura'] ?? 'Radiant'
            );
        }

        // Store Angel in session
        $_SESSION['creature_type']   = 'Angel';
        $_SESSION['name']            = $creature->getName();
        $_SESSION['age']             = $creature->getAge();
        $_SESSION['humanEncounters'] = $creature->getHumanEncounters();
        $_SESSION['ability']         = $creature->getAbility();
        $_SESSION['skillLevel']      = $creature->getSkillLevel();
        $_SESSION['healthLevel']     = $creature->getHealthLevel();
        $_SESSION['divinityLevel']   = $creature->getDivinityLevel();
        $_SESSION['aura']            = $creature->getAura();

        header('Location: battlefield.php');
        exit;

        // ---- SERAPHIM CREATION ----
    } elseif ($player === 'Seraphim') {

        if (isset($_POST['presetIndex']) && isset($seraphimCollection[(int)$_POST['presetIndex']])) {
            // Preset card was clicked
            $creature = $seraphimCollection[(int)$_POST['presetIndex']];
        } else {
            // Custom form was submitted
            $creature = new Seraphim(
                    $_POST['name'] ?? '',
                    (int)($_POST['age'] ?? 0),
                    (int)($_POST['humanEncounters'] ?? 0),
                    $_POST['ability'] ?? '',
                    (float)($_POST['skillLevel'] ?? 0),
                    (float)($_POST['healthLevel'] ?? 0),
                    (float)($_POST['divinityLevel'] ?? 50.0),
                    (int)($_POST['wingsCount'] ?? 6)
            );
        }

        // Store Seraphim in session
        $_SESSION['creature_type']   = 'Seraphim';
        $_SESSION['name']            = $creature->getName();
        $_SESSION['age']             = $creature->getAge();
        $_SESSION['humanEncounters'] = $creature->getHumanEncounters();
        $_SESSION['ability']         = $creature->getAbility();
        $_SESSION['skillLevel']      = $creature->getSkillLevel();
        $_SESSION['healthLevel']     = $creature->getHealthLevel();
        $_SESSION['divinityLevel']   = $creature->getDivinityLevel();
        $_SESSION['wingsCount']      = $creature->getWingsCount();

        header('Location: battlefield.php');
        exit;

        // ---- WEREWOLF CREATION ----
    } elseif ($player === 'Werewolf') {

        if (isset($_POST['presetIndex']) && isset($werewolfCollection[(int)$_POST['presetIndex']])) {
            // Preset card was clicked
            $creature = $werewolfCollection[(int)$_POST['presetIndex']];
        } else {
            // Custom form was submitted
            $creature = new Werewolf(
                    $_POST['name'] ?? '',
                    (int)($_POST['age'] ?? 0),
                    (int)($_POST['humanEncounters'] ?? 0),
                    $_POST['ability'] ?? '',
                    (float)($_POST['skillLevel'] ?? 0),
                    (float)($_POST['healthLevel'] ?? 0),
                    (float)($_POST['ferocity'] ?? 50.0)
            );
        }

        // Store Werewolf in session
        $_SESSION['creature_type']   = 'Werewolf';
        $_SESSION['name']            = $creature->getName();
        $_SESSION['age']             = $creature->getAge();
        $_SESSION['humanEncounters'] = $creature->getHumanEncounters();
        $_SESSION['ability']         = $creature->getAbility();
        $_SESSION['skillLevel']      = $creature->getSkillLevel();
        $_SESSION['healthLevel']     = $creature->getHealthLevel();
        $_SESSION['ferocity']        = $creature->getFerocity();

        header('Location: battlefield.php');
        exit;
    }
}
?>

<!-- ============================================= -->
<!-- HTML STRUCTURE - PREPARATION PAGE -->
<!-- ============================================= -->

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/png" href="https://png.pngtree.com/png-clipart/20250123/original/pngtree-blood-moon-png-image_20325627.png">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <title>php practice by crimsoncrows</title>
    <link href="https://fonts.googleapis.com/css2?family=Caudex:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
</head>
<body>

<!-- ============================================= -->
<!-- INTRO TEXT SECTION -->
<!-- ============================================= -->

<div class="intro-text">
    <h2>The Battlefield</h2>
    <p>
        Beneath the bleeding glow of a crimson moon lies a desolate expanse of
        crumbling gothic ruins and encroaching shadows—the ultimate arena for the creatures of the night.
        Choose a preset creature or create your own. Mechanics can be read below before the battle.
    </p>
</div>

<!-- ============================================= -->
<!-- SETUP WRAPPER - CONTAINS IMAGE & FORM -->
<!-- ============================================= -->

<div class="setup-wrapper">

    <!-- ---- PLAYER IMAGE SECTION ---- -->
    <div class="setup-pic">
        <img class="creature-img floating" src="<?= $img ?>" alt="<?= $title ?>">
        <h1 class="char-name"><?= $title ?></h1>
    </div>

    <!-- ---- SETUP FORM SECTION ---- -->
    <div class="setup-form">

        <!-- PRESET CARDS SECTION -->
        <div class="preset-section">
            <?php
            if ($player === 'Fiend') {
                foreach ($fiendCollection as $index => $fiendPreset) {
                    ?>
                    <form method="POST" action="prepare.php" class="char-form">
                        <input type="hidden" name="player" value="Fiend">
                        <input type="hidden" name="presetIndex" value="<?= $index ?>">
                        <button type="submit" class="char-card">
                            <div class="frame">
                                <h1 class="char-name"><?= htmlspecialchars($fiendPreset->getName()) ?></h1>
                                <p class="char-info"><?= $fiendPreset->getAbility()?></p>
                            </div>
                        </button>
                    </form>
                    <?php
                }
            } elseif ($player === 'Vampire') {
                foreach ($vampireCollection as $index => $vampirePreset) {
                    ?>
                    <form method="POST" action="prepare.php" class="char-form">
                        <input type="hidden" name="player" value="Vampire">
                        <input type="hidden" name="presetIndex" value="<?= $index ?>">
                        <button type="submit" class="char-card">
                            <div class="frame">
                                <h1 class="char-name"><?= htmlspecialchars($vampirePreset->getName()) ?></h1>
                                <p class="char-info"><?= $vampirePreset->getAbility()?></p>
                            </div>
                        </button>
                    </form>
                    <?php
                }
            } elseif ($player === 'Netherlord') {
                foreach ($netherlordCollection as $index => $netherlordPreset) {
                    ?>
                    <form method="POST" action="prepare.php" class="char-form">
                        <input type="hidden" name="player" value="Netherlord">
                        <input type="hidden" name="presetIndex" value="<?= $index ?>">
                        <button type="submit" class="char-card">
                            <div class="frame">
                                <h1 class="char-name"><?= htmlspecialchars($netherlordPreset->getName()) ?></h1>
                                <p class="char-info"><?= $netherlordPreset->getAbility()?></p>
                            </div>
                        </button>
                    </form>
                    <?php
                }
            } elseif ($player === 'Angel') {
                foreach ($angelCollection as $index => $angelPreset) {
                    ?>
                    <form method="POST" action="prepare.php" class="char-form">
                        <input type="hidden" name="player" value="Angel">
                        <input type="hidden" name="presetIndex" value="<?= $index ?>">
                        <button type="submit" class="char-card">
                            <div class="frame">
                                <h1 class="char-name"><?= htmlspecialchars($angelPreset->getName()) ?></h1>
                                <p class="char-info"><?= $angelPreset->getAbility()?></p>
                            </div>
                        </button>
                    </form>
                    <?php
                }
            } elseif ($player === 'Seraphim') {
                foreach ($seraphimCollection as $index => $seraphimPreset) {
                    ?>
                    <form method="POST" action="prepare.php" class="char-form">
                        <input type="hidden" name="player" value="Seraphim">
                        <input type="hidden" name="presetIndex" value="<?= $index ?>">
                        <button type="submit" class="char-card">
                            <div class="frame">
                                <h1 class="char-name"><?= htmlspecialchars($seraphimPreset->getName()) ?></h1>
                                <p class="char-info"><?= $seraphimPreset->getAbility()?></p>
                            </div>
                        </button>
                    </form>
                    <?php
                }
            } elseif ($player === 'Werewolf') {
                foreach ($werewolfCollection as $index => $werewolfPreset) {
                    ?>
                    <form method="POST" action="prepare.php" class="char-form">
                        <input type="hidden" name="player" value="Werewolf">
                        <input type="hidden" name="presetIndex" value="<?= $index ?>">
                        <button type="submit" class="char-card">
                            <div class="frame">
                                <h1 class="char-name"><?= htmlspecialchars($werewolfPreset->getName()) ?></h1>
                                <p class="char-info"><?= $werewolfPreset->getAbility()?></p>
                            </div>
                        </button>
                    </form>
                    <?php
                }
            }
            ?>
        </div>

        <!-- ---- CUSTOM CREATURE FORM ---- -->
        <form method="POST" action="prepare.php">
            <input type="hidden" name="player" value="<?= $player ?>">

            <div class="field">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter creature name">
            </div>

            <div class="field">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" placeholder="e.g. 300">
            </div>

            <div class="field">
                <label for="humanEncounters">Human Encounters</label>
                <input type="number" id="humanEncounters" name="humanEncounters" placeholder="e.g. 12">
            </div>

            <div class="field">
                <label for="ability">Ability</label>
                <input type="text" id="ability" name="ability" placeholder="e.g. Hypnosis">
            </div>

            <div class="field">
                <label for="skillLevel">Skill Level</label>
                <input type="number" step="0.1" id="skillLevel" name="skillLevel" placeholder="e.g. 90.0">
            </div>

            <div class="field">
                <label for="healthLevel">Health Level</label>
                <input type="number" step="0.1" id="healthLevel" name="healthLevel" placeholder="e.g. 100.0">
            </div>

            <?php if ($player === 'Fiend') { ?>
                <!-- Fiend-specific fields -->
                <div class="field">
                    <label for="corruptionLevel">Corruption Level</label>
                    <input type="number" step="0.1" id="corruptionLevel" name="corruptionLevel" placeholder="e.g. 50.0">
                </div>

                <div class="field">
                    <label for="pactCount">Pact Count</label>
                    <input type="number" id="pactCount" name="pactCount" placeholder="e.g. 0">
                </div>

                <div class="field">
                    <label for="fearAura">Fear Aura</label>
                    <input type="text" id="fearAura" name="fearAura" placeholder="e.g. Low, Medium, High">
                </div>
            <?php } ?>

            <?php if ($player === 'Vampire') { ?>
                <!-- Vampire-specific fields -->
                <div class="field">
                    <label for="bloodDrank">Blood Drank</label>
                    <input type="number" step="0.1" id="bloodDrank" name="bloodDrank" placeholder="e.g. 15.0">
                </div>

                <div class="field">
                    <label for="biteForce">Bite Force</label>
                    <input type="number" step="0.1" id="biteForce" name="biteForce" placeholder="e.g. 75.5">
                </div>
            <?php } ?>

            <?php if ($player === 'Netherlord') { ?>
                <!-- Netherlord-specific fields -->
                <div class="field">
                    <label for="soulsCollected">Souls Collected</label>
                    <input type="number" id="soulsCollected" name="soulsCollected" placeholder="e.g. 10">
                </div>

                <div class="field">
                    <label for="realmPower">Realm Power</label>
                    <input type="number" step="0.1" id="realmPower" name="realmPower" placeholder="e.g. 30.0">
                </div>

                <div class="field">
                    <label for="minionCount">Minion Count</label>
                    <input type="number" id="minionCount" name="minionCount" placeholder="e.g. 2">
                </div>
            <?php } ?>

            <?php if ($player === 'Angel') { ?>
                <!-- Angel-specific fields -->
                <div class="field">
                    <label for="divinityLevel">Divinity Level</label>
                    <input type="number" step="0.1" id="divinityLevel" name="divinityLevel" placeholder="e.g. 100.0">
                </div>

                <div class="field">
                    <label for="aura">Aura</label>
                    <input type="text" id="aura" name="aura" placeholder="e.g. Radiant, Faint, Overwhelming">
                </div>
            <?php } ?>

            <?php if ($player === 'Seraphim') { ?>
                <!-- Seraphim-specific fields -->
                <div class="field">
                    <label for="divinityLevel">Divinity Level</label>
                    <input type="number" step="0.1" id="divinityLevel" name="divinityLevel" placeholder="e.g. 50.0">
                </div>

                <div class="field">
                    <label for="wingsCount">Wings Count</label>
                    <input type="number" id="wingsCount" name="wingsCount" placeholder="e.g. 6">
                </div>
            <?php } ?>

            <?php if ($player === 'Werewolf') { ?>
                <!-- Werewolf-specific fields -->
                <div class="field">
                    <label for="ferocity">Ferocity</label>
                    <input type="number" step="0.1" id="ferocity" name="ferocity" placeholder="e.g. 50.0">
                </div>
            <?php } ?>

            <button type="submit" class="submit-btn">Enter the Night</button>
        </form>
    </div>
</div>

<!-- ============================================= -->
<!-- CLASS MECHANICS - EDUCATIONAL -->
<!-- ============================================= -->

<div class="class-mechanics">
    <h2>How This Battle Works</h2>

    <?php if ($player === 'Fiend') { ?>
        <div class="class-explanation">
            <h3>Fiend</h3>
            <p><strong>Role:</strong> Dark manipulator from the abyss.</p>
            <p><strong>Traits:</strong> Corruption Level, Pact Count, Fear Aura.</p>
            <p><strong>OOP Concepts:</strong></p>
            <ul>
                <li><strong>Inheritance:</strong> Extends Supernatural abstract class.</li>
                <li><strong>Interface:</strong> Implements FlyandChange.</li>
                <li><strong>Trait:</strong> Uses Logger for battle logging.</li>
                <li><strong>Encapsulation:</strong> Private properties with public getters/setters.</li>
            </ul>
            <p><strong>Abilities:</strong></p>
            <ul>
                <li><strong>performSkill():</strong> Scales skill gains based on health tier.</li>
                <li><strong>attack($prey):</strong> Deals 5-20 damage, increases corruption by 3-5.</li>
                <li><strong>eatSoul($prey):</strong> Conditional (health ≤ 20, skill ≥ 80). Restores 20-30 health.</li>
                <li><strong>createPact():</strong> Conditional (health ≤ 90, prey mood = "good").</li>
                <li><strong>fly/spawn/teleport/morph:</strong> Decrease health for dark maneuvers.</li>
            </ul>
        </div>
    <?php } elseif ($player === 'Vampire') { ?>
        <div class="class-explanation">
            <h3>Vampire</h3>
            <p><strong>Role:</strong> Undead blood-drinker of the night.</p>
            <p><strong>Traits:</strong> Blood Drank, Bite Force, Mood.</p>
            <p><strong>OOP Concepts:</strong></p>
            <ul>
                <li><strong>Inheritance:</strong> Extends Supernatural abstract class.</li>
                <li><strong>Interface:</strong> Implements Nocturnal.</li>
                <li><strong>Trait:</strong> Uses Logger for battle logging.</li>
                <li><strong>Encapsulation:</strong> Private properties with public accessors.</li>
            </ul>
            <p><strong>Abilities:</strong></p>
            <ul>
                <li><strong>attackAndBite($prey, $bloodToDrain):</strong> Bite or drain blood. Restores 5-8 health on drain.</li>
                <li><strong>Weakness Triggers:</strong> 30% chance to trigger garlic, sunlight, or holy water.</li>
                <li><strong>Nocturnal Interface:</strong> goOutside(), monitorMoonStatus(), lurkInTheDark().</li>
            </ul>
        </div>
    <?php } elseif ($player === 'Netherlord') { ?>
        <div class="class-explanation">
            <h3>Netherlord</h3>
            <p><strong>Role:</strong> Supreme ruler of the underworld.</p>
            <p><strong>Traits:</strong> Souls Collected, Realm Power, Minion Count.</p>
            <p><strong>OOP Concepts:</strong></p>
            <ul>
                <li><strong>Inheritance:</strong> Extends Supernatural abstract class.</li>
                <li><strong>Interface:</strong> Implements FlyandChange.</li>
                <li><strong>Trait:</strong> Uses Logger for battle logging.</li>
                <li><strong>Encapsulation:</strong> Private properties with controlled getter/setter access.</li>
            </ul>
            <p><strong>Abilities:</strong></p>
            <ul>
                <li><strong>collectSoul($prey):</strong> +1 soul, deals 15-25 damage, boosts realm power.</li>
                <li><strong>netherStorm($prey):</strong> Costs realm power, deals 10-20 damage.</li>
                <li><strong>attack($prey):</strong> Minions attack with bonus damage per minion.</li>
                <li><strong>fly/spawn/teleport/morph:</strong> 30% chance to alter prey mood.</li>
            </ul>
        </div>
    <?php } elseif ($player === 'Angel') { ?>
        <div class="class-explanation">
            <h3>Angel</h3>
            <p><strong>Role:</strong> Divine guardian of the light.</p>
            <p><strong>Traits:</strong> Divinity Level, Aura.</p>
            <p><strong>OOP Concepts:</strong></p>
            <ul>
                <li><strong>Inheritance:</strong> Extends Supernatural abstract class.</li>
                <li><strong>Interface:</strong> Implements FlyandChange.</li>
                <li><strong>Trait:</strong> Uses Logger for battle logging.</li>
                <li><strong>Encapsulation:</strong> Private properties (divinityLevel, aura) with public getters/setters.</li>
            </ul>
            <p><strong>Abilities:</strong></p>
            <ul>
                <li><strong>healPrey($prey, $healAmount):</strong> 20% chance to redeem prey (health = 0).</li>
                <li><strong>blessPrey($prey, $blessing):</strong> Deals 3-8 damage, +10 divinity.</li>
                <li><strong>attack($prey):</strong> Deals 5-15 damage, slightly raises divinity.</li>
                <li><strong>fly/spawn/teleport/morph:</strong> Soar, cast guardians, teleport.</li>
            </ul>
        </div>
    <?php } elseif ($player === 'Seraphim') { ?>
        <div class="class-explanation">
            <h3>Seraphim</h3>
            <p><strong>Role:</strong> Highest celestial being with many wings.</p>
            <p><strong>Traits:</strong> Divinity Level, Wings Count.</p>
            <p><strong>OOP Concepts:</strong></p>
            <ul>
                <li><strong>Multi-level Inheritance:</strong> Extends Angel → Supernatural.</li>
                <li><strong>Polymorphism:</strong> Overrides parent methods (heal(), bless()).</li>
                <li><strong>Trait:</strong> Uses Logger for battle logging.</li>
                <li><strong>Encapsulation:</strong> Private properties (wingsCount, flyCount) with getters/setters.</li>
            </ul>
            <p><strong>Abilities:</strong></p>
            <ul>
                <li><strong>revealGlory($prey):</strong> 30% chance to instantly reduce prey health to 0.</li>
                <li><strong>heal():</strong> Restores 10-20 health, costs divinity.</li>
                <li><strong>bless():</strong> Enhances skill, drains divinity.</li>
                <li><strong>fly():</strong> 3+ flights = 30% risk of flying too close to the sun (instant death).</li>
            </ul>
        </div>
    <?php } elseif ($player === 'Werewolf') { ?>
        <div class="class-explanation">
            <h3>Werewolf</h3>
            <p><strong>Role:</strong> Ferocious beast of the moon.</p>
            <p><strong>Traits:</strong> Ferocity.</p>
            <p><strong>OOP Concepts:</strong></p>
            <ul>
                <li><strong>Inheritance:</strong> Extends Supernatural abstract class.</li>
                <li><strong>Interface:</strong> Implements Nocturnal.</li>
                <li><strong>Trait:</strong> Uses Logger for battle logging.</li>
                <li><strong>Encapsulation:</strong> Private ferocity property with public getter/setter.</li>
            </ul>
            <p><strong>Abilities:</strong></p>
            <ul>
                <li><strong>chase($prey):</strong> Costs 3-8 health, 15-20% chance to capture prey.</li>
                <li><strong>pounce($prey):</strong> Costs 5-12 health, high-intensity ambush.</li>
                <li><strong>attack($prey):</strong> Deals 8-18 damage, increases ferocity.</li>
                <li><strong>monitorMoonStatus():</strong> Increases ferocity by 2-8 points.</li>
            </ul>
        </div>
    <?php } ?>

    <!-- ---- PREY EXPLANATION ---- -->
    <div class="class-explanation">
        <h3>Prey</h3>
        <p><strong>Role:</strong> Human victim that creatures hunt.</p>
        <p><strong>Traits:</strong> Name, Age, Vitality (Health), Mood.</p>
        <p><strong>OOP Concepts:</strong></p>
        <ul>
            <li><strong>Encapsulation:</strong> Private properties (preyName, preyAge, preyHealth, preyMood) with public getters/setters.</li>
            <li><strong>Trait:</strong> Uses Logger for battle logging.</li>
        </ul>
        <p><strong>Abilities:</strong></p>
        <ul>
            <li><strong>attack($target):</strong> Counter-attacks when player attacks. Damage scales based on prey mood.</li>
            <li><strong>Mood Scaling:</strong></li>
            <ul>
                <li><strong>Light / Good / Unreadable:</strong> 5-15 damage</li>
                <li><strong>Moderate:</strong> 10-19 damage</li>
                <li><strong>Aggressive:</strong> 20-30 damage</li>
                <li><strong>Violent:</strong> 30-50 damage (Netherlord only)</li>
                <li><strong>Scared / Startled / Mortified:</strong> 2-8 damage</li>
                <li><strong>Neutral (vs Netherlord):</strong> Watches warily (0 damage)</li>
                <li><strong>Neutral (vs others):</strong> Cautiously defends (3-8 damage)</li>
            </ul>
        </ul>
    </div>
</div>

<div class="battlefield-mechanics">

    <h1>Detailed Battlefield Mechanics and Object Interaction Flow</h1>

    <p>
        The battlefield operates as a dynamic, session-driven state machine where
        player actions trigger a continuous loop of execution, state modification,
        counter-attacks, and persistence. When a user interacts with
        <code>battlefield.php</code>, the following sequence of events takes place:
    </p>

    <ol>
        <li>
            <strong>State Rehydration:</strong>
            The script reads the serialized properties stored in
            <code>$_SESSION</code> during the setup phase and instantiates the
            matching concrete class (e.g., <code>Fiend</code>,
            <code>Vampire</code>, <code>Werewolf</code>, <code>Angel</code>,
            <code>Seraphim</code>, or <code>Netherlord</code>). This ensures that
            object-oriented methods and encapsulated behaviors are immediately available.
        </li>

        <li>
            <strong>Dynamic Prey Generation:</strong>
            If no prey session exists, a new <code>Prey</code> object is created.
            Its health scaling and emotional moods (aggressive, moderate, scared,
            violent, etc.) are randomized to introduce tactical variance into the encounter.
        </li>

        <li>
            <strong>Action Dispatching &amp; Polymorphism:</strong>
            When a user clicks an action button (such as Fly, Bite, Chase, or Heal),
            a POST request submits the action type back to
            <code>battlefield.php</code>. The script checks interface implementation
            or class type-hinting (e.g.,
            <code>$creature instanceof FlyandChange</code> or
            <code>$creature instanceof Nocturnal</code>) to safely execute the
            corresponding method.


            <ul>
                <li>
                    <strong>Vampire/Werewolf Actions:</strong>
                    Invoking <code>goOutside()</code> or
                    <code>monitorMoonStatus()</code> updates nocturnal statistics
                    and adjusts ferocity or mood metrics.
                </li>

                <li>
                    <strong>Offensive Strikes:</strong>
                    Actions like <code>attack()</code>,
                    <code>biteAndDrain()</code>, or <code>pounce()</code>
                    calculate damage using the creature's unique traits
                    (such as <code>biteForce</code> or <code>ferocity</code>)
                    and subtract it from the <code>Prey</code> object's health.
                </li>
            </ul>
        </li>

        <li>
            <strong>Prey Retaliation Loop:</strong>
            Immediately after the creature executes its offensive move, if the
            prey's health remains above zero, the <code>Prey</code> object fires
            back via its own <code>attack($target)</code> method. The damage
            dealt to the player's health level is dynamically calculated based
            on the prey's current mood state.
        </li>

        <li>
            <strong>Logging and Persistence:</strong>
            Every action message is pushed into the battle log using the
            Logger trait (<code>$creature-&gt;battleLog($msg)</code>), which
            serializes the logs into <code>$_SESSION['battlelog']</code>.
            All mutated object properties (health, skills, corruption,
            ferocity, divinity) are written back into <code>$_SESSION</code> variables.
        </li>

        <li>
            <strong>Redirect and Render:</strong>
            A
            <code>header('Location: battlefield.php')</code>
            redirect clears out the POST payload to prevent duplicate submissions,
            refreshing the interface to display updated stats, log entries, and
            UI buttons. If either combatant's health drops to zero or below,
            a modal overlay triggers to declare the victor and prompt a session
            reset via <code>reset.php</code>.
        </li>
    </ol>

    <h2>Creature Instantiation Mechanics in <code>prepare.php</code></h2>

    <p>
        The <code>prepare.php</code> script acts as the foundational bridge between
        user selection and runtime instantiation. It handles both
        <strong>preset object instantiations</strong> (pre-configured instances
        hardcoded inside the class files) and
        <strong>custom object instantiations</strong> (dynamically built using
        user-submitted form parameters).
    </p>

    <h3>Preset Instantiation</h3>

    <p>
        Each creature file (e.g., <code>Angel.php</code>,
        <code>Vampire.php</code>, <code>Werewolf.php</code>) defines arrays of
        pre-built objects like <code>$youngAngel</code>,
        <code>$defaultAngel</code>, or <code>$ancientVampire</code>.
        When a user clicks a preset card, <code>prepare.php</code> reads the
        corresponding index from the collection array, extracting the
        pre-instantiated object directly:
    </p>

    <pre><code>if (isset($_POST['presetIndex']) &amp;&amp; isset($vampireCollection[(int)$_POST['presetIndex']])) {
    $creature = $vampireCollection[(int)$_POST['presetIndex']];
}</code></pre>

    <h3>Custom Instantiation</h3>

    <p>
        If the user bypasses presets and fills out the HTML form,
        <code>prepare.php</code> captures the raw POST inputs, casts them to
        appropriate data types (floats, integers, strings), and passes them
        dynamically into a new <code>ClassName(...)</code> constructor call:
    </p>

    <pre><code>$creature = new Vampire(
    $_POST['name'] ?? '',
    (int)($_POST['age'] ?? 0),
    (int)($_POST['humanEncounters'] ?? 0),
    $_POST['ability'] ?? '',
    (float)($_POST['skillLevel'] ?? 0),
    (float)($_POST['healthLevel'] ?? 0),
    (float)($_POST['bloodDrank'] ?? 0.0),
    (float)($_POST['biteForce'] ?? 75.5)
);</code></pre>

    <h3>Session Mapping</h3>

    <p>
        Once the creature object is successfully instantiated through either
        method, <code>prepare.php</code> extracts every private/protected
        attribute using getter methods (e.g.,
        <code>$creature-&gt;getName()</code> and
        <code>$creature-&gt;getBiteForce()</code>) and registers them into the
        <code>$_SESSION</code> superglobal before routing the user to
        <code>battlefield.php</code>.
    </p>

</div>



</body>
</html>