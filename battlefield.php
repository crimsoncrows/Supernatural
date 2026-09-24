<?php
// =============================================
// 1. REQUIRE DEPENDENCIES
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
// BONUS ATTACK SETTINGS
// =============================================

const BONUS_ATTACK_CHANCE_PER_100 = 50;

// Actions that don't already put the creature and prey in direct combat
const NON_PREY_ACTIONS = [
        'fly', 'spawn', 'teleport', 'morph',
        'goOutside', 'monitorMoon', 'lurk',
        'roam', 'perform', 'deal'
];

// =============================================
// 2. SESSION INITIALIZATION
// =============================================

session_start();

$creatureType = $_SESSION['creature_type'] ?? '';

// =============================================
// 3. CREATE CREATURE FROM SESSION DATA
// =============================================

if ($creatureType === 'Fiend') {
    $creature = new Fiend(
            $_SESSION['name'] ?? 'Lucier',
            $_SESSION['age'] ?? 0,
            $_SESSION['humanEncounters'] ?? 0,
            $_SESSION['ability'] ?? '',
            $_SESSION['skillLevel'] ?? 0,
            $_SESSION['healthLevel'] ?? 0,
            $_SESSION['corruptionLevel'] ?? 50.0,
            $_SESSION['pactCount'] ?? 0,
            $_SESSION['fearAura'] ?? 'Low'
    );
} elseif ($creatureType === 'Vampire') {
    $creature = new Vampire(
            $_SESSION['name'] ?? 'Dracula',
            $_SESSION['age'] ?? 0,
            $_SESSION['humanEncounters'] ?? 0,
            $_SESSION['ability'] ?? '',
            $_SESSION['skillLevel'] ?? 0,
            $_SESSION['healthLevel'] ?? 0,
            $_SESSION['bloodDrank'] ?? 0.0,
            $_SESSION['biteForce'] ?? 75.5
    );

    // Restore mood from session if it exists
    if (isset($_SESSION['mood'])) {
        $creature->setMood($_SESSION['mood']);
    }
} elseif ($creatureType === 'Netherlord') {
    $creature = new Netherlord(
            $_SESSION['name'] ?? 'Malakar',
            $_SESSION['age'] ?? 0,
            $_SESSION['humanEncounters'] ?? 0,
            $_SESSION['ability'] ?? '',
            $_SESSION['skillLevel'] ?? 0,
            $_SESSION['healthLevel'] ?? 0,
            $_SESSION['soulsCollected'] ?? 0,
            $_SESSION['realmPower'] ?? 30.0,
            $_SESSION['minionCount'] ?? 0
    );
} elseif ($creatureType === 'Angel') {
    $creature = new Angel(
            $_SESSION['name'] ?? 'Gabriel',
            $_SESSION['age'] ?? 0,
            $_SESSION['humanEncounters'] ?? 0,
            $_SESSION['ability'] ?? '',
            $_SESSION['skillLevel'] ?? 0,
            $_SESSION['healthLevel'] ?? 0,
            $_SESSION['divinityLevel'] ?? 100.0,
            $_SESSION['aura'] ?? 'Radiant'
    );
} elseif ($creatureType === 'Seraphim') {
    $creature = new Seraphim(
            $_SESSION['name'] ?? 'Seraphiel',
            $_SESSION['age'] ?? 0,
            $_SESSION['humanEncounters'] ?? 0,
            $_SESSION['ability'] ?? '',
            $_SESSION['skillLevel'] ?? 0,
            $_SESSION['healthLevel'] ?? 0,
            $_SESSION['divinityLevel'] ?? 50.0,
            $_SESSION['wingsCount'] ?? 6
    );
} elseif ($creatureType === 'Werewolf') {
    $creature = new Werewolf(
            $_SESSION['name'] ?? 'Lupus',
            $_SESSION['age'] ?? 0,
            $_SESSION['humanEncounters'] ?? 0,
            $_SESSION['ability'] ?? '',
            $_SESSION['skillLevel'] ?? 0,
            $_SESSION['healthLevel'] ?? 0,
            $_SESSION['ferocity'] ?? 50.0
    );
}

// =============================================
// 4. PREY GENERATION
// =============================================

if (!isset($_SESSION['prey_name'])) {
    $preyNames = [
        // ORIGINAL NAMES
            'Lucian', 'Mira', 'Osric', 'Thalia', 'Bram', 'Ysolde', 'Corwin', 'Neve',
            'Silas', 'Elara', 'Gideon', 'Vesper', 'Malachi', 'Rowena', 'Alistair',
            'Lenore', 'Dorian', 'Maeve', 'Thorne', 'Seraphina', 'Lucius', 'Sibyl',
            'Caius', 'Genevieve', 'Lorcan', 'Lyra', 'Magnus', 'Carmilla', 'Orion',
            'Ravenna', 'Ronan', 'Odelia', 'Valerius', 'Nyx', 'Ewan', 'Elysia',

        // GOTHIC/VICTORIAN NAMES
            'Ambrose', 'Belladonna', 'Cassian', 'Drusilla', 'Elias', 'Fenella',
            'Gareth', 'Hecate', 'Isolde', 'Jasper', 'Killian', 'Lilith', 'Morrigan',
            'Nero', 'Ophelia', 'Percival', 'Quinn', 'Raphael', 'Sabine', 'Theron',
            'Ursula', 'Valentine', 'Wren', 'Xander', 'Yvaine', 'Zephyr',

        // MEDIEVAL/NOBLE NAMES
            'Aldric', 'Beatrice', 'Cedric', 'Diana', 'Edmund', 'Fiona', 'Godfrey',
            'Helena', 'Ivan', 'Juliana', 'Kenneth', 'Lydia', 'Matthias', 'Naomi',
            'Oswin', 'Petra', 'Roland', 'Selene', 'Tobias', 'Ursa', 'Victor',
            'Wilhelmina', 'Xavier', 'Yvette', 'Zachary', 'Aeliana', 'Benedict',
            'Constance', 'Dominic', 'Eleanor', 'Frederick', 'Gwendolyn', 'Hubert',
            'Isabella', 'Jerome', 'Katherine', 'Lawrence', 'Marcella', 'Nicholas',
            'Odette', 'Patrick', 'Quentin', 'Regina', 'Stephen', 'Theresa',
            'Urban', 'Valeria', 'Walter', 'Ximena', 'Yarrow', 'Zara',

        // MYTHOLOGICAL/MYSTICAL NAMES

            'Aetherius', 'Boreas', 'Calypso', 'Delphine', 'Ephraim', 'Faustus',
            'Gaia', 'Hermione', 'Icarus', 'Jareth', 'Kismet', 'Lucienne',
            'Merlin', 'Nebula', 'Orpheus', 'Pandora', 'Quicksilver', 'Raven',
            'Sirius', 'Tempest', 'Ulysses', 'Valkyrie', 'Wisteria', 'Xerxes',
            'Yggdrasil', 'Zoltan', 'Amarantha', 'Briseis', 'Cassiopeia',
            'Dionysus', 'Erebus', 'Fortuna', 'Gryphon', 'Hestia', 'Iris',
            'Janus', 'Kallisto', 'Leto', 'Minerva', 'Nemesis', 'Olympia',
            'Perseus', 'Rhiannon', 'Styx', 'Themis', 'Urania', 'Vesta',
            'Wotan', 'Xochitl', 'Yara', 'Zephyrine',

        // DARK FANTASY VILLAGER NAMES
            'Arwen', 'Bran', 'Cora', 'Damon', 'Eira', 'Finn', 'Greta',
            'Hugo', 'Ingrid', 'Jorah', 'Kara', 'Leif', 'Maren', 'Nils',
            'Oona', 'Piper', 'Runa', 'Soren', 'Tova', 'Ulric', 'Vera',
            'Willow', 'Ylva', 'Astrid', 'Bjorn', 'Einar', 'Freya', 'Gunnar',
            'Hilda', 'Ivar', 'Jens', 'Katla', 'Lars', 'Mikael', 'Nora',
            'Odd', 'Ragnar', 'Sigrid', 'Torben', 'Ulf', 'Vidar', 'Yrsa'
    ];

    $moods = ['aggressive', 'moderate', 'light', 'good', 'unreadable'];

    $_SESSION['prey_name']   = $preyNames[array_rand($preyNames)];
    if($creatureType === 'Angel' || $creatureType === 'Seraphim') {
        $_SESSION['prey_age']    = rand(100, 200);
    }
    else{
        $_SESSION['prey_age']    = rand(18, 80);
    }
    if ( $_SESSION['prey_age'] > 60) {
        if ( $_SESSION['prey_age'] <= 80) {
            $_SESSION['prey_health']  = rand(60, 80);
        } elseif ( $_SESSION['prey_age'] <= 100) {
            $_SESSION['prey_health'] = rand(40, 60);
        } else {
            $_SESSION['prey_health'] = rand(75, 100);
        }
    } elseif ( $_SESSION['prey_age'] > 40) {
        $_SESSION['prey_health'] = rand(80, 95);
    } else {
        $_SESSION['prey_health'] = rand(90, 100);
    }
    $_SESSION['prey_mood']   = $moods[array_rand($moods)];
}

// =============================================
// 5. CREATE PREY OBJECT
// =============================================

$prey = new Prey(
        $_SESSION['prey_name'],
        $_SESSION['prey_age'],
        $_SESSION['prey_health'],
        $_SESSION['prey_mood']
);

// =============================================
// 6. HANDLE POST ACTIONS (COMBAT)
// =============================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $logEntry = '';
    $action = $_POST['action'];

    // ---- SUPERNAUTRAL ACTIONS ----
    if ($creature instanceof Supernatural) {
        if ($action === 'perform') {
            $logEntry = $creature->performSkill();
        } elseif ($action === 'roam') {
            $logEntry = $creature->goForExpedition();
        }
    }

    // ---- FLYANDCHANGE ACTIONS ----
    if ($creature instanceof FlyandChange) {
        if ($action === 'fly') {
            $logEntry = $creature->fly();
        } elseif ($action === 'spawn') {
            $logEntry = $creature->spawn();
        } elseif ($action === 'teleport') {
            $logEntry = $creature->teleport();
        } elseif ($action === 'morph') {
            $logEntry = $creature->morph();
        } elseif ($action === 'attack') {
            $logEntry = $creature->attack($prey);
        }
    }

    // ---- NOCTURNAL ACTIONS ----
    if ($creature instanceof Nocturnal) {
        if ($action === 'goOutside') {
            $logEntry = $creature->goOutside();
        } elseif ($action === 'monitorMoon') {
            $logEntry = $creature->monitorMoonStatus();
        } elseif ($action === 'lurk') {
            $logEntry = $creature->lurkInTheDark();
        }
    }

    // ---- FIEND-SPECIFIC ACTIONS ----
    if ($creature instanceof Fiend) {
        if ($action === 'eat') {
            $logEntry = $creature->drainSoul($prey);
        } elseif ($action === 'deal') {
            $logEntry = $creature->createPact();
        }
    }

    // ---- VAMPIRE-SPECIFIC ACTIONS ----
    if ($creature instanceof Vampire) {
        if ($action === 'bite') {
            $logEntry = $creature->attackAndBite($prey);
        } elseif ($action === 'drain') {
            $logEntry = $creature->attackAndBite($prey, 20.0);
        }
    }

    // ---- NETHERLORD-SPECIFIC ACTIONS ----
    if ($creature instanceof Netherlord) {
        if ($action === 'collectSoul') {
            $logEntry = $creature->collectSoul($prey);
        } elseif ($action === 'netherStorm') {
            $logEntry = $creature->netherStorm($prey);
        }
    }

    // ---- ANGEL-SPECIFIC ACTIONS ----
    if ($creature instanceof Angel) {
        if ($action === 'healPrey') {
            $healAmount = rand(10, 25);
            $logEntry = $creature->healPrey($prey, $healAmount);
        } elseif ($action === 'blessPrey') {
            $blessing = 'Divine Protection';
            $logEntry = $creature->blessPrey($prey, $blessing);
        }
    }

    // ---- SERAPHIM-SPECIFIC ACTIONS ----
    if ($creature instanceof Seraphim) {
        if ($action === 'revealGlory') {
            $logEntry = $creature->revealGlory($prey);
        } elseif ($action === 'heal') {
            $logEntry = $creature->heal();
        } elseif ($action === 'bless') {
            $logEntry = $creature->bless();
        }
    }

    // ---- WEREWOLF-SPECIFIC ACTIONS ----
    if ($creature instanceof Werewolf) {
        if ($action === 'chase') {
            $logEntry = $creature->chase($prey);
        } elseif ($action === 'pounce') {
            $logEntry = $creature->pounce($prey);
        } elseif ($action === 'attack') {
            $logEntry = $creature->attack($prey);
        }
    }

    // 1. LOG PREDATOR MOVE FIRST
    if ($logEntry !== '') {
        $creature->battleLog($logEntry);
    }

    // 2. PREY ATTACKS BACK LOGIC
    if (isset($prey) && $prey instanceof Prey && $prey->getPreyHealth() > 0) {
        $shouldPreyAttack = false;

        // If the predator did a non-attack action, 50% chance the prey takes a swing anyway
        if (in_array($action, NON_PREY_ACTIONS, true)) {
            if (rand(1, 100) <= BONUS_ATTACK_CHANCE_PER_100) {
                $shouldPreyAttack = true;
            }
        }
        // If it was a combat/attack action, prey always counterattacks
        // (excluding angelic non-damage actions like healing)
        elseif (!in_array($action, ['healPrey', 'blessPrey', 'revealGlory', 'heal', 'bless'], true)) {
            $shouldPreyAttack = true;
        }

        // Execute and log prey attack second
        if ($shouldPreyAttack) {
            $prey->setPreyMood($_SESSION['prey_mood']);
            $preyLogEntry = $prey->attack($creature);
            $prey->battleLog($preyLogEntry);
        }
    }

    // ---- UPDATE SESSION DATA ----
    $_SESSION['healthLevel'] = $creature->getHealthLevel();
    $_SESSION['skillLevel']  = $creature->getSkillLevel();
    $_SESSION['prey_health'] = $prey->getPreyHealth();
    $_SESSION['prey_mood']   = $prey->getPreyMood();
    $_SESSION['humanEncounters'] = $creature->getHumanEncounters();

    // ---- UPDATE FIEND-SPECIFIC SESSION DATA ----
    if ($creature instanceof Fiend) {
        $_SESSION['corruptionLevel'] = $creature->getCorruptionLevel();
        $_SESSION['pactCount']       = $creature->getPactCount();
        $_SESSION['fearAura']        = $creature->getFearAura();
    }

    // ---- UPDATE VAMPIRE-SPECIFIC SESSION DATA ----
    if ($creature instanceof Vampire) {
        $_SESSION['bloodDrank'] = $creature->getBloodDrank();
        $_SESSION['biteForce']  = $creature->getBiteForce();
        $_SESSION['mood']       = $creature->getMood();
    }

    // ---- UPDATE NETHERLORD-SPECIFIC SESSION DATA ----
    if ($creature instanceof Netherlord) {
        $_SESSION['soulsCollected'] = $creature->getSoulsCollected();
        $_SESSION['realmPower']     = $creature->getRealmPower();
        $_SESSION['minionCount']    = $creature->getMinionCount();
    }

    // ---- UPDATE ANGEL-SPECIFIC SESSION DATA ----
    if ($creature instanceof Angel) {
        $_SESSION['divinityLevel'] = $creature->getDivinityLevel();
        $_SESSION['aura']          = $creature->getAura();
    }

    // ---- UPDATE SERAPHIM-SPECIFIC SESSION DATA ----
    if ($creature instanceof Seraphim) {
        $_SESSION['divinityLevel'] = $creature->getDivinityLevel();
        $_SESSION['wingsCount']    = $creature->getWingsCount();
    }

    // ---- UPDATE WEREWOLF-SPECIFIC SESSION DATA ----
    if ($creature instanceof Werewolf) {
        $_SESSION['ferocity'] = $creature->getFerocity();
    }

    // ---- REDIRECT TO REFRESH PAGE ----
    header('Location: battlefield.php');
    exit;
}

// =============================================
// 7. INITIALIZE BATTLE LOG (if not exists)
// =============================================

if (!isset($_SESSION['battlelog'])) {
    $_SESSION['battlelog'] = [];
}

?>

<!-- ============================================= -->
<!-- 8. HTML STRUCTURE - BATTLEFIELD PAGE -->
<!-- ============================================= -->

<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/png" href="https://png.pngtree.com/png-clipart/20250123/original/pngtree-blood-moon-png-image_20325627.png">
    <link rel="stylesheet" href="style.css">
    <title>php practice by crimsoncrows</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:ital,wght@0,300;0,400;0,500;1,300;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caudex:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
</head>
<body>

<?php if ($prey== null || $creature== null ): ?>
    <div class="modal-overlay">
        <div class="game-over-box">

            <!-- LEFT SIDE -->
            <div class="game-over-result">
                <h2>SOMETHING'S MISSING</h2>

                <p class="result-message">
                    <?="A battle isn't ready yet..." ?>
                </p>

                <form method="POST" action="reset.php">
                    <button type="submit" class="method-btn">
                        Go Prepare
                    </button>
                </form>
            </div>

            <!-- RIGHT SIDE -->
            <div class="game-over-history">
                <h3>Battle History</h3>

                <div class="history-entries">

                </div>
            </div>

        </div>
    </div>
<?php endif; ?>

<!-- ============================================= -->
<!-- 9. PLAYER STATUS BAR -->
<!-- ============================================= -->

<div class="stat-bar">
    <div class="player-header">
        <?php
        if ($creatureType  === 'Fiend') {
            $icon = 'https://i.pinimg.com/736x/59/97/12/5997129b7e8bd6002e8fd7abad512d71.jpg';
        } elseif ($creatureType === 'Werewolf') {
            $icon = 'https://i.pinimg.com/1200x/56/76/bc/5676bc5cf5acbcdff029e9068195b3da.jpg';
        } elseif ($creatureType  === 'Seraphim') {
            $icon = 'https://i.pinimg.com/736x/0f/a2/fd/0fa2fd6ad0dd1fffb41a5238c93106dd.jpg';
        } elseif ($creatureType === 'Angel') {
            $icon = 'https://i.pinimg.com/736x/3d/49/bb/3d49bbe85d5e193ca52db3af036e401c.jpg';
        } elseif ($creatureType  === 'Vampire') {
            $icon = 'https://i.pinimg.com/1200x/aa/87/74/aa8774fccc376899c181c4371476bcea.jpg';
        } elseif ($creatureType  === 'Netherlord') {
            $icon = 'https://i.pinimg.com/1200x/b7/4a/e7/b74ae776972df4b7bbbd90ce1da90ec1.jpg';
        } else {
            $icon = 'https://i.pinimg.com/736x/ea/b8/bd/eab8bd92d52d593e5a92f0d6bef65a23.jpg';
        }
        ?>
        <img src="<?= htmlspecialchars($icon) ?>" alt="Player Portrait" class="player-pic">
        <h2><?= $creature->getName() ?>'s Status</h2>
        <h2><?= " | " . $creature->getAbility() ?></h2>

    </div>

    <!-- BATTLE PROGRESS BARS -->
    <div class="battle-progress">
        <div class="health-wrapper predator-health">
            <div class="health-label"><?= $creature->getName() ?> <span>(<?= $creature->getHealthLevel() ?> HP)</span></div>
            <div class="health-bar-bg">
                <div class="health-fill" style="width: <?= min(100, max(0, $creature->getHealthLevel())) ?>%;"></div>
            </div>
        </div>

        <div class="vs-text">VS</div>

        <div class="health-wrapper prey-health">
            <div class="health-label"><?= $prey->getPreyName() ?> <span>(<?= $prey->getPreyHealth() ?> HP)</span></div>
            <div class="health-bar-bg">
                <div class="health-fill" style="width: <?= min(100, max(0, $prey->getPreyHealth())) ?>%;"></div>
            </div>
        </div>
    </div>

    <div class="creature-info">
        <h3>Age:</h3>
        <span><?= $creature->getAge() ?></span>
    </div>

    <div class="creature-info">
        <h3>Human Encounters:</h3>
        <span><?= $creature->getHumanEncounters() ?></span>
    </div>

    <div class="creature-info">
        <h3>Ability:</h3>
        <span><?= htmlspecialchars($creature->getAbility()) ?></span>
    </div>

    <div class="creature-info">
        <h3>Skill Level:</h3>
        <span><?= $creature->getSkillLevel() ?></span>
    </div>

    <div class="creature-info">
        <h3>Health Level:</h3>
        <span><?= $creature->getHealthLevel() ?></span>
    </div>

    <!-- ---- FIEND TRAITS ---- -->
    <?php if ($creature instanceof Fiend) { ?>
        <div class="creature-info">
            <h3>Corruption Level:</h3>
            <span><?= $creature->getCorruptionLevel() ?></span>
        </div>

        <div class="creature-info">
            <h3>Pact Count:</h3>
            <span><?= $creature->getPactCount() ?></span>
        </div>

        <div class="creature-info">
            <h3>Fear Aura:</h3>
            <span><?= $creature->getFearAura() ?></span>
        </div>
    <?php } ?>

    <!-- ---- VAMPIRE TRAITS ---- -->
    <?php if ($creature instanceof Vampire) { ?>
        <div class="creature-info">
            <h3>Blood Drank:</h3>
            <span><?= $creature->getBloodDrank() ?></span>
        </div>

        <div class="creature-info">
            <h3>Bite Force:</h3>
            <span><?= $creature->getBiteForce() ?></span>
        </div>

        <div class="creature-info">
            <h3>Mood:</h3>
            <span><?= $creature->getMood() ?></span>
        </div>
    <?php } ?>

    <!-- ---- NETHERLORD TRAITS ---- -->
    <?php if ($creature instanceof Netherlord) { ?>
        <div class="creature-info">
            <h3>Souls Collected:</h3>
            <span><?= $creature->getSoulsCollected() ?></span>
        </div>

        <div class="creature-info">
            <h3>Realm Power:</h3>
            <span><?= $creature->getRealmPower() ?></span>
        </div>

        <div class="creature-info">
            <h3>Minion Count:</h3>
            <span><?= $creature->getMinionCount() ?></span>
        </div>
    <?php } ?>

    <!-- ---- ANGEL TRAITS ---- -->
    <?php if ($creature instanceof Angel) { ?>
        <div class="creature-info">
            <h3>Divinity Level:</h3>
            <span><?= $creature->getDivinityLevel() ?></span>
        </div>

        <div class="creature-info">
            <h3>Aura:</h3>
            <span><?= $creature->getAura() ?></span>
        </div>
    <?php } ?>

    <!-- ---- SERAPHIM TRAITS ---- -->
    <?php if ($creature instanceof Seraphim) { ?>
        <div class="creature-info">
            <h3>Divinity Level:</h3>
            <span><?= $creature->getDivinityLevel() ?></span>
        </div>

        <div class="creature-info">
            <h3>Wings Count:</h3>
            <span><?= $creature->getWingsCount() ?></span>
        </div>
    <?php } ?>

    <!-- ---- WEREWOLF TRAITS ---- -->
    <?php if ($creature instanceof Werewolf) { ?>
        <div class="creature-info">
            <h3>Ferocity:</h3>
            <span><?= $creature->getFerocity() ?></span>
        </div>
    <?php } ?>

    <!-- ---- RESET BUTTON ---- -->
    <form method="POST" action="reset.php">
        <button type="submit" class="method-btn">Reset Battle</button>
    </form>
</div>

<!-- ============================================= -->
<!-- 10. BATTLEFIELD CONTAINER - LOG & PREY -->
<!-- ============================================= -->

<div class="battlefield-container">

    <!-- ---- BATTLE LOG SECTION ---- -->
    <div class="combat-log">
        <h3>Battle Log</h3>
        <div class="log-entries">
            <?php
            $totalEntries = count($_SESSION['battlelog']);
            foreach ($_SESSION['battlelog'] as $index => $entry):
                $isLast = ($index === $totalEntries - 1);
                ?>
                <p class="<?= $isLast ? 'log-delayed' : '' ?>"><?= '> > ' . htmlspecialchars($entry) ?></p>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ---- PREY SECTION ---- -->
    <div class="prey-area">
        <?php if ($creatureType === 'Angel' || $creatureType === 'Seraphim') { ?>
            <h3>Demon Caught</h3>
        <?php } else { ?>
            <h3>Prey Caught</h3>
        <?php } ?>
        <div class="prey-stats">
            <p><strong>Prey:</strong> <?= htmlspecialchars($prey->getPreyName()) ?> (Age: <?= $prey->getPreyAge() ?>)</p>
            <p><strong>Vitality:</strong> <?= $prey->getPreyHealth() ?></p>
            <p><strong>Mood:</strong> <?= $prey->getPreyMood()?></p>
        </div>
    </div>

    <!-- ---- CREATURE ACTION BUTTONS ---- -->
    <div class="creature-methods">
        <!-- ---- FLYANDCHANGE ACTIONS (Fiend, Netherlord, Angel, Seraphim, etc.) ---- -->
        <?php if ($creature instanceof FlyandChange) { ?>
            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="fly">
                <button type="submit" class="method-btn">Fly</button>
            </form>

            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="spawn">
                <button type="submit" class="method-btn">Spawn</button>
            </form>

            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="teleport">
                <button type="submit" class="method-btn">Teleport</button>
            </form>

            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="morph">
                <button type="submit" class="method-btn">Morph</button>
            </form>

            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="attack">
                <button type="submit" class="method-btn">Attack</button>
            </form>
        <?php } ?>

        <!-- ---- NOCTURNAL ACTIONS (Vampire, Werewolf, etc.) ---- -->
        <?php if ($creature instanceof Nocturnal) { ?>
            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="goOutside">
                <button type="submit" class="method-btn">Go Outside</button>
            </form>

            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="monitorMoon">
                <button type="submit" class="method-btn">Monitor Moon</button>
            </form>

            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="lurk">
                <button type="submit" class="method-btn">Lurk in Dark</button>
            </form>
        <?php } ?>

        <!-- ---- FIEND-SPECIFIC ACTIONS (CONDITIONAL) ---- -->
        <?php if ($creature instanceof Fiend) {
            // Eat Soul: Only when health <= 20 AND skill >= 80
            if ($creature->getHealthLevel() <= 20 && $creature->getSkillLevel() >= 80) { ?>
                <form method="POST" action="battlefield.php">
                    <input type="hidden" name="action" value="eat">
                    <button type="submit" class="method-btn">Drain Soul</button>
                </form>
            <?php }

            // Make Pact: Only when health <= 90 AND prey mood is "good"
            if ($creature->getHealthLevel() <= 90 && $prey->getPreyMood() == "good") { ?>
                <form method="POST" action="battlefield.php">
                    <input type="hidden" name="action" value="deal">
                    <button type="submit" class="method-btn">Make Pact</button>
                </form>
            <?php }
        } ?>

        <!-- ---- VAMPIRE-SPECIFIC ACTIONS ---- -->
        <?php if ($creature instanceof Vampire) { ?>
            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="bite">
                <button type="submit" class="method-btn">Bite</button>
            </form>

            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="drain">
                <button type="submit" class="method-btn">Drain Blood</button>
            </form>
        <?php } ?>

        <!-- ---- NETHERLORD-SPECIFIC ACTIONS ---- -->
        <?php if ($creature instanceof Netherlord) { ?>
            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="collectSoul">
                <button type="submit" class="method-btn">Collect Soul</button>
            </form>

            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="netherStorm">
                <button type="submit" class="method-btn">Nether Storm</button>
            </form>
        <?php } ?>

        <!-- ---- ANGEL-SPECIFIC ACTIONS ---- -->
        <?php if ($creature instanceof Angel) { ?>
            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="healPrey">
                <button type="submit" class="method-btn">Heal Prey</button>
            </form>

            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="blessPrey">
                <button type="submit" class="method-btn">Bless Prey</button>
            </form>
        <?php } ?>

        <!-- ---- SERAPHIM-SPECIFIC ACTIONS ---- -->
        <?php if ($creature instanceof Seraphim) { ?>
            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="revealGlory">
                <button type="submit" class="method-btn">Reveal Glory</button>
            </form>

            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="heal">
                <button type="submit" class="method-btn">Heal</button>
            </form>

            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="bless">
                <button type="submit" class="method-btn">Bless</button>
            </form>
        <?php } ?>

        <!-- ---- WEREWOLF-SPECIFIC ACTIONS ---- -->
        <?php if ($creature instanceof Werewolf) { ?>
            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="chase">
                <button type="submit" class="method-btn">Chase</button>
            </form>

            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="pounce">
                <button type="submit" class="method-btn">Pounce</button>
            </form>

            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="attack">
                <button type="submit" class="method-btn">Attack</button>
            </form>
        <?php } ?>

        <!-- Roam button for all Supernatural creatures -->
        <?php if ($creature instanceof Supernatural) { ?>
            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="roam">
                <button type="submit" class="method-btn">Roam</button>
            </form>
        <?php } ?>

        <!-- Perform Skill button for all Supernatural creatures -->
        <?php if ($creature instanceof Supernatural) { ?>
            <form method="POST" action="battlefield.php">
                <input type="hidden" name="action" value="perform">
                <button type="submit" class="method-btn">Perform Skill</button>
            </form>
        <?php } ?>
    </div>

    <!-- ============================================= -->
    <!-- 11. BATTLE RESULT MODAL -->
    <!-- ============================================= -->

    <?php
    $healthLeftPrey = $prey->getPreyHealth();
    $healthLeftPredator = $creature->getHealthLevel();

    if ($healthLeftPredator <= 0 && $healthLeftPrey <= 0) {
        $resultMessage = "Both fighters collapse... it's a draw.";
    } elseif ($healthLeftPredator <= 0) {
        $resultMessage = $prey->getPreyName() . " has survived! " . $creature->getName() . " has fallen.";

    } elseif ($healthLeftPrey <= 0) {
        $resultMessage = $creature->getName() . " emerges victorious over " . $prey->getPreyName() . "!";
    } else {
        $resultMessage = null;
    }
    ?>

    <?php if ($resultMessage !== null): ?>
        <div class="modal-overlay">
            <div class="game-over-box">

                <!-- LEFT SIDE -->
                <div class="game-over-result">
                    <h2>BATTLE OVER</h2>

                    <p class="result-message">
                        <?= htmlspecialchars($resultMessage) ?>
                    </p>

                    <form method="POST" action="reset.php">
                        <button type="submit" class="method-btn">
                            Play Again
                        </button>
                    </form>
                </div>

                <!-- RIGHT SIDE -->
                <div class="game-over-history">
                    <h3>Battle History</h3>

                    <div class="battle-progress" style="margin-bottom:30px;">
                        <div class="health-wrapper predator-health">
                            <div class="health-label"><?= $creature->getName() ?> <span>(<?= $creature->getHealthLevel() ?> HP)</span></div>
                            <div class="health-bar-bg">
                                <div class="health-fill" style="width: <?= min(100, max(0, $creature->getHealthLevel())) ?>%;"></div>
                            </div>
                        </div>

                        <div class="vs-text">VS</div>

                        <div class="health-wrapper prey-health">
                            <div class="health-label"><?= $prey->getPreyName() ?> <span>(<?= $prey->getPreyHealth() ?> HP)</span></div>
                            <div class="health-bar-bg">
                                <div class="health-fill" style="width: <?= min(100, max(0, $prey->getPreyHealth())) ?>%;"></div>
                            </div>
                        </div>
                    </div>


                    <div class="history-entries">
                        <?php foreach ($_SESSION['battlelog'] as $entry): ?>
                            <p><?= '> > ' . htmlspecialchars($entry) ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>
    <?php endif; ?>

    <!-- ============================================= -->
    <!-- 12. JAVASCRIPT - AUTO-SCROLL BATTLE LOG -->
    <!-- ============================================= -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const logContainer = document.querySelector('.log-entries');
            if (logContainer) {
                logContainer.scrollTop = logContainer.scrollHeight;
            }
        });
    </script>

</body>
</html>