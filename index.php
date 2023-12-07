<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Liste d'archives</title>
</head>
<body>
    <header><h1>Listes d'archives</h1></header>
    
    <div id="searchandadd">
        <input type="text" id="search" name="search" placeholder="Rechercher un utilisateur...">
        <button class="btn" id="openModalBtn">Ajouter un utilisateur</button>
    </div>
    <div id="display-search"></div>
    <?php require('addUser.php');?>
    <?php require('defaultDisplay.php');?>

    <div class="modal-container" id="modalContainer">
        <div class="modal">
            <span class="close-btn" id="closeModalBtn">&times;</span>
            <h2>Ajouter un utilisateur</h2>
            <form method="POST">
                <label for="firstname">Prénom :</label>
                <input type="text" id="firstname" name="firstname" required><br><br>

                <label for="lastname">Nom :</label>
                <input type="text" id="lastname" name="lastname" required><br><br>

                <label for="subscribe_date">Date d'inscription :</label>
                <input type="date" id="subscribe_date" name="subscribe_date" required><br><br>
                <div id="error-message" class="error-message"></div>

                <input class="btn" type="submit" value="Ajouter">
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>