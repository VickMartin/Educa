<?php
require_once'Conexao.php';
require_once'../model/DTO/UsuarioDTO.php';

class UsuarioDAO{
    public $pdo = null;
    //construtor da classe que estabelece a canexão com o banco de dados no momentoda criação do objeto DAO
    public function __construct(){
        $this->pdo = Conexao::getInstance();
    }
    public function cadastroUsuario(UsuarioDTO $UsuarioDTO){
        try{
            $sql = "INSERT INTO usuarios (nome, email, cpf, perfil, senha ) VALUES (?,?,?,?,?)";
            $stmt = $this->pdo->prepare($sql);

            $nome = $UsuarioDTO->getNome();
            $email = $UsuarioDTO->getEmail();
            $cpf = $UsuarioDTO->getCpf();
            $perfil = $UsuarioDTO->getperfil();
            $senha = $UsuarioDTO->getSenha();


            $stmt->bindValue(param: 1,value: $nome);
            $stmt->bindValue(param: 2,value: $email);
            $stmt->bindValue(param: 3,value: $cpf);
            $stmt->bindValue(param: 4,value: $perfil);
            $stmt->bindValue(param: 5,value: $senha);
            $retorno = $stmt->execute();


            // Obtém o ID do usuário recém-cadastrado
            $idUsuario = $this->pdo->lastInsertId();

            // Verifica o perfil e cadastra na tabela correspondente
            if ($perfil === 'responsavel') {
                $sqlResponsavel = "INSERT INTO responsaveis (usuario_id) VALUES (?)";
                $stmtResponsavel = $this->pdo->prepare($sqlResponsavel);
                $stmtResponsavel->execute([$idUsuario]);
            } elseif ($perfil === 'professor') {
                $sqlProfessor = "INSERT INTO professores (usuario_id) VALUES (?)";
                $stmtProfessor = $this->pdo->prepare($sqlProfessor);
                $stmtProfessor->execute([$idUsuario]);
            }

            echo "Cadastro realizado com sucesso.";
           // return true;

            return $retorno;

        }catch(PDOException $exe){

            echo $exe->getMessage();

        }
       
    }
        
    public function validarLogin($email,$senha){
        try{
            $sql = "SELECT * FROM usuarios WHERE email = '{$email}' AND senha = '{$senha}'; ";
            $stmt = $this->pdo->prepare($sql);

            $stmt->execute();
            $retorno = $stmt->fetch(PDO::FETCH_ASSOC);

            return $retorno;


        }catch(PDOException $exe){

            echo $exe->getMessage();

        }
    }

    public function buscarPerfil($email, $senha) {
        try {
            // Define a consulta SQL para buscar o perfil do usuário com base no email e na senha fornecidos
            $sql = "SELECT perfil FROM usuarios WHERE email = ? AND senha = ?";
            $stmt = $this->pdo->prepare($sql);
            
            // Executa a consulta, passando os parâmetros do email e senha
            $stmt->execute([$email, $senha]);
    
            // Busca o resultado da consulta em um array associativo
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Retorna o perfil do usuário se encontrado, caso contrário retorna null
            return $usuario ? $usuario['perfil'] : null;
        } catch (PDOException $exe) {
            // Exibe a mensagem de erro em caso de exceção
            echo $exe->getMessage();
            return null; // Retorna null em caso de erro
        }
    }
    
    public function listarUsuarios(){
        // retorna todos os usuarios armazenados
        try{

            $sql = "SELECT * FROM usuarios";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $retorno = $stmt->fetchALL(PDO::FETCH_ASSOC);
            return $retorno;

        }catch (PDOException $exe) {
            // Exibe a mensagem de erro em caso de exceção
            echo $exe->getMessage();
            return null; // Retorna null em caso de erro
        }

        
    }
   
    
    public function excluirUsuario($id) {
        try {
            // Começa uma transação
            $this->pdo->beginTransaction();
    
            // Exclua as referências na tabela 'aluno'
            $sqlDeleteAluno = "DELETE FROM aluno WHERE id_responsavel = (SELECT id_responsavel FROM responsaveis WHERE usuario_id = :id_usuario)";
            $stmtAluno = $this->pdo->prepare($sqlDeleteAluno);
            $stmtAluno->bindParam(':id_usuario', $id, PDO::PARAM_INT);
            $stmtAluno->execute();
    
            // Exclua as referências na tabela 'responsaveis'
            $sqlDeleteResponsaveis = "DELETE FROM responsaveis WHERE usuario_id = :id_usuario";
            $stmtResponsaveis = $this->pdo->prepare($sqlDeleteResponsaveis);
            $stmtResponsaveis->bindParam(':id_usuario', $id, PDO::PARAM_INT);
            $stmtResponsaveis->execute();
    
            // Exclua as referências na tabela 'professores'
            $sqlDeleteProfessores = "DELETE FROM professores WHERE usuario_id = :id_usuario";
            $stmtProfessores = $this->pdo->prepare($sqlDeleteProfessores);
            $stmtProfessores->bindParam(':id_usuario', $id, PDO::PARAM_INT);
            $stmtProfessores->execute();
    
            // Em seguida, exclua o usuário na tabela 'usuarios'
            $sqlDeleteUsuario = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";
            $stmtUsuario = $this->pdo->prepare($sqlDeleteUsuario);
            $stmtUsuario->bindParam(':id_usuario', $id, PDO::PARAM_INT);
    
            // Executa a exclusão
            $retorno = $stmtUsuario->execute();
    
            // Confirma a transação
            $this->pdo->commit();
    
            return $retorno;
        } catch (PDOException $exe) {
            // Desfaz a transação em caso de erro
            $this->pdo->rollBack();
            echo $exe->getMessage();
            return null;
        }
    }
    

        public function buscarUsuarioPorId($id) {
            try {
                // Use um parâmetro nomeado corretamente
                $sql = "SELECT * FROM usuarios WHERE id_usuario = :id_usuario;";
                $stmt = $this->pdo->prepare($sql);
                
                // Liga o parâmetro
                $stmt->bindParam(':id_usuario', $id, PDO::PARAM_INT);
                
                // Executa a consulta
                $stmt->execute();
                
                // Busca o resultado da consulta em um array associativo
                $retorno = $stmt->fetch(PDO::FETCH_ASSOC);
                return $retorno; // Retorna o resultado ou null se não encontrado
                
            } catch (PDOException $exe) {
                // Exibe a mensagem de erro em caso de exceção
                echo $exe->getMessage();
                return null; // Retorna null em caso de erro
            }
        }
        
    

        public function buscarResponsavelPorCPF($cpf) {
            try {
                // SQL ajustado para incluir as informações da tabela usuarios
                $sql = "SELECT responsaveis.id_responsavel, usuarios.id_usuario, usuarios.cpf, usuarios.nome, usuarios.perfil
                        FROM responsaveis
                        INNER JOIN usuarios ON responsaveis.usuario_id = usuarios.id_usuario
                        WHERE usuarios.cpf = :cpf"; // Usando um parâmetro nomeado
                
                $stmt = $this->pdo->prepare($sql);
                
                // Liga o parâmetro
                $stmt->bindParam(':cpf', $cpf);
                
                // Executa a consulta
                $stmt->execute();
                
                // Busca o resultado da consulta em um array associativo
                $retorno = $stmt->fetch(PDO::FETCH_ASSOC);
                
                return $retorno; // Retorna o resultado ou null se não encontrado
            } catch (PDOException $exe) {
                // Exibe a mensagem de erro em caso de exceção
                echo $exe->getMessage();
                return null; // Retorna null em caso de erro
            }
        }   
   
    public function alterarUsuario(UsuarioDTO $usuarioDTO) {
        try {
            $sql = "UPDATE usuarios 
                    SET nome = :nome, 
                        email = :email, 
                        cpf = :cpf, 
                        senha = :senha, 
                        perfil = :perfil 
                    WHERE id_usuario = :id_usuario";
                    
            $stmt = $this->pdo->prepare($sql);
    
            // Obtenha os valores do DTO
            $nome = $usuarioDTO->getNome();
            $email = $usuarioDTO->getEmail();
            $cpf = $usuarioDTO->getCpf();
            $senha = $usuarioDTO->getSenha();
            $perfil = $usuarioDTO->getPerfil();
            $id_usuario = $usuarioDTO->getId_usuario(); // Certifique-se de que isso existe no DTO
    
            // Vincule os parâmetros
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':perfil', $perfil);
            $stmt->bindParam(':id_usuario', $id_usuario);
    
            // Debug: Mostre a consulta SQL
            // Você pode usar var_dump para verificar os dados também
            // var_dump($stmt); // Para ver os parâmetros vinculados
            
            // Execute a atualização
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao alterar o usuário: " . $e->getMessage();
            return false;
        }
    }
    







    }
    
    
    
    












?>