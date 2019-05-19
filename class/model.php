<?php

require 'connection.php';
require 'view.php';

class ContactBookModel
{
  private $limit;
  private $start;
  private $string;
  private $number;
  private $total;

  function __construct()
  {
    $this->setTotalRowCount();
    $this->setInitLimit();
    $this->setInitStart();
    $this->setInitString();
    $this->setInitNumber();
  }

  private function setTotalRowCount()
  {
    $query = 'SELECT * FROM `name_phone`';
    $connection = new Connection;
    $result = $connection->connectAndGetData($query);
    $this->total = $result->num_rows;
  }

  private function setInitLimit()
  {
    if (isset($_GET['limit'])) {
      if (is_numeric($_GET['limit']) && $_GET['limit'] > 20 && $_GET['limit'] < 501) {
        $this->limit =  intval($_GET['limit']);
      } else {
        $this->limit = 30;
      }
    } else {
      $this->limit = 30;
    }
  }

  private function setInitStart()
  {
    if (isset($_GET['start'])) {
      if (is_numeric($_GET['start']) && $_GET['start'] > 1) {
        $this->start =  intval($_GET['start']);
      } else {
        $this->start = 1;
      }
    } else {
      $this->start = 1;
    }
  }

  private function setInitString()
  {
    if (isset($_POST['string'])) {
      $this->string = strip_tags($_POST['string']);
    } else {
      if (isset($_GET['string'])) {
        $this->string = strip_tags($_GET['string']);
      } else {
        $this->string = '';
      }
    }
  }

  private function setInitNumber()
  {
    if (isset($_POST['number'])) {
      $this->number = strip_tags($_POST['number']);
    } else {
      if (isset($_GET['number'])) {
        $this->number = strip_tags($_GET['number']);
      } else {
        $this->number = '';
      }
    }
  }

  private function getPagination($actualTotal)
  {
    $totalPages = ceil($actualTotal / $this->limit);
    $data = 'Rodomas ' . $this->start . ' puslapis iš ' . $totalPages . ' ';
    if ($this->start > 1) {

      $data .= '<a href="?start=' . ($this->start - 1) . '&string=' . $this->string . '&number=' . $this->number . '" class="btn btn-sm btn-success">Atgal į #' . ($this->start - 1) . ' puslapį</a>';
    }

    if ($this->start < $totalPages) {
      $data .= ' <a href="?start=' . ($this->start + 1) . '&string=' . $this->string . '&number=' . $this->number . '" class=" btn btn-sm btn-success">Sekantis #' . ($this->start + 1) . ' puslapis</a>';
    }
    return $data;
  }

  public function loadPage($msg = '')
  {
    $this->setTotalRowCount();
    $view = new ContactBookView;
    $header = $view->searchForm($this->string, $this->number);
    echo $view->showEntirePage($header, $view->showTable($this->constructTable()), '', $msg);
  }

  private function constructTable()
  {

    $connection = new Connection;

    $query =  'SELECT * FROM `name_phone`';
    if ($this->string != '' || $this->number != '') {
      $query .= " WHERE ";
    }
    if ($this->string != '') {
      $query .= "`Name` LIKE '%" . $this->string . "%' ";
    }

    if ($this->string != '' && $this->number != '') {
      $query .=  " AND ";
    }
    if ($this->number != '') {
      $query .= "`Phone` LIKE '%" . $this->number . "%'";
    }
    $result = $connection->connectAndGetData($query);

    $actualTotal = '';
    if (is_object($result)) {
      if ($result->num_rows > 0) {
        $actualTotal = $result->num_rows;
      }
      $query .= ' LIMIT ' . (($this->start - 1) * $this->limit) . ',' . $this->limit;

      $result = $connection->connectAndGetData($query);
      if ($result->num_rows > 0) {
        $data = '';
        while ($row = $result->fetch_assoc()) {
          $data .= '
       <tr>
        <td>' . $row["Name"] . '</td>
        <td>' . $row["Phone"] . '</td>
        <td>
        <a href="?action=edit&id=' . $row["id"] . '&string=' . $this->string . '&number=' . $this->number . '" class="btn-sm"><i class="fas fa-sm fa-user-edit" aria-hidden="true"></i></a>
        <a href="?action=del&id=' . $row["id"] . '&string=' . $this->string . '&number=' . $this->number . '" class="btn-sm"><i class="fas fa-user-alt-slash fa-sm" aria-hidden="true"></i></a>
        </td>
       </tr>
        ';
        }
        $data .= '
      <tr>
       <td colspan="3"  class="font-weight-bold" >' . $this->getPagination($actualTotal) . ' <br>Kriterijus atitinka ' . $actualTotal . ' iš ' . $this->total . ' </td>
      </tr>
      ';
        return $data;
      } else {
        $data = '
      <tr>
       <td colspan="3">Nerastas nei vienas įrašas atitinkatis užklausą</td>
      </tr>
      ';
        return $data;
      }
    } else {
      $data = '
      <tr>
       <td colspan="3">Nerastas nei vienas įrašas atitinkatis užklausą</td>
      </tr>
      ';
      return $data;
    }
  }

  public function createContact()
  {
    $view = new ContactBookView;
    echo $view->showEntirePage($view->createForm($this->string, $this->number));
  }

  public function editContact()
  {
    if (isset($_GET['id'])) {
      if (isset($_GET['id']) != '') {
        $connection = new Connection;
        $result = $connection->connectAndGetForUpdate($_GET['id']);
        if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            $view = new ContactBookView;
            echo $view->showEntirePage($view->editForm($row["id"], $row["Name"], $row["Phone"], $this->string, $this->number));
          }
        } else {
          $view = new ContactBookView;
          $this->loadPage($view->errorMsg('Nerastas įrašas'));
        }
      } else {
        $view = new ContactBookView;
        $this->loadPage($view->errorMsg('Nepateikti visi reikiami duomenys'));
      }
    } else {
      $view = new ContactBookView;
      $this->loadPage($view->errorMsg('Nepateikti visi reikiami duomenys'));
    }
  }

  public function updateContact()
  {
    if (isset($_GET['id']) && isset($_POST['name']) && isset($_POST['phone'])) {
      if (isset($_GET['id'])  != '' && isset($_POST['name'])  != '' && isset($_POST['phone'])  != '') {
        $connection = new Connection;
        $this->loadPage($connection->connectAndUpdateData(strip_tags($_GET['id']), strip_tags($_POST['name']), strip_tags($_POST['phone'])));
      } else {
        $view = new ContactBookView;
        $this->loadPage($view->errorMsg('Nepateikti visi reikiami duomenys'));
      }
    } else {
      $view = new ContactBookView;
      $this->loadPage($view->errorMsg('Nepateikti visi reikiami duomenys'));
    }
  }

  public function saveContact()
  {
    if (isset($_POST['name']) && isset($_POST['phone'])) {
      if (isset($_POST['name']) != '' && isset($_POST['phone']) != '') {
        $connection = new Connection;
        $this->loadPage($connection->connectAndSaveData(strip_tags($_POST['name']), strip_tags($_POST['phone'])));
      } else {
        $view = new ContactBookView;
        $this->loadPage($view->errorMsg('Nepateikti visi reikiami duomenys'));
      }
    } else {
      $view = new ContactBookView;
      $this->loadPage($view->errorMsg('Nepateikti visi reikiami duomenys'));
    }
  }

  public function delContact()
  {
    if (isset($_GET['id'])) {
      if (isset($_GET['id']) != '') {
        $connection = new Connection;
        $this->loadPage($connection->connectAndDelete($_GET['id']));
      } else {
        $view = new ContactBookView;
        $this->loadPage($view->errorMsg('Nepateikti visi reikiami duomenys'));
      }
    } else {
      $view = new ContactBookView;
      $this->loadPage($view->errorMsg('Nepateikti visi reikiami duomenys'));
    }
  }
}
