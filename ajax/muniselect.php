<?php 
include_once("../db.php");
if($_POST){
    $deptoselected =(isset($_POST["deptoselection"])?$_POST["deptoselection"]:"");

    $sql = $conexion->prepare("SELECT * FROM municipios WHERE id_departamento=:departamento");
    $sql->bindParam(":departamento",$deptoselected);
    $sql->execute();
    $list_municipios=$sql->fetchAll(PDO::FETCH_ASSOC);

    foreach($list_municipios as $municipios){
        $idmuni = $municipios['id'];
        $municipio = $municipios['municipio'];

        $arreglomunicipios [] = array("id"=>$idmuni,"muni"=>ucwords($municipio));
    }

    echo json_encode($arreglomunicipios);

}
?>