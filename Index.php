
<?php 

include('Bd_connexion.php');

// Recevoir un ShortCut

        if(isset($_GET['q'])) {

// VARIABLE
                $shortcut = htmlspecialchars($_GET['q']);
// C'est un ShortCut ?

                $bdd = new PDO('mysql:host='.$hostname.';dbname='.$database.';charset=utf8',$username,$password);
                $req = $bdd ->prepare('SELECT COUNT(*) AS x FROM links WHERE shortcut = ?');
                $req->execute(array($shortcut));
        
                        while ($result = $req->fetch()) {
        
                        if ($result['x'] != 1){
        
                        header('location:./?error=true&message=Adresse url non connu');
        
                        exit();
                        
                        }
                }
        
// REDIRECTION 

                $req = $bdd ->prepare('SELECT * FROM links WHERE shortcut= ?');

                $req->execute(array($shortcut));

                while ($result = $req->fetch()) {

                header('location: '.$result['url']);

                exit();

                }
}
// Envoie Formulaire

        if(isset ($_POST['url'])) {

 // VARIABLE

        $url= $_POST['url'];

 // VERIFICATION

         if(!filter_var($url, FILTER_VALIDATE_URL)) {

 // PAS UN LIEN
        
        header ('location:../?error=true&message=Adresse url non valide');
        exit();
        
        }
 // SHORTCUT

        $shortcut = crypt($url, rand());

 // Déjà été envoyé ?


        $bdd = new PDO('mysql:host=localhost;dbname=bitly;charset=utf8', 'root', '');

        $req = $bdd ->prepare('SELECT COUNT(*) AS x FROM links WHERE url = ?');
        $req->execute(array($url));

                while ($result = $req->fetch()) {

                if ($result['x'] != 0){

                header('location:./?error=true&message=Adresse déjà raccourcie');

                exit();
        }  
}
 // Envoie

        $req = $bdd ->prepare('INSERT INTO links (url, shortcut)
                                VALUES(?, ?)');
       
        $req->execute(array($url, $shortcut));

                header('location:./?short='.$shortcut);

                exit();
}       
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Raccourcisseur d'URL</title>
            <link rel="stylesheet" href="Style.css">
            <link rel="shortcut icon" type="image/png" href="./Images/favicon.ico.png"/>
    </head>
    <body>
        <!--PRESENTATION-->
                <section id="hello">
                        <div class="container">
                                <header>

                                        <img src="./Images/logo.png" alt="logo" id="logo">

                                </header>

                                        <h1>Une url longue? Raccourcissez-là?</h1>

                                        <h2>Largement meilleur et plus court que les autres.</h2>
        <!--FORMULAIRE-->
                                <form method="post" action="Index.php">

                                        <input type="url" name = "url" placeholder="Collez un lien à racourcir"/>
                                         <input type="submit" value="Raccourcir">

                                </form>

                                <?php if(isset($_GET['error']) && isset($_GET['message'])) { ?>

                                        <div class="center">
                                        <div id="result">
                                                <b><?php echo htmlspecialchars($_GET['message']); ?></b>
                                        </div>
                                        </div>

                                <?php } else if (isset($_GET['short'])) { ?>

                                        <div class="center">
                                        <div id="result">

                                                <b>URL RACCOURCIE :</b>
                                                http://localhost/?q=<?php echo htmlspecialchars($_GET['short']); ?>

                                <?php } ?>
                                
                                        </div>
                                        </div>
                        </div>
                </section>

        <!--Marques-->
                <section id="brands">
        <!--CONTAINER-->
                
                <div class="container">

                <h3>Ces marques nous font confiance</h3>
                
                <img src="./Images/1.png" alt="photo" class="pictures">
                <img src="./Images/2.png" alt="photo" class="pictures">
                <img src="./Images/3.png" alt="photo" class="pictures">
                <img src="./Images/4.png" alt="photo" class="pictures">

                </div>
                </section>
        <footer>

        <img src="./Images/logo2.png" alt="logo" id="logo"><br>

        2018 © Bitly<br>
        <a href="#">Contact</a> - <a href="#">A propos</a>

        </footer>
 </body>
 </html>
