<?php
require_once '../model/DAO/AdmDAO.php'; // Inclui a classe AdmDAO

session_start();

// Verifica se o usuário está logado como administrador
if (!isset($_SESSION['usuario']) || $_SESSION['perfil'] !== 'administrador') {
    echo "<script>
        alert('Acesso não autorizado!');
        window.location.href = '../index.php';
    </script>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEMA | VM</title>
    <link rel="stylesheet" href="../css/adm.css">
</head>

<body>
    <!-- Barra de navegação -->
    <nav class="navbar">
        <div class="system-name">
            <h3>Educa<span>Mentes</span></h3>
        </div>
        <a href="../control/logout.php" class="logout-button">Sair</a>
    </nav>

    <!-- Guia lateral -->
    <div class="sidebar">

        <div class="menu-container">
            <h3 class="menu-text">Menu</h3>
            
        </div>
        <hr>
        <br>
        <a href="formMeuperfil.php" class="sidebar-btn" onclick="showSection('perfil')">Meu Perfil</a>
        <a href="gerencia.php" class="sidebar-btn" onclick="showSection('gerencia')">Gerenciar Prefis</a>
        <a href="buscarPai.php" class="sidebar-btn" onclick="showSection('alunoForm')">Cadastrar Alunos</a>
        <a href="formResponsavel.php" class="sidebar-btn" onclick="showSection('responsavelForm')">Cadastrar Responsável</a>
        <a href="professorForm.php" class="sidebar-btn" onclick="showSection('professorForm')">Cadastrar Professor</a>



    </div>

    <?php
    require_once '../control/listarUsuarioControl.php';
    ?>

   

        <table>
            
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>CPF</th>
                <th>Perfil</th>
                <th colspan="2">Operações</th>

            </tr>
            <?php foreach ($todosUsuarios as $t): ?>
                <tr>
                    <td><?php echo $t['id_usuario']; ?></td>
                    <td><?php echo $t['nome']; ?></td>
                    <td><?php echo $t['email']; ?></td>
                    <td><?php echo $t['cpf']; ?></td>
                    <td><?php echo $t['perfil']; ?></td>

                    <td>
                        <a href="../control/excluirUsuarioControl.php?id=<?php echo $t['id_usuario']; ?>">

                            <button class="btn_exclir">Excluir</button>

                        </a>
                    </td>
                    <td>
                        <a href="../view/alterarUsuario.php?id=<?php echo $t['id_usuario']; ?>">


                            <button class="btn_alterar">Alterar</button>

                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php
    require_once '../control/listarAlunoControl.php';
    ?>   
        <table>
            <tr>
                <th>matricula</th>
                <th>Nome</th>
                <th>Data de Nascimento:</th>
                <th>Tipo Sanguíneo</th>
                <th>Deficiência</th>
                <th>Alergia</th>
                <th>Nome do Responsável</th>
                

                <th>Operações</th>
                
            

            </tr>
            <?php foreach ($todosUsuarios as $t): ?>
                <tr>
                    <td><?php echo $t['matricula']; ?></td>
                    <td><?php echo $t['nome']; ?></td>
                    <td><?php echo $t['data_nascimento']; ?></td>
                    <td><?php echo $t['tipo_sanguineo']; ?></td>
                    <td><?php echo $t['deficiencia']; ?></td>
                    <td><?php echo $t['alergia']; ?></td>
                    <td><?php echo $t['nome_mae']; ?></td>
                    
                    <td>
                        <a href="../view/alterarAluno.php?id=<?php echo $t['matricula']; ?>">


                            <button class="btn_alterar">Alterar</button>

                        </a>
                    </td>
                  
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</html>