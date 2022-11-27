<?php
    require('../dbconnect.php');

    $id = $_GET['id'];

    $pdo = connect();

    try {
        $sql = "DELETE FROM odais WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
      
        header("Location: index.php");
        exit;
      } catch (PDOException $e) {
        echo $e->getMessage();
        die();
      }
?>