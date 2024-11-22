<?php
// Código para receber as informações do HTML e fazer algo
// Captura o que o usuário digitou e cadastra no bd

// Chama arquivo de conexão
include 'conexao.php';

// Verifica se existe alguma informação chegando pela Rede
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Recebe o e-mail, filtra e armazena na variavel
    $email = htmlspecialchars($_POST['email']);

    // Recebe a senha, criptografa e armazena em uma variavel
    $senha = password_hash($_POST['senha'],PASSWORD_DEFAULT);

    $nome = htmlspecialchars($_POST['nome']);

    // Exibe a Variavel para testar 
    //var_dump($senha);

    // Bloco tente para cadastrar no banco de dados
    try{
        // Prepara o comando SQL para inserir no banco de dados
        // Utilizar o Prepared para preverir injetar SQL 
        $stmt = $conn->prepare("INSERT INTO Usuarios (email,senha,nome) 
                                VALUES (:email, :senha, :nome)");

        // Associa os valores das variaveis :email e :senha 
        $stmt->bindParam(":email",$email); // Vincula o e-mail e limpa 
        $stmt->bindParam(":senha",$senha);
        $stmt->bindParam(":nome",$nome);

        // Executa o código
        $stmt->execute();

        echo "Cadastrado com Sucesso";
    }catch(PDOException $e){
        echo "Erro ao cadastrar o usuário :".$e->getMessage();
    }
}