<?php

class Connection
{
  private $serverName = "localhost";
  private $userName = "root";
  private $password = "";
  private $dbname = "phonebook";

  public function connectAndGetData($query)
  {
    $conn = new mysqli($this->serverName, $this->userName, $this->password, $this->dbname);
    if ($conn->connect_error) {
      die("Jungimosi klaida: " . $conn->connect_error);
    }
    $result = $conn->query($query);
    $conn->close();
    return $result;
  }

  public function connectAndGetForUpdate($id)
  {
    $conn = new mysqli($this->serverName, $this->userName, $this->password, $this->dbname);
    if ($conn->connect_error) {
      die(" Jungimosi klaida: " . $conn->connect_error);
    }
    $query =  "SELECT * FROM `name_phone` WHERE `id` = " . mysqli_real_escape_string($conn, $id) . " LIMIT 1";
    $result = $conn->query($query);
    $conn->close();
    return $result;
  }

  public function connectAndDelete($id)
  {
    $conn = new mysqli($this->serverName, $this->userName, $this->password, $this->dbname);
    if ($conn->connect_error) {
      die(" Jungimosi klaida: " . $conn->connect_error);
    }
    $query =  "DELETE FROM `name_phone` WHERE id=" . mysqli_real_escape_string($conn, $id);
    $result = $conn->query($query);
    $conn->close();
    $msg = new ContactBookView;
    if ($result === TRUE) {
      return $msg->successMsg('Įrašas ištrintas');
    } else {
      return $msg->errorMsg("Klaida trinant duomenis: " . $conn->error);
    }
  }

  public function connectAndSaveData($name, $phone)
  {
    $conn = new mysqli($this->serverName, $this->userName, $this->password, $this->dbname);
    $query = "INSERT INTO `name_phone` (id, Name , Phone) VALUES ( NULL, '" . mysqli_real_escape_string($conn, $name) . "', '" . mysqli_real_escape_string($conn, $phone) . "') ";
    if ($conn->connect_error) {
      die(" Jungimosi klaida: " . $conn->connect_error);
    }
    $result = $conn->query($query);
    $conn->close();
    $msg = new ContactBookView;
    if ($result === TRUE) {
      return $msg->successMsg('Sukurtas naujas įrašas');
    } else {
      return $msg->errorMsg("Klaida: " . $query);
    }
    return $result;
  }

  public function connectAndUpdateData($id, $name, $phone)
  {
    $conn = new mysqli($this->serverName, $this->userName, $this->password, $this->dbname);
    $query = "UPDATE `name_phone` SET `Name` = '" . mysqli_real_escape_string($conn, $name) . "', `Phone` = '" . mysqli_real_escape_string($conn, $phone) . "' WHERE `name_phone`.`id` = " . mysqli_real_escape_string($conn, $id) . ";";
    if ($conn->connect_error) {
      die(" Jungimosi klaida: " . $conn->connect_error);
    }
    $result = $conn->query($query);
    $conn->close();
    $msg = new ContactBookView;
    if ($result === TRUE) {
      return $msg->successMsg('Išsaugota sėkmingai');
    } else {
      return $msg->errorMsg("Klaida: " . $query);
    }
    return $result;
  }
}
