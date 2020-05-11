<?php
    //FORMIRANJE TABELA U BAZI

    require_once "db_connect.php";

    $sql = "CREATE TABLE IF NOT EXISTS users(
        id INT UNSIGNED AUTO_INCREMENT,
        username VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE = InnoDB;";

    $sql .= "CREATE TABLE IF NOT EXISTS profiles(
        id INT UNSIGNED AUTO_INCREMENT,
        user_id INT UNSIGNED NOT NULL UNIQUE,
        name VARCHAR(255) NOT NULL,
        dob DATE NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (user_id) REFERENCES users(id)
    ) ENGINE = InnoDB;";

    $sql .= "CREATE TABLE IF NOT EXISTS follow(
        id INT UNSIGNED AUTO_INCREMENT,
        user_id INT UNSIGNED NOT NULL,
        friend_id INT UNSIGNED NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (friend_id) REFERENCES users(id)
    ) ENGINE = InnoDB;";

    if($conn->multi_query($sql)) {
        echo "Uspešno kreirane tabele!";
    }
    else {
        echo "Došlo je do greške: " . $conn->error;
    }

    ?>
