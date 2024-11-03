<?php
require_once'Conexao.php';
require_once'../model/DTO/AlunoDTO.php';

class AlunoDAO{
    public $pdo = null;
    //construtor da classe que estabelece a canexão com o banco de dados no momentoda criação do objeto DAO
    public function __construct(){
        $this->pdo = Conexao::getInstance();
    }

 public function cadastroAluno(AlunoDTO $alunoDTO){
        try{
            $sql = "INSERT INTO aluno (nome, ano_ingresso, data_nascimento, tipo_sanguineo, deficiencia, alergia, nome_mae, id_responsavel ) VALUES (?,?,?,?,?,?,?,?)";
            $stmt = $this->pdo->prepare($sql);

            $nome = $alunoDTO->getNome();
            $ano_ingresso = $alunoDTO->getAnoIngresso();
            $data_nascimento = $alunoDTO->getDataNascimento();
            $tipo_sanguineo = $alunoDTO->getTipoSanguineo();
            $deficiencia = $alunoDTO->getDeficiencia();
            $alergia = $alunoDTO->getAlergia();
            $nome_mae = $alunoDTO->getNomeMae();
            $id_responsavel = $alunoDTO->getIdResponsavel();


            $stmt->bindValue(param: 1,value: $nome);
            $stmt->bindValue(param: 2,value: $ano_ingresso);
            $stmt->bindValue(param: 3,value: $data_nascimento);
            $stmt->bindValue(param: 4,value: $tipo_sanguineo);
            $stmt->bindValue(param: 5,value: $deficiencia);
            $stmt->bindValue(param: 6,value: $alergia);
            $stmt->bindValue(param: 7,value: $nome_mae);
            $stmt->bindValue(param: 8,value: $id_responsavel);
            $retorno = $stmt->execute();

            return $retorno;

        }catch(PDOException $exe){
            echo $exe->getMessage();
    }
       
}
        
    
public function listarUsuarios(){
    // retorna todos os usuarios armazenados
    try{

        $sql = "SELECT * FROM aluno";
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


    public function buscarAlunoPorId($id) {
        try {
            // Use um parâmetro nomeado corretamente
            $sql = "SELECT * FROM aluno WHERE matricula = :matricula;";
            $stmt = $this->pdo->prepare($sql);
            
            // Liga o parâmetro
            $stmt->bindParam(':matricula', $id, PDO::PARAM_INT);
            
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



    



public function alterarAluno(AlunoDTO $alunoDTO) {
    try {
        $sql = "UPDATE aluno 
                SET nome = :nome, 
                    ano_ingresso = :ano_ingresso, 
                    data_nascimento = :data_nascimento, 
                    tipo_sanguineo = :tipo_sanguineo, 
                    deficiencia = :deficiencia, 
                    alergia = :alergia
                WHERE matricula = :matricula";
                
        $stmt = $this->pdo->prepare($sql);

        // Obtenha os valores do DTO
        $nome = $alunoDTO->getNome();
        $ano_ingresso = $alunoDTO->getAnoIngresso();
        $data_nascimento = $alunoDTO->getDataNascimento();
        $tipo_sanguineo = $alunoDTO->getTipoSanguineo();
        $deficiencia = $alunoDTO->getDeficiencia();
        $alergia = $alunoDTO->getAlergia();
        $matricula = $alunoDTO->getMatricula(); // Certifique-se de que o DTO contenha esse método
        
        // Vincule os parâmetros
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':ano_ingresso', $ano_ingresso);
        $stmt->bindParam(':data_nascimento', $data_nascimento);
        $stmt->bindParam(':tipo_sanguineo', $tipo_sanguineo);
        $stmt->bindParam(':deficiencia', $deficiencia);
        $stmt->bindParam(':alergia', $alergia);
        $stmt->bindParam(':matricula', $matricula);
        
        // Execute a atualização
        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Erro ao alterar o usuário: " . $e->getMessage();
        return false;
    }
}




}

?>
