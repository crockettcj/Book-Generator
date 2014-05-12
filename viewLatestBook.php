<?
require_once ("database/interface.php");
require_once "displayBook.php";
$data = readCatalog();


$bookID = $data["bookID"];
$numberOfPages = $data["numberOfPages"];
echo displayBook($numberOfPages,$bookID);

?>