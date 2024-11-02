<?php
require 'vendor/autoload.php';
require './cadastro.php';
require './login.php';
use Kreait\Firebase\Factory;

$firebase = (new Factory)
    ->withServiceAccount(__DIR__ . '/serviceAccountKey.json')
    ->withDatabaseUri('https://wydenai.firebaseio.com');

$firestore = $firebase->createFirestore();
$usuariosCollection = $firestore->database()->collection("Usuarios");

header('Content-Type: application/json');
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

switch ($requestMethod) {
    case 'POST':
        if (strpos($requestUri, '/register') !== false) {
            $data = json_decode(file_get_contents('php://input'), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Erro ao decodificar JSON: " . json_last_error_msg());
            }

            $response_register = cadastrarUsuario($data['email'], $data['password'], $data['nome'], $data['curso']);
            echo json_encode($response_register);
        } 
        if (strpos($requestUri, '/login') !== false) {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Erro ao decodificar JSON: " . json_last_error_msg());
            }

            $email = $data['email'] ?? null;
            $password = $data['password'] ?? null;

            if (!$email || !$password) {
                throw new Exception('Email e senha obrigatórios');
            }

            // Chame a função de login e capture a resposta
            $response = loginUsuario($email, $password);
            echo json_encode($response);
        }
        break;
    case 'GET':
        if (strpos($requestUri, '/usuarios') !== false) {
            try {
                $usuarios = $usuariosCollection->documents();
                $usuariosArray = [];
                foreach ($usuarios as $usuario) {
                    $usuariosArray[] = $usuario->data();
                }
                echo json_encode(['usuarios' => $usuariosArray]);
            } catch (Exception $e) {
                error_log($e->getMessage());
                echo json_encode(['error' => $e->getMessage()]);
            }
        }
        break;
    default:
        echo json_encode(['message' => 'Método não suportado']);
        break;
}

