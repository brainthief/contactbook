<?php

require 'model.php';

class ContactBookController
{
 public function index()
 {
  $page = new ContactBookModel;
  if (isset($_GET['action'])) {
   switch ($_GET['action']) {
    case 'create':
     $page->createContact();
     break;
    case 'save':
     $page->saveContact();
     break;
    case 'edit':
     $page->editContact();
     break;
    case 'update':
     $page->updateContact();
     break;
    case 'del':
     $page->delContact();
     break;
    default:
     $page->loadPage();
     break;
   }
  } else {
   $page->loadPage();
  }
 }
}
