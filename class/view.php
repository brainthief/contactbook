<?php

class ContactBookView
{

  public function showEntirePage($header = '', $content = '', $footer = '', $msg = '')
  {
    return '
  <!DOCTYPE html>
  <html lang="lt">
  <head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <title>React App</title>
   <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
   <link href="css/bootstrap.min.css" rel="stylesheet">
   <link href="css/mdb.min.css" rel="stylesheet">
  </head>
  <body>
   <div class="container">
   ' . $header . '
   ' . $msg . '
   ' . $content . '
   </div>
  </body>
  </html>
  ';
  }

  public function showTable($data)
  {
    return '
   <div class="row mt-1">
    <div class="col-12">
     <table id="dtBasicExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
      <thead>
       <tr>
        <th class="th-sm">Vardas ir pavardė</th>
        <th class="th-sm">Telefono numeris</th>
        <th class="th-sm">Veiksmai</th>
       </tr>
      </thead>
      <tbody>
      ' . $data . '
      </tbody>
     </table>
   </div>
   </div>

  ';
  }


  public function editForm($id, $name, $phone, $string = '', $number = '')
  {
    return '
  <div class="row mt-2">
   <div class="col-3"></div>
   <div class="col-6 text-center">
   <h2>Redaguojamas įrašas #' . $id . '</h2>
    <form class="text-center border border-light" action="?action=update&id=' . $id . '&string=' . $string . '&number=' . $number . '" method="POST">
     <div class="form-row pt-4">
      <div class="col-12 pl-4 pr-4">
       <h4>Vardas ir pavardė:</h4>
       <input class="form-control " type="text" id="createName" onkeyup="changeForm()" name="name" value="' . $name . '" required>
      </div>
      <div class="col-12 pt-4  pl-4 pr-4">
       <h4>Telefono numeris:</h4>
       <input class="form-control" type="number" id="createPhone" onkeyup="changeForm()" max="99999999" name="phone" value="' . $phone . '" required>
       <p>Vesti be kodo (+370 ar 8)</p>
      </div>
      <div class="col-12 pt-4 pb-3">
       <input class="btn btn-success" type="submit" id="createBtn" value="Saugoti" disabled>
       <a href="?action=back&string=' . $string . '&number=' . $number . '" class="btn btn-primary">Atgal</a>
      </div>
     </div>
     <script>
       function changeForm() {
        let name = document.getElementById("createName").value;
        let phone = document.getElementById("createPhone").value;
        if(name.length > 0 && phone.length > 5 && phone.length < 9 ){document.getElementById("createBtn").disabled = false;} else {document.getElementById("createBtn").disabled = true;}
       }
      </script>
     </form> 
    <div class="col-3"></div>
   </div>
  </div>
  ';
  }

  public function createForm($string = '', $number = '')
  {
    return '
  <div class="row mt-2">
   <div class="col-3"></div>
   <div class="col-6 text-center">
   <h2>Sukurti naują įrašą</h2>
    <form class="text-center border border-light" action="?action=save&string=' . $string . '&number=' . $number . '" method="POST">
     <div class="form-row pt-4">
      <div class="col-12 pl-4 pr-4">
       <h4>Vardas ir pavardė:</h4>
       <input class="form-control " type="text"  id="createName" onkeyup="changeForm()" name="name" required>
      </div>
      <div class="col-12 pt-4  pl-4 pr-4">
       <h4>Telefono numeris:</h4>
       <input class="form-control" type="number"  id="createPhone" onkeyup="changeForm()" max="99999999" name="phone" required>
       <p>Vesti be kodo (+370 ar 8)</p>
      </div>
      <div class="col-12 pt-4 pb-3">
       <input id="createBtn" class="btn btn-success" type="submit" value="Sukurti įrašą" disabled>
       <a href="?action=back&string=' . $string . '&number=' . $number . '" class="btn btn-primary">Atgal</a>
      </div>
     </div>
     <script>
       function changeForm() {
        let name = document.getElementById("createName").value;
        let phone = document.getElementById("createPhone").value;
        if(name.length > 0 && phone.length > 5 && phone.length < 9 ){document.getElementById("createBtn").disabled = false;} else {document.getElementById("createBtn").disabled = true;}
       }
      </script>
     </form> 
    <div class="col-3"></div>
   </div>
  </div>
  ';
  }

  public function searchForm($string = '', $number = '')
  {
    return '
  <div class="row mt-2">
   <div class="col-12">
    <form action="?action=search" class="text-center border border-light" method="POST">
     <div class="form-row">
      <div class="col-xl-3 col-sm-6 col-12 pt-2 pl-3">
       <input class="form-control" id="searchName" onkeyup="changeForm()"  type="text" placeholder="Vardo ar pavardės fragmentas" name="string" value="' . $string . '">
      </div>
      <div class="col-xl-1 col-sm-1 col-2 pt-3 text-right">
       <span>+370</span>
      </div>
      <div class="col-xl-3 col-sm-5 col-10 pt-2 pr-3">
       <input  class="form-control" id="searchNumber" onkeyup="changeForm()"   type="number" placeholder="Telefono numerio fragmentas" name="number" value="' . $number . '">
      </div>
      <div class="col-xl-5 col-sm-12">
       <input type="submit" value="Ieškoti" type="button" id="searchBtn" class="btn btn-primary" disabled>
       <button onclick="clearForm()" class="btn btn-primary">Atšaukti</button>
      <script>
       function clearForm() {
        document.getElementById("searchName").value ="";
        document.getElementById("searchNumber").value = "";
       }
       function changeForm() {
        let name = document.getElementById("searchName").value;
        let phone = document.getElementById("searchNumber").value;
        if(name.length > 0 || phone.length > 0 ){document.getElementById("searchBtn").disabled = false;} else {document.getElementById("searchBtn").disabled = true;}
       }
      </script>
      <a href="?action=create&string=' . $string . '&number=' . $number . '" class="btn btn-success"><i class="fas fa-user-plus" aria-hidden="true"></i></a>
     </div>
    </div>
   </form>
  </div>
  </div>
  ';
  }

  public function successMsg($msg)
  {
    return '
   <div class="row mt-2">
    <div class="col-12">
     <div class="alert alert-success" role="alert">
      ' . $msg . '
     </div>
    </div>
   </div>';
  }

  public function errorMsg($msg)
  {
    return '
   <div class="row mt-2">
    <div class="col-12">
     <div class="alert alert-danger" role="alert">
      ' . $msg . '
     </div>
    </div>
   </div>';
  }
}
