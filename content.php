<?
require_once("database/interface.php");
require_once("newsScraper/services.php");

error_reporting(E_ERROR | E_PARSE);
$bookContent 	= array();
ini_set('memory_limit', '3024M');
ini_set('max_execution_time', 600);
define("APPENDIX_SIZE", 7);
$filler = 0;
$quoteNr = rand(1, 7);
$stillNr = rand(1, 7);
$json = '';

//==============================================================================//
// ADD CHAPTERS TO ARRAY														//
//==============================================================================//

function buildBookContent(){
	/*
$mapFile = $_SERVER['DOCUMENT_ROOT'].'/atlas/maps.txt';
	$mapfromFile = file_get_contents($mapFile) ;		
	$GLOBALS["json"] = json_decode($mapfromFile, true);
*/
	
	
	
	insertCoverPage();
	insertEmptyPage();
	insertFillerPage();
	
		
	ToCSpace();
	

	//ADD CONTENT HERE
	
	
	

	

	ToCFillin();
	insertColophon();
	insertBackPage();
}

function buildAppendixContent($city, $lat, $lon){
	
	
	getAppendixAddresses($city, $lat, $lon);
}

//==============================================================================//
// RENDER CHAPTERS TO PAGE LAYOUT												//
//==============================================================================//

function getLayout(){
	
	$output			= "";
	$pages			= count($GLOBALS["bookContent"]);
	$pageNr			= 0;
	$layout			= "";
	
	for ($i=0; $i<$pages; $i++){
		$pageNr++;
		$page 		= "";
		$pageData 	= $GLOBALS["bookContent"][$i]["pageContent"];
		$chapterName= $GLOBALS["bookContent"][$i]["chapterName"];
		$fullPage	= $GLOBALS["bookContent"][$i]["fullPage"];
		$showHeader	= $GLOBALS["bookContent"][$i]["showHeader"];
		$showPageNr	= $GLOBALS["bookContent"][$i]["showPageNumber"];
		$head 		= "";
		
		if( $pageNr%2 ==0 ){
			
			//------------//
			// LEFT PAGE
			//------------//
			
			if ($fullPage) $page.= '<div id="leftPageWithoutMargins">'. "\n";
			if ($showPageNr)$page.= '<div class="pageNumberLeft"><div class="alignBottom">'.$pageNr.'</div></div>';
			if ($showHeader){
			$page.= '<div id="headerL">
						<div class="headerColLeft">
							<div class="alignBottom"><span class="headerLarge">ATLAS OF PENTACOSTALISM</span></div>
						</div>
						<div class="headerColLeft">
							<div class="alignBottom">&nbsp;</div>
						</div>
						<div class="headerColLeft">
							<div class="alignBottom">&nbsp;</div>
						</div>
					</div>';
			}	
			if (!$fullPage) $page.= '<div id="leftPageWithMargins">'. "\n";	
		}else{
			
			//------------//
			// RIGHT PAGE
			//------------//
			
			if ($fullPage) $page.= '<div id="rightPageWithoutMargins">'. "\n";
			if ($showPageNr)$page.= '<div class="pageNumberRight"><div class="alignBottom">'.$pageNr.'</div></div>';
			if ($showHeader){
			$page.= '<div id="headerR">
						<div class="headerColRight">
							<div class="alignBottom"><span class="headerLarge">'.strtoUpper($chapterName).'</span></div>
							
						</div>
						<div class="headerColRight">
							<div class="alignBottom"><span class="headerSmall">software version<br/><span class="headerSmallLight">'.VERSION.'</span></span></div>
						</div>
						<div class="headerColRight">
							<div class="alignBottom"><span class="headerSmall">page generated<br/><span class="headerSmallLight">'.date("F j, Y").'</span></span></div>

						</div>
					</div>';
			}
			if (!$fullPage) $page.= '<div id="rightPageWithMargins">'. "\n";
			
		}
		
		$layout		.=		$page;
		$layout		.=		$pageData;	
		$layout		.=		"</div>";


	}
	//echo $layout;
	return Array($layout,$pages);
}
function perspective(){
	require_once("test.php");
	$chapterName = "Andrew Johnson";
	$chapterType = "perspectives";
	$fullPage = false;
	$showHeader = true;
	$showPageNumber = true;
	startRight();
	titlePage("perspectives","Perspectives on Pentecostalism: ".$chapterName);

	$string = $johnson;
	$alineas = explode("\n\n",$string);
	$length = 3000;
	$characters = 0;
	$alinea = 0;
	$newArray = array();
	for ($i=0; $i<count($alineas); $i++){
		$characters+=strlen($alineas[$i]);
		if ($characters>$length){
			$characters = strlen($alineas[$i]);
			$alinea++;
		}
		$newArray[$alinea].=$alineas[$i]."<br/><br/>";
	}
	
	$counter = 0;
		
	for($i=0; $i<count($newArray); $i++){
		
		//---------------------
		// Perspective Page
		//---------------------
		
		
		$pageData;
		
		if( $counter%2 == 0 ){
			$pageData = '<div id="textCol1"> </div><div id="textCol2">'.nl2br($newArray[$counter]).'</div>';
			}else{
			$pageData = '<div id="textCol2">'.nl2br($newArray[$counter]).'</div><div id="textCol1"> </div>';
		}

		if ($i==1){
			$caption = "video still from the interview";
			$screenshot = '<div class="mapHolderLeft"><img class="imageSpread" src="assets/perspectives/johnson/'.rand(1, 4).'.jpg"/><div style="color:white; font-family: \'tstarregular\'; position:absolute; bottom:10px; left:10px;">'.$caption.'</div></div>';;
			$pageContent = array("pageContent"=>$screenshot,"chapterName"=>$chapterName, "fullPage"=>true, "showHeader"=>false, "showPageNumber"=>false, "chapterType"=>$chapterType);
			array_push($GLOBALS["bookContent"],$pageContent);
			$pageData = '<div id="textCol1"> </div><div id="textCol2">'.nl2br($newArray[$counter]).'</div>';
		}else{
			$counter++;
		}

		$pageContent = array("pageContent"=>$pageData,"chapterName"=>$chapterName, "fullPage"=>$fullPage, "showHeader"=>$showHeader, "showPageNumber"=>$showPageNumber, "chapterType"=>$chapterType);
		array_push($GLOBALS["bookContent"],$pageContent);
		
	}
}


function ToCSpace(){
	$chapterName = "Tale of Contents";
	$fullPage = true;
	$showHeader = false;
	$showPageNumber = false;
	startLeft();
	for ($i=0;$i<3;$i++){
		getSingleImage("assets/images/inhoudsopgave/toc".$i.".jpg");
		$pageData = "ToCStart";
		$pageContent = array("pageContent"=>$pageData,"chapterName"=>$chapterName, "fullPage"=>$fullPage, "showHeader"=>$showHeader, "showPageNumber"=>$showPageNumber, "chapterType"=>$chapterType);
		array_push($GLOBALS["bookContent"],$pageContent);
	}
}
function ToCFillin(){
	$startToC = 0;
	for ($i=0;$i<count($GLOBALS["bookContent"]);$i++){
		if ($GLOBALS["bookContent"][$i]["pageContent"]=='ToCStart'){
			$startToC = $i;
			break;
		}
	}
	$toc1='<div class="TocTitle">ICONOGRAPHY</div>';
	$toc2='<div class="TocTitle">CARTOGRAPHY</div>';
	$toc3='<div class="TocTitle">PERSPECTIVES</div>';
	//$toc4='<div class="TocTitle">VIDEOGRAPHY</div>';
	$backDrop='';
	for ($j=0; $j<3;$j++){
		$chapterStart = 0;
		$output = '';
		
		if ($j==0)$filter = 'iconography';

		if ($j==1)$filter = 'cartography';

		if ($j==2)$filter = 'perspectives';

		//if ($j==3)$filter = 'videography';
		
		$chapterArray = array();
		
		for ($i=0; $i<count($GLOBALS["bookContent"]);$i++){
			if ($GLOBALS["bookContent"][$i]["chapterType"]==$filter){
				$chapterName = $GLOBALS["bookContent"][$i]["chapterName"];
				$existsInArray = false;
				for ($a=0; $a<count($chapterArray);$a++){
					if ($chapterArray[$a]==$chapterName)$existsInArray=true;
				}
				
				if(!$existsInArray){
					$pageNumber = $i+1;
					$output.= '<div class="TocLine"><div class="tocLeft">'.strtoUpper($chapterName).'</div><div class="tocRight">'.$pageNumber.'</div></div>';
					$chapterArray[]=$chapterName;
				}
				//$chapterStart = $i;
			}
		}
		if ($j==0)$toc1.= $output;
		if ($j==1)$toc2.= $output;
		if ($j==2)$toc3.= $output;
		if ($j==3)$toc4.= $output;
		//if ($j==0)$backDrop1= '<img src="assets/gradients/gradient1.jpg" alt="A Nice Picture"/>';
		//if ($j==1)$backDrop2= '<img src="assets/gradients/gradient2.jpg" alt="A Nice Picture"/>';
		//if ($j==2)$backDrop3= '<img src="assets/gradients/gradient3.jpg" alt="A Nice Picture"/>';
		//if ($j==3)$backDrop4= '<img src="assets/gradients/gradient4.jpg" alt="A Nice Picture"/>';
	}

	$GLOBALS["bookContent"][$startToC]["pageContent"]=$backDrop1.'<div class="ToCContainer">'.$toc1.'</div>';
	$GLOBALS["bookContent"][$startToC+2]["pageContent"]=$backDrop2.'<div class="ToCContainer">'.$toc2.'</div>';
	$GLOBALS["bookContent"][$startToC+4]["pageContent"]=$backDrop3.'<div class="ToCContainer">'.$toc3.'</div>';
	//$GLOBALS["bookContent"][$startToC+6]["pageContent"]=$backDrop4.'<div class="ToCContainer">'.$toc4.'</div>';
}


function getSingleImage($fileName, $caption){
	$chapterName = "iconography";
	$fullPage = true;
	$showHeader = false;
	$showPageNumber = false;
	
		$pageData = '<img src="'.$fileName.'" alt="A Nice Picture"/><div style="color:white; font-family: \'tstarregular\'; position:absolute; bottom:20px; left:20px;">'.$caption.'</div>';
		$pageContent = array("pageContent"=>$pageData,"chapterName"=>$chapterName, "fullPage"=>$fullPage, "showHeader"=>$showHeader, "showPageNumber"=>$showPageNumber, "chapterType"=>$chapterType);
		array_push($GLOBALS["bookContent"],$pageContent);
}

function getSpreadImage($image, $category){
	$chapterName = $category;
	$chapterType = "iconography";
	$fullPage = false;
	$showHeader = true;
	$showPageNumber = true;
		
	startLeft();
		
		$pageData = '<div class="imgSpreadHolderLeft"><img class="imageSpread" src="../../cms/content/full/'.$image.'"/></div>';
		$pageContent = array("pageContent"=>$pageData,"chapterName"=>$chapterName, "fullPage"=>$fullPage, "showHeader"=>$showHeader, "showPageNumber"=>$showPageNumber, "chapterType"=>$chapterType);
		array_push($GLOBALS["bookContent"],$pageContent);
		$pageData = '<div class="imgSpreadHolderRight"><img class="imageSpread" src="../../cms/content/full/'.$image.'"/></div>';
		$pageContent = array("pageContent"=>$pageData,"chapterName"=>$chapterName, "fullPage"=>$fullPage, "showHeader"=>$showHeader, "showPageNumber"=>$showPageNumber, "chapterType"=>$chapterType);
		array_push($GLOBALS["bookContent"],$pageContent);
}



function titlePage($title, $subtitle){
	
	$chapterName = "Introduction";
	$fullPage = false;
	$showHeader = false;
	$showPageNumber = false;

	$pageData = '<div class="titlePage"><div class="titlePageTitle">'.strtoUpper($title).'</div><div class="titlePageSubTitle">'.$subtitle.'</div></div>';
	$pageContent = array("pageContent"=>$pageData,"chapterName"=>$chapterName, "fullPage"=>$fullPage, "showHeader"=>$showHeader, "showPageNumber"=>$showPageNumber, "chapterType"=>$chapterType);
	array_push($GLOBALS["bookContent"],$pageContent);
}

function startLeft(){
	$pages			= count($GLOBALS["bookContent"]);
	if( $pages%2 == 0 ){
		insertFillerPage();
		return true;
	}else{
		return true;
	}	
}
function startRight(){
	$pages			= count($GLOBALS["bookContent"]);
	if( $pages%2 != 0 ){
		insertFillerPage();
		return true;
	}else{
		return true;
	}	
}
function insertFillerPage(){
	$chapterName = "Introduction";
	
	$showHeader = false;
	$showPageNumber = false;
	$quotes = array(
	array("author"=>"", "quote"=>"Why waste time studying the Bible when you can experience the Holy Spirit anointing"),
	array("author"=>"", "quote"=>"Pentecostalism is a religion that roots in emotions not the mind. They say, \"You feel religion, you don’t think it.\""),
	array("author"=>"", "quote"=>"Knowledge is of the devil"),
	array("author"=>"", "quote"=>"The devil knows more about the Bible than any man"),
	array("author"=>"", "quote"=>"You don’t learn Pentecostalism, you experience it"),
	array("author"=>"Creflo Dollar", "quote"=>"Some people come to me and say, well I came here to get some peace, not money, and I tell them, you NEED money otherwise you ain't gunna get no peace"),
	array("author"=>"Creflo Dollar", "quote"=>"Some people say it's about peace, joy and love.  NO!! It's about MONEY!"),
	array("author"=>"", "quote"=>"God didn't create you to be average or poor")
	);
	
	
	$stills = array(
	array("url"=>"assets/stills/still1.jpg", "caption"=>"Video still from the documentary at 00:08:04"),
	array("url"=>"assets/stills/still2.jpg", "caption"=>"Video still from the documentary at 00:10:34"),
	array("url"=>"assets/stills/still3.jpg", "caption"=>"Video still from the documentary at 00:12:13"),
	array("url"=>"assets/stills/still4.jpg", "caption"=>"Video still from the documentary at 00:15:06"),
	array("url"=>"assets/stills/still5.jpg", "caption"=>"Video still from the documentary at 00:16:26"),
	array("url"=>"assets/stills/still6.jpg", "caption"=>"Video still from the documentary at 00:33:24"),
	array("url"=>"assets/stills/still7.jpg", "caption"=>"Video still from the documentary at 00:35:53"),
	array("url"=>"assets/stills/still8.jpg", "caption"=>"Video still from the documentary at 00:48:40")
	);
	
	

	if( $GLOBALS["filler"]%2 ==0 ){
		$fullPage = false;
		$pageData = '<div class="quote">"<span style="text-decoration:underline;">'.strtoUpper($quotes[$GLOBALS["quoteNr"]]["quote"]).'</span>"</div>';
		$GLOBALS["quoteNr"]++;
		if ($GLOBALS["quoteNr"]>count($quotes)-1)$GLOBALS["quoteNr"] = 1;
	}else{
		$pageData = '<img src="'.$stills[$GLOBALS["stillNr"]]["url"].'" alt=""/><div style="color:white; font-family: \'tstarregular\'; position:absolute; bottom:20px; left:20px;">'.$stills[$GLOBALS["stillNr"]]["caption"].'</div>';
		$fullPage = true;
		$GLOBALS["stillNr"]++;
		if ($GLOBALS["stillNr"]>count($quotes)-1)$GLOBALS["stillNr"] = 1;
	}
	$GLOBALS["filler"]++;
	$pageData.='<embed class="map" type="image/svg+xml"/>';
	$pageContent = array("pageContent"=>$pageData,"chapterName"=>$chapterName, "fullPage"=>$fullPage, "showHeader"=>$showHeader, "showPageNumber"=>$showPageNumber, "chapterType"=>$chapterType);
	array_push($GLOBALS["bookContent"],$pageContent);
	
}
function insertEmptyPage(){
	$chapterName = "Empty Page";
	$fullPage = true;
	$showHeader = false;
	$showPageNumber = false;
	$pageData = '';
	$pageContent = array("pageContent"=>$pageData,"chapterName"=>$chapterName, "fullPage"=>$fullPage, "showHeader"=>$showHeader, "showPageNumber"=>$showPageNumber, "chapterType"=>$chapterType);
	array_push($GLOBALS["bookContent"],$pageContent);
}

function insertCoverPage(){
	$chapterName = "Cover";
	$fullPage = true;
	$showHeader = false;
	$showPageNumber = false;
	$pageData = makeCoverData2();
	$pageContent = array("pageContent"=>$pageData,"chapterName"=>$chapterName, "fullPage"=>$fullPage, "showHeader"=>$showHeader, "showPageNumber"=>$showPageNumber, "chapterType"=>$chapterType);
	array_push($GLOBALS["bookContent"],$pageContent);
}
function insertColophon(){
	startLeft();
	$chapterName = "Colophon";
	$fullPage = false;
	$showHeader = false;
	$showPageNumber = false;
	$colophon.= '<div id="colophon">';
	$colophon.= '<b>Concept:</b> Bregtje van der Haak and Richard Vijgen<br/>';
	$colophon.= '<b>Design and software:</b> Richard Vijgen<br/>';
	$colophon.= '<b>Programming assistance:</b> Jasper van Loenen<br/>';
	$colophon.= '<b>Interviews:</b> Pentecostal and Charismatic Research Initiative led by Donald Miller at the Center for Religion and Civic Culture at the University of Southern California.<br/>';
	$colophon.= '<b>Video stills:</b> Great Expectations filmed and edited by Maasja Ooms<br/><br/>';
	
	$colophon.= 'At VPRO Television, Henneke Hagen did extensive research in the early stages and, further on, very little could have been accomplished without the continuing intellectual and practical support of anthropologist Asonzeh Ukah. <br/><br/>We wish to thank RCCG in Lagos for their hospitality and the team of Metropolis for lending us their excellent global network of reporters. The second round of photos has been shot by alumni of Columbia Journalism School in New York. Diane Winston and Birgit Meyer have supported the project both in practical and in academic ways. <br/><br/>Atlas of Pentecostalism is an experimental journalism and design project, which came out of the seminar ‘Follow the Money’, co-produced by the Cultural Media Fund and the Sandberg Institute in Amsterdam in 2010. 
	
Funding has been provided by the Pulitzer Center for Crisis Reporting with additional funding by VPRO Television, the Cultural Media Fund and USC Annenberg’s Knight Chair for Religion Reporting. Atlas of Pentecostalism is released with a CC license and hopes to contribute to the invention of new collaborative models for covering dynamic global trends and events.	';
	
	$colophon.= '</div>';
	$pageData = '<div id="textCol1"> </div><div id="textCol2">'.$colophon.'</div>';
	$pageContent = array("pageContent"=>$pageData,"chapterName"=>$chapterName, "fullPage"=>$fullPage, "showHeader"=>$showHeader, "showPageNumber"=>$showPageNumber, "chapterType"=>$chapterType);
	array_push($GLOBALS["bookContent"],$pageContent);
	
	$pageData = " ";
	$pageContent = array("pageContent"=>$pageData,"chapterName"=>$chapterName, "fullPage"=>$fullPage, "showHeader"=>$showHeader, "showPageNumber"=>$showPageNumber, "chapterType"=>$chapterType);
	array_push($GLOBALS["bookContent"],$pageContent);
}
function insertBackPage(){
	
	$chapterName = "Back";
	$fullPage = true;
	$showHeader = false;
	$showPageNumber = false;
	$pageData = makeBack();
	$pageContent = array("pageContent"=>$pageData,"chapterName"=>$chapterName, "fullPage"=>$fullPage, "showHeader"=>$showHeader, "showPageNumber"=>$showPageNumber, "chapterType"=>$chapterType);
	array_push($GLOBALS["bookContent"],$pageContent);
}


function constructImageGrid($dataResult,$category, $pointParents){
	$chapterName = strtoUpper($category);
	$chapterType = "iconography";
	$fullPage = false;
	$showHeader = true;
	$showPageNumber = true;
	$pageData='';
	$collumns = 3;
	$rows = 6;
	$pointsAmount = count($pointParents);
	$dataAmount = count($dataResult);	
	$positionNumber = 0;
	for($k=0;$k<$dataAmount;$k++){
	
		$churchData = $dataResult[$k];
		$positionNumber = -1;
	
		for($i=0; $i<$pointsAmount;$i++){
			if($churchData['parent'] == $pointParents[$i]){
				$positionNumber = $i+1;
			}
		}

		if ($imageRows>$rows){
			$imageRows = 0;
			$imageCollumns = 0;
			
			/*
if ($collumns>2){
			$collumns-=2;
			$rows-=2;
			}
*/
			$pageContent = array("pageContent"=>$pageData,"chapterName"=>$chapterName, "fullPage"=>$fullPage, "showHeader"=>$showHeader, "showPageNumber"=>$showPageNumber, "chapterType"=>$chapterType);
			array_push($GLOBALS["bookContent"],$pageContent);
			$pageData = '';
		}
		/*
if($positionNumber == -1){
			$pageData		.= 	'<div class="iconographyChurchEntry">
									<div class="iconoTitle">'.$churchData["churchName"].'</div>';
		}else{
			$pageData		.= 	'<div class="iconographyChurchEntry">
									<div class="iconoTitle">positie '.$positionNumber.' '.$churchData["churchName"].'</div>';
		}
		$pageData		.=	'<div class="iconoAdress">'.$churchData["adress"][0].'</div><div class="iconoUser">contributed by '.$churchData["user"][0].'</div>'.
							'</div>';
*/
		$numberOfImages = count($churchData["images"]);
		$imageCollumns 	= 1;
		
		for($j=0; $j<$numberOfImages; $j++){
			$imageCollumns++;
			if ($imageCollumns>$collumns){
				$imageCollumns = 1;
				$imageRows++;
			}
			if ($imageRows>$rows){
				$imageRows =1;
				$imageCollumns = 1;
				$pageContent = array("pageContent"=>$pageData,"chapterName"=>$chapterName, "fullPage"=>$fullPage, "showHeader"=>$showHeader, "showPageNumber"=>$showPageNumber, "chapterType"=>$chapterType);
				array_push($GLOBALS["bookContent"],$pageContent);
				$pageData = '';
			}
			$thumbWidth = 4.4/$collumns;
			$numberIndication = '';
			if($positionNumber != -1){
				$numberIndication = '<div class="numberInd">'.$positionNumber.'</div>';
			}
			$width = $churchData["widths"][$j];
			$height = $churchData["heights"][$j];
			if ($width>$height)$thumbStyle = "thumbHolderL";
			else $thumbStyle = "thumbHolderS";
			echo 	$thumbWidth."".$thumbStyle;		
			if ($churchData["parent2"][$j]==1)$pageData.='<div class="holderHolder" style="width:'.$thumbWidth.'in; height:'.$thumbWidth.'in;"><div class="iconographyThumb"><img class="'.$thumbStyle.'"  src="../../cms/content/web/'.$churchData["images"][$j].'"/>'.$numberIndication.'</div></div>';
		}
		$imageRows++;
		//$pageData.='<div class="iconographySpacer"></div>';
		//$numberOfImageRows+= ceil(count($churchData["images"])/5);
	}
	
	return $pageData;
}




function makeBack(){
	$coverOutput = '';
	//$coverOutput.='<div class="coverImage"><img src="assets/cover/achterkant.jpg" width="100%"/></div>';
	$coverOutput.='<div id="backInner">';
	$coverOutput.='<span class="blue">AN EXPANDING RECORD OF THE FASTEST GROWING RELIGION IN THE WORLD.</span><br/>ONE QUARTER OF THE TWO BILLION CHRISTIANS IN THE WORLD ARE NOW MEMBER OF A PENTECOSTAL CHURCH. COMPARED TO 6% IN 1980. IT HAS BECOME THE LARGEST CHRISTIAN TRADITION AFTER ROMAN CATHOLICISM. PENTECOSTALISM IS GROWING AT A RATE OF 13 MILLION A YEAR.';
	$coverOutput.='</div>';
	return $coverOutput;
}
function makeSpine(){
	$today = date("F j, Y");
	$coverOutput = '';
	$coverOutput.='<svg id="spineText" xmlns="http://www.w3.org/2000/svg">
	<text 
          transform="rotate(90 0,0)"
          style="stroke:none; fill:#000000;"
          >ATLAS OF PENTECOSTALISM / as of '.strtoLower($today).'</text>
	</svg>';
	return $coverOutput;
}
function makeCoverData(){
	$today = date("F j, Y");
	$now = time(); // or your date as well
     $your_date = strtotime("2013-10-01");
     $datediff = $now - $your_date;
     $dayssince  = floor($datediff/(60*60*24));
	
	
	$coverOutput = '';
	//$coverOutput.='<div class="coverImage"><img src="assets/cover/cover.svg" width="100%"/></div>';
	$coverOutput.='<div class="coverImage"><embed class="map" type="image/svg+xml" src="assets/cover/cover.svg" width="100%"/></div>';
	//$coverOutput.='<div class="coverTitle">ATLAS OF PENTECOSTALISM</div>';
	$coverOutput.='<div class="coverSubTitle">captured on<br/>'.strtoLower($today).'</div>';
	$coverOutput.=	'<div class="coverFooter">
						<div class="coverCollumn">
							PRINT NR. '.$dayssince.'<br/>
							
							'.numberOfChurches().' churches<br/>
							'.numberOfImages().' photographs<br/>
							'.numberOfChurches().' public contributions<br/>
							6 maps<br/>
							12 essays
						</div>
						<div class="coverCollumn">
							This volume holds content 
							by '.numberOfChurches().' contributors including:<br/>
							<br/>
							Bregtje van der Haak<br/>
							Richard Vijgen
						</div>
						<div class="coverCollumn">
							This book is a printed version
							of the online Atlas of Pentecostalism
							which can be found at<br/>
							<br/>
							www.atlasofpentecostalism.net

						</div>
					 </div>';
	
	return $coverOutput;
}

function makeCoverData2(){
	$today = date("F j, Y");
	$now = time(); // or your date as well
     $your_date = strtotime("2013-10-01");
     $datediff = $now - $your_date;
     $dayssince  = floor($datediff/(60*60*24));
	
	
	$coverOutput = '';
	//$coverOutput.='<div class="coverImage"><img src="assets/cover/cover.svg" width="100%"/></div>';
	$coverOutput = '<div class="mapHolderLeft" ><img class="imageSpread" src="http://www.atlasofpentecostalism.net/html/maps/churches/adressen3.png"/></div>';
	$coverOutput.= '<div class="mapHolderLeft"><embed class="map" type="image/svg+xml" src="http://www.atlasofpentecostalism.net/html/maps/churches/address_map/rendered/twitter_map.svg"/></div>';
	//$coverOutput.= '<div class="mapHolderLeft"><embed class="map" type="image/svg+xml" src="assets/maps/churches/vector.svg" style="z-index:500;"/></div>';
	
	$coverOutput.='<div class="coverImage2"><embed class="map" type="image/svg+xml" src="assets/cover/cover4.svg" width="100%"/></div>';
	//$coverOutput.='<div class="coverTitle">ATLAS OF PENTECOSTALISM</div>';
	$coverOutput.='<div class="coverSubTitle">'.strtoLower($today).'</div>';
	$coverOutput.=	'<div class="coverFooter">
						<div class="coverCollumn">
							PRINT NR. '.$dayssince.'<br/>
							
							'.numberOfChurches().' churches<br/>
							'.numberOfImages().' photographs<br/>
							'.numberOfChurches().' public contributions<br/>
							18 maps<br/>
							3 essays
						</div>
						<div class="coverCollumn">
							This volume holds content 
							by '.numberOfChurches().' contributors including:<br/>
							<br/>
							Bregtje van der Haak<br/>
							Richard Vijgen
						</div>
						<div class="coverCollumn">
							This book is a printed version
							of the online Atlas of Pentecostalism
							which can be found at:<br/>
							<br/>
							www.atlasofpentecostalism.net

						</div>
					 </div>';
	
	return $coverOutput;
}


function checkEmail($email) {
  if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])
  ↪*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",
               $email)){
    list($username,$domain)=split('@',$email);
    if(!checkdnsrr($domain,'MX')) {
      return false;
    }
    return true;
  }
  return false;
}
function isValidURL($url){
	return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}


?>