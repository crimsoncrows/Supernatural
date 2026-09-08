<?php


session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['player'])) {
    $_SESSION['player'] = $_POST['player'];
}

?>


<!DOCTYPE html>
<html>
<head>
    <link rel="icon" type="image/png" href="https://png.pngtree.com/png-clipart/20250123/original/pngtree-blood-moon-png-image_20325627.png">
    <title>php practice by crimsoncrows</title>
    <link rel="icon" type="image/png" href="https://png.pngtree.com/png-clipart/20250123/original/pngtree-blood-moon-png-image_20325627.png">
    <link rel="stylesheet" href="style.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Caudex:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&display=swap" rel="stylesheet">

</head>

<body>

    <div class="intro-text">
        <div class="welcome">

            <img src="N109-LOGO.png" style  width="400" height="400">
            <hr>
        </div>

        <p>
    Born once in Java, reborn now in PHP — every predator and prey below
    carries the same blood, the same instincts, translated line by line
    across languages as a study in Object-Oriented Programming.
  </p>
  <p class="edu-note">
    A practice project exploring OOP in PHP.
      Welcome to the zone where the dangerous unknown lurks and mysteries emerge.
  </p>
</div>

<div class="how-to-play">
  <ol>
    <li><strong>Choose your side.</strong> Pick a predator (Vampire, Werewolf, Fiend) to hunt, or a prey/guardian type (Angel, Seraphim) to survive and resist.</li>
    <li><strong>Face your foe.</strong> Each round, your creature and your opponent exchange skills — predators attack and drain, guardians defend and protect.</li>
    <li><strong>Watch your stats.</strong> Health, skill level, and unique traits (bite force, mood, blood drank) shift with every action. Weaknesses — garlic, sunlight, holy water — can turn a fight fast.</li>
    <li><strong>Survive or feast.</strong> The round ends when one side's health reaches zero. Predators win by draining their prey; prey win by outlasting the hunt.</li>
  </ol>
</div>


<div class="home">

    <div class="list-char">

        <form method="POST" action="prepare.php" class="char-form">
            <input type="hidden" name="player" value="Fiend">
            <button type="submit" class="char-card">
                <div class="frame">
                    <img class="creature-img floating" src="https://i.pinimg.com/736x/55/3f/cd/553fcd94e06b4e79d039179265b79cc4.jpg" alt="">
                    <h1 class="char-name">Crimson Fiend</h1>
                    <p class="char-info">Your soul is in captive.</p>
                </div>
            </button>
        </form>

        <form method="POST" action="prepare.php" class="char-form">
            <input type="hidden" name="player" value="Werewolf">
            <button type="submit" class="char-card">
                <div class="frame">
                    <img class="creature-img floating" src="https://m.media-amazon.com/images/I/71a75Jhy3qL._AC_UF894,1000_QL80_.jpg" alt="">
                    <h1 class="char-name">Lone Wolf</h1>
                    <p class="char-info">Do you dare follow?</p>
                </div>
            </button>
        </form>

        <form method="POST" action="prepare.php" class="char-form">
            <input type="hidden" name="player" value="Seraphim">
            <button type="submit" class="char-card">
                <div class="frame">
                    <img class="creature-img floating" src="https://image.tensorartassets.com/cdn-cgi/image/anim=true,plain=false,w=500,q=85/model_showcase/707950927233096208/1e229740-48c4-ee6e-777f-edd02654646a.jpeg" alt="">
                    <h1 class="char-name">Divine Seraphim</h1>
                    <p class="char-info">The one who listens.</p>
                </div>
            </button>
        </form>

        <form method="POST" action="prepare.php" class="char-form">
            <input type="hidden" name="player" value="Angel">
            <button type="submit" class="char-card">
                <div class="frame">
                    <img class="creature-img floating" src="https://cdn.talkie-ai.com/talkie/prod/img/2024-02-02/6f30c1ab-dee9-46c8-9b65-6858d0dc6bd8.jpeg?x-oss-process=image/resize,w_1024/format,webp" alt="">
                    <h1 class="char-name">Guardian Angel</h1>
                    <p class="char-info">The one who protects.</p>
                </div>
            </button>
        </form>

        <form method="POST" action="prepare.php" class="char-form">
            <input type="hidden" name="player" value="Vampire">
            <button type="submit" class="char-card">
                <div class="frame">
                    <img class="creature-img floating" src="https://creator.nightcafe.studio/jobs/Yh2f73gnUNMLBGNlZ6ye/Yh2f73gnUNMLBGNlZ6ye--1--ba861.jpg" alt="">
                    <h1 class="char-name">Red Vampire</h1>
                    <p class="char-info">Prepare to be a feast.</p>
                </div>
            </button>
        </form>

        <form method="POST" action="prepare.php" class="char-form">
            <input type="hidden" name="player" value="Netherlord">
            <button type="submit" class="char-card">
                <div class="frame">
                    <img class="creature-img floating" src="https://i.pinimg.com/736x/55/4e/60/554e6019d08d9cb72f06b75585a0ad8a.jpg" alt="">
                    <h1 class="char-name">Netherlord</h1>
                    <p class="char-info">Take my hand.</p>
                </div>
            </button>
        </form>

    </div>
</div>




</body>


</html>

