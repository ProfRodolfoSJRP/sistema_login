<?php
// Código para "Deslogar" do sistema
// Inicia a sessão
session_start();
// Destruir Sessão
session_destroy();

// Redirecionar para página de login
header("Location: index.php");
exit;