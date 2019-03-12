<?php

    session_start();
    if (!empty($_POST)) {

        // database operation
        require 'Database.php';
        $db_connect = new Database('projet');
        $pdo = $db_connect->getPDO();

        $wrong_inputs = array();

        $pseudo = mysqli_real_escape_string($pdo, $_POST['pseudo']);
        $pwd = mysqli_real_escape_string($pdo, $_POST['pwd']);

        // vadiation
        if (empty($pseudo) || empty($pwd)) {
            $wrong_inputs['pseudo'] = "Entrer votre pseudo";
            $wrong_inputs['pwd'] = "Entrer votre code";
            header("Location:dashboard.php?connexion=error");
            exit();
        } else {
            $sql = "SELECT * FROM Utlisateur WHERE Pseudo='$pseudo'";
            $verify = mysqli_query($pdo, $sql);
            $verify_result = mysqli_num_rows($verify);
            if ($verify_result < 1) {
                $wrong_inputs['pwd'] = "Votre compte n'existe pas!";
                header("Location:dashboard.php?connexion=error");
                exit();
            } else {
                if ($row = mysqli_fetch_assoc($verify_result)) {
                    $hashedPWDcheck = password_verify($pwd, $row['Mot_de_passe']);
                    if ($hashedPWDcheck == false) {
                        $wrong_inputs['pwd'] = "Votre mot de passe n'est pas correct";
                        header("Location:dashboard.php?connexion=error");
                        exit();
                    } elseif ($hashedPWDcheck == true) {
                        //connexion success
                        $_SESSION['auth'] = $user;
                        $_SESSION['status']['success'] = "Vous êtes maintenant connecté";
                        header('Location:dashboard.php');
                    }
                }
            }

        }

    }

?>

<?php var_dump($_SESSION) ?>
<?php require '../Templates/header.php'; ?>

<div class="modal fade" id="form_connection" tabindex="-1" role="dialog" aria-labelledby="form_connetionCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title form_title" id="form_connectionTitle">Connexion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="POST"  novalidate>
                    <div class="form-group">
                        <input type="text" class="form-control" id="pseudoInput" placeholder="Pseudo">
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="mdpInput" placeholder="Mot de passe">
                    </div>
                    <button type="submit" class="btn btn-danger connect_submit">Se connecter</button>
                    <a href="#">Mot de passe oublié ? </a>
                </form>
            </div>
            <div class="modal-footer">
                <span class="new">Vous êtes nouveau </span>
                ?
                <span class="new_account">Creer votre compte</span>
            </div>
        </div>
    </div>
</div>

<?php require '../Templates/footer.php'; ?>