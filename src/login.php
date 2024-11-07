<?php
require '../vendor/autoload.php'; // Autoload do Composer
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\FirebaseException;

$firebase = (new Kreait\Firebase\Factory)->withServiceAccount(__DIR__ . '/serviceAccountKey.json');
$auth = $firebase->createAuth();
$firestore = $firebase->createFirestore();
$usuariosCollection = $firestore->database()->collection("Usuarios");

/**
 * Realiza o login do usuário no Firebase Authentication.
 *
 * @param string $email O endereço de e-mail do usuário.
 * @param string $password A senha do usuário.
 *
 * @return array Um array com a chave "message" e o valor "Login bem-sucedido!" ou um array com a chave "error" e o valor do erro ocorrido.
 */

function loginUsuario($email, $password) {
    global $auth;

    try {
        $user = $auth->getUserByEmail($email);

        $signInResult = $auth->signInWithEmailAndPassword($email, $password);

        $chatToken = bin2hex(random_bytes(16));

        return [
            'message' => 'Login bem-sucedido!',
            'chat_token' => $chatToken,
            'userId' => $user->uid
        ];
    } catch (UserNotFound $e) {
        return ['error' => 'Usuário não encontrado.'];
    } catch (InvalidPassword $e) {
        return ['error' => 'Senha inválida.'];
    } catch (FirebaseException $e) {
        return ['error' => $e->getMessage()];
    }
}