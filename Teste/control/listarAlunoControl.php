<?php

    require_once'../model/DTO/AlunoDTO.php';
    require_once'../model/DAO/AlunoDAO.php';
  
    $alunoDAO = new AlunoDAO();
    $todosUsuarios = $alunoDAO->listarUsuarios();
   
    // echo "<pre>";
    // var_dump($todosUsuarios);

?>