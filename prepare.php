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
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
        crumbling gothic ruins and encroaching shadows—the ultimate arena for the creatures of the night. Choose a preset creature or create your own.
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

</body>
</html>