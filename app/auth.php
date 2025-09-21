<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

function loginWithGoogle() {
    global $google_client, $pdo;

    $allowedDomain = 'gmail.com';//modificar a @cedhinuevaarequipa.edu.pe

    if (isset($_GET["code"])) {
        $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

        if (!isset($token['error'])) {
            $google_client->setAccessToken($token['access_token']);
            $_SESSION['access_token'] = $token['access_token'];

            $google_service = new Google\Service\Oauth2($google_client);
            $data = $google_service->userinfo->get();

            if ($data) {
                $email = $data['email'];
                $emailDomain = substr(strrchr($email, "@"), 1);

                if ($emailDomain !== $allowedDomain) {
                    logout();
                    throw new Exception("Acceso denegado. Solo se permiten cuentas de @{$allowedDomain}.");
                    return null;
                }

                $stmt = $pdo->prepare("SELECT * FROM users WHERE google_id = :google_id OR email = :email LIMIT 1");
                $stmt->execute([":google_id" => $data['id'], ":email" => $data['email']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user) {
                    $stmt = $pdo->prepare("INSERT INTO users (google_id, first_name, last_name, email, picture, role)
                                           VALUES (:google_id, :first_name, :last_name, :email, :picture, 'estudiante')");
                    $stmt->execute([
                        ":google_id" => $data['id'],
                        ":first_name" => $data['given_name'] ?? '',
                        ":last_name"  => $data['family_name'] ?? '',
                        ":email"      => $data['email'],
                        ":picture"    => $data['picture'] ?? ''
                    ]);

                    $userId = $pdo->lastInsertId();
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
                    $stmt->execute([":id" => $userId]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $stmt = $pdo->prepare("UPDATE users 
                                           SET google_id = :google_id, first_name = :first_name, 
                                               last_name = :last_name, email = :email, picture = :picture 
                                           WHERE id = :id");
                    $stmt->execute([
                        ":google_id" => $data['id'],
                        ":first_name" => $data['given_name'] ?? '',
                        ":last_name"  => $data['family_name'] ?? '',
                        ":email"      => $data['email'],
                        ":picture"    => $data['picture'] ?? '',
                        ":id"         => $user['id']//id extraído de la bd, no de google
                    ]);

                    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
                    $stmt->execute([":id" => $user['id']]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                }

                $userDataForSession = [
                    'user_id' => $user['id'],
                    'google_id' => $user['google_id'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'email' => $user['email'],
                    'picture' => $user['picture'],
                    'role' => $user['role'] //rol asignado en la bd
                ];

                return $userDataForSession;
            }
        }
    }
    return null;
}

function logout() {
    unset($_SESSION['access_token']);
    // *** MODIFICACIÓN: También limpiar los datos de usuario de la sesión
    unset($_SESSION['user_id']);
    unset($_SESSION['user_first_name']);
    unset($_SESSION['user_last_name']);
    unset($_SESSION['user_email_address']);
    unset($_SESSION['user_image']);
    unset($_SESSION['role']);
    session_destroy();
}
?>