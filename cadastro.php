<?php
require 'vendor/autoload.php'; // Autoload do Composer

use Kreait\Firebase\Exception\Auth\EmailExists;
use Kreait\Firebase\Exception\FirebaseException;

try {
    $firebase = (new Kreait\Firebase\Factory)->withServiceAccount(__DIR__ . '/serviceAccountKey.json');
    $auth = $firebase->createAuth();
    $firestore = $firebase->createFirestore();
} catch (Exception $e) {
    error_log("Erro na conexão com o Firebase: " . $e->getMessage());
}

$usuariosCollection = $firestore->database()->collection("Usuarios");

/**
 * Cadastra um usuário no Firebase Authentication e no Firestore.
 *
 * @param string $email O endereço de e-mail do usuário.
 * @param string $password A senha do usuário.
 * @param string $nome O nome do usuário.
 * @param string $curso O curso do usuário.
 *
 * @return array Um array com a chave "message" e o valor "Usuário criado com sucesso!" ou um array com a chave "error" e o valor do erro ocorrido.
 */
function cadastrarUsuario($email, $password, $nome, $curso) {
    global $auth, $usuariosCollection;

    try {
        $user = $auth->createUser([
            'email' => $email,
            'password' => $password,
            'displayName' => $nome,
        ]);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $usuariosCollection->document($user->uid)->set([
            'id' => $user->uid,
            'email' => $email,
            'nome' => $nome,
            'password' => $hashedPassword,
            'curso' => $curso,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    } catch (EmailExists $e) {
        return ['error' => 'E-mail já está em uso.'];
    } catch (FirebaseException $e) {
        return ['error' => $e->getMessage()];
    } catch (\Exception $e) {
        return ['error' => 'Erro inesperado: ' . $e->getMessage()];
    }
    

    return ['message' => 'Usuário criado com sucesso!', 'userId' => $user->uid];
}

