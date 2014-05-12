<?
	
	define("VERSION", "v 0.1", true);
	define("APPENDIX_SIZE", 0);
	ini_set('memory_limit', '3024M');
	ini_set('max_execution_time', 600);
	$startTime = time();
	require "createBook.php";
	require "displayBook.php";
	require_once ("database/interface.php");
	
	doLog('', true);//clear the log file
	doLog('start createBook()',false);
	
	
	createBook();
	
	//==============================================================================//
	// CREATE BOOK , CONVERT TO IMAGES	AND SAVE TO CATALOG							//
	//==============================================================================//
	function createBook(){
	
	$makePrint = producePDF('book',null);

		if ($makePrint!=null){
		
			$numberOfPages 	= $makePrint[0]+APPENDIX_SIZE;
			$bookID			= $makePrint[1];
			//echo $bookID;
			
			if (convertToImg($numberOfPages,$bookID)){
				
					if(convertFirstPageToImg($bookID)){
						writeCatalog($numberOfPages,$bookID,0);
						require_once ("database/testClean.php");
						
					}
			}
		}
	}
	
	//==============================================================================//
	// DISPLAY THE BOOK AS IMAGES													//
	//==============================================================================//
	function showRenderedBook($numberOfPages,$bookID){
		
		header('Location: viewLatestBook.php');
	}
	
	//==============================================================================//
	// EMPTY IMAGES DIRECTORY														//
	//==============================================================================//
	function EmptyDir($dir) {
		$handle=opendir($dir);
		while (($file = readdir($handle))!==false) {
		//echo "$file <br>";
		if ($file!="..")@unlink($dir.'/'.$file);
		}
		closedir($handle);
	}

		
	$endTime = time();
//	echo 'time: '.($endTime - $startTime).'<br />';
function doLog($string, $erase=false){
		$myFile = $_SERVER['DOCUMENT_ROOT'].'/atlas/log.txt';
		if($erase){
			$fh = fopen($myFile, 'w') or die("can't open file");	
		}else{
			$fh = fopen($myFile, 'a') or die("can't open file");
		}
		fwrite($fh, $string."\n");
		fclose($fh);	
	}


function sendUserMail($firstName, $lastName, $email, $numberOfPages){
		$endTime = time();
		$to  = $email . ', '; // note the comma
		
		// subject
		$subject = 'new Atlas';
		
		// message
		$message = '
		<html>
		<head>
		  <title>Dear '.$firstName.' '.$lastName.', <br/><br/>A new '.$numberOfPages.' page copy of the Atlas has been generated successfully</title>
		</head>
		<body>
		  <p>Dear '.$firstName.' '.$lastName.'A new '.$numberOfPages.' page copy of the Atlas has been generated successfully</p>
		  click <a href="http://www.atlasofpentecostalism.net/index2.php">here</a> to view it.
		  time: '.round(abs($endTime - $startTime)/60,2).' minutes. <br />
		</body>
		</html>
		';
		
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		// Additional headers
		//$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
		$headers .= 'From: Atlas of Pentecostalism <admin@atlasofpentecostalism.net>' . "\r\n";
		//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
		//$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
		
		// Mail it
		mail($to, $subject, $message, $headers);
  		}

?>
