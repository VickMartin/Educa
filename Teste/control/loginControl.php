<?php
session_start(); // Inicia a sessão

if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
    // Lógica de autenticação
    require_once '../model/DAO/AdmDAO.php';
    require_once '../model/DAO/UsuarioDAO.php';

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $admDAO = new AdmDAO();
    $sucessoAdm = $admDAO->validarLogin($email, $senha);

    $usuarioDAO = new UsuarioDAO();
    $sucessoUsuario = $usuarioDAO->validarLogin($email, $senha);

    if ($sucessoAdm) {
        $_SESSION['usuario'] = $email;
        $_SESSION['perfil'] = 'administrador';

        echo "<script>
            alert('Logado como administrador!'); 
            window.location.href = '../view/telaAdm.php';
        </script>";
    } elseif ($sucessoUsuario) {
        $perfilUsuario = $usuarioDAO->buscarPerfil($email, $senha);

        if ($perfilUsuario) {
            $_SESSION['usuario'] = $email;
            $_SESSION['perfil'] = $perfilUsuario;

            // Redireciona com base no perfil do usuário
            if ($perfilUsuario === 'responsavel') {
                echo "<script>
                    alert('Logado como responsável!'); 
                    window.location.href = '../view/telaResponsavel.php';
                </script>";
            } elseif ($perfilUsuario === 'professor') {
                echo "<script>
                    alert('Logado como professor!'); 
                    window.location.href = '../view/telaProfessor.php';
                </script>";
            }
        }
        exit; // Finaliza o script após o redirecionamento
    } else {
        // Redireciona para a página de login caso os dados estejam ausentes
        echo "<script>
            alert('Usuário não encontrado!');
            window.location.href = '../index.php';
        </script>";
        exit;
    }
}
?>
