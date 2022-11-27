<?php
    require('../dbconnect.php');

    $odai_id = $_GET['id'];

    $pdo = connect();

    try {
        $sql = "DELETE FROM odais WHERE id = :odai_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":odai_id", $odai_id);
        $stmt->execute();

        try {
            $sql = "DELETE FROM answers WHERE odai_id = :odai_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":odai_id", $odai_id);
            $stmt->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
          }

        header("Location: index.php");
        exit;
      } catch (PDOException $e) {
        echo $e->getMessage();
        die();
      }
?>