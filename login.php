<?php
include 'conexao.php';

// Verifica se a requisição atual é um POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Limpa o Email e armazena
    $email = htmlspecialchars($_POST['email']);
    $senha = $_POST['senha'];

    try {
        // Prepara a Instrução SQL para Execução
        $stmt = $conn->prepare("SELECT id_cliente, senha, nome FROM Usuarios 
                                where email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Obtém o resultado para trabalhar depois
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se algum usuário foi retornada a consulta
        // Se existir usuário
        if ($usuario) {
            // VErifica se a senha fornecida corresponde à senha armazenada
            if (password_verify($senha, $usuario['senha'])) {
                // Inicia Sessão para amazenar informações do usuário 
                session_start();
                // Regenera o ID da sessão para prevenis sequestro de sessão
                session_regenerate_id();
                // Define configurações seguras para o cookie da sessão
                session_set_cookie_params([
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);
                // Armazena o ID do usuário e o estado de login 
                $_SESSION['usuario_id'] = $usuario['id_cliente'];
                $_SESSION['logado'] = true;
                $_SESSION['nome'] = $usuario['nome'];

                // Redireciona o usuário para a página do painel após login
                header("Location: painel.php");
                exit;
            } else {
                // Caso a senha não esteja correta
                echo "Senha Incorreta";
            }
        } else {
            echo "Usuário Não encontrado";
        }
    } catch (PDOException $e) {
        echo "Erro no login" . $e->getMessage();
    }
}