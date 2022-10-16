<?php
    session_start();
    error_reporting(0);
    $user = "root";
    $pwd = "root";
    $pdo = new PDO('mysql:host=db;dbname=blog', $user, $pwd);
    date_default_timezone_set('Europe/Paris');
    $date = date('d-m-y h:i:s');

    // $select = $pdo->query("SELECT * FROM user");
    // print_r($select->fetchAll(PDO::FETCH_ASSOC));
    if (isset($_POST['submit_inscription'])){
        if (!isset($_POST['email']) || !isset($_POST['nom']) || !isset($_POST['prenom']) || !isset($_POST['password'])){
            echo 'vous avez pas remplis tous les champs';
        }
        else{
            $email = $_POST['email'];
            $password = $_POST['password'];
            $prenom = $_POST['prenom'];
            $nom = $_POST['nom'];
            $requete_inscription = $pdo->prepare("INSERT INTO user (mail, mdp, prenom, nom) VALUES ('".$email."','".$password."','".$prenom."','".$nom."');");
            $requete_inscription->execute();
        }
    }

    if (isset($_POST['submit_connexion'])){
        if (!isset($_POST['email']) || !isset($_POST['password'])){
            echo 'vous avez pas remplis tous les champs';
        }
        else{
            $email = $_POST['email'];
            $password = $_POST['password'];
            $requete_connexion = $pdo->prepare("SELECT * FROM user WHERE mdp = '$password' and mail = '$email'");
            $requete_connexion->execute();
            $data = $requete_connexion->fetch(PDO::FETCH_ASSOC);
            $count = $requete_connexion->rowCount();
            if($count == 1){
                $_SESSION['id'] = $data["id"];
                $_SESSION['mail'] = $data["mail"];
                $_SESSION['nom'] = $data["nom"];
                $_SESSION['prenom'] = $data["prenom"];
            }
            else{
                echo "mauvais mot de passe ou mauvaise adresse mail";            
            }
        }
    }
    if (isset($_POST['submit_deconnexion'])) {
		$_SESSION = array();
		session_destroy();
		unset($_SESSION);
		header('Location: index.php');
	}
    if (isset($_POST['submit_poste'])) {
		if (!isset($_POST['text'])){
            echo 'vous avez pas remplis tous les champs';
        }
        else{
            $text = $_POST['text'];
            $id_user = $_POST['id_user'];
            $insert_poste = $pdo->prepare("INSERT INTO postes (id_user, texte, date_poste) VALUES ('".$id_user."','".$text."','".$date."');");
            $insert_poste->execute();
        }
	}
    if (isset($_POST['delete_post'])) {
        $id_poste = $_POST['id_poste'];
        $delete_poste = $pdo->prepare("DELETE FROM postes WHERE id_poste = '".$id_poste."'");
        $delete_poste->execute();
	}
    $select_postes = $pdo->prepare('SELECT * FROM postes INNER JOIN user ON postes.id_user = user.id');
    $select_postes->execute();
    $donnees_select_postes = $select_postes->fetchAll(PDO::FETCH_ASSOC); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="css/reseet.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav>
        <ul>
            <li>
                <?php
                    echo $_SESSION['prenom']," ",$_SESSION['nom'];
                ?>
            </li>
            <li></li>
        </ul>
        <ul>
            <li>
                <button id="open_popup_inscription">Inscription</button>
            </li>
            <li>
                <button id="open_popup_connexion">Connexion</button>
            </li>
            <li>
                <form class="form_deconnexion" action="index.php" method="post">
                    <input type="submit" name="submit_deconnexion" value="Se deconnecter">
                </form>
            </li>
        </ul>
    </nav>
    <div id="popup_inscription">
        <form class="form_inscription" action="index.php" method="post">
            <span id="close_popup_inscription">X</span>
            <h2>Inscription</h2>
            <input type="text" name="email" placeholder="Adresse Mail" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="submit" name="submit_inscription" value="S'inscrire">
        </form>
    </div>
    <div id="popup_connexion">
        <form class="form_connexion" action="index.php" method="post">
            <span id="close_popup_connexion">X</span>
            <h2>Connexion</h2>
            <input type="text" name="email" placeholder="Adresse Mail" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="submit" name="submit_connexion" value="Se connecter">
        </form>
    </div>
    <div>
        <form method="post" action="index.php" class="create_poste">
            <h2>Raconte ce que tu veux</h2>
            <textarea name="text"></textarea>
            <input type="hidden" name="id_user" value="<?= $_SESSION['id']?>">
            <input type="submit" name="submit_poste" value="Creer un poste">
        </form>
    </div>
    <?php foreach($donnees_select_postes as $donnee_select_postes): ?>
        <div class="poste">
            <div class="infos_poste">
                <span>poste de : <?= $donnee_select_postes["prenom"]?> <?= $donnee_select_postes["nom"]?> créé le : <?= $donnee_select_postes["date_poste"]?></span>
                <?php
                if($_SESSION['id'] == $donnee_select_postes["id_user"]){
                ?>    
                    <form method="post" action="index.php">
                        <input type="hidden" name="id_poste" value="<?= $donnee_select_postes["id_poste"]?>">
                        <input type="submit" name="delete_post" value="supprimer" class="delete_poste">
                    </form>
                <?php  
                }
                ?>
                <div class="text_poste">
                    <?= $donnee_select_postes["texte"]?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <script src="js/script.js"></script>
</body>
</html>