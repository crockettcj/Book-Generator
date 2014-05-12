<?
	//header('Content-Type: application/pdf');
	//header('Content-Disposition: inline; filename="foo.pdf"');
	
	require "prince_files/prince.php";
	require "content.php";

	define( 'APIKEY', 'qmyb6vtfmwjwwad7zd5uv8ag');
	ini_set('memory_limit', '3024M');
	ini_set('max_execution_time', 600);
	
	function produceCover($pages, $filename){
	
		$prince = new Prince('/usr/local/bin/prince');
		$prince->addStyleSheet("styles/cover.css");
		
		$spineData = get_spineSize($pages);
		
		$width 			= $spineData[coverSizeData][fullCoverDimension][width][valueInInches];
		$height 		= $spineData[coverSizeData][fullCoverDimension][height][valueInInches];
		$spineWidth 	= $spineData[coverSizeData][spineWidth][valueInInches];
		$spineIndent 	= $spineData[coverSizeData][spineIndentation][length][valueInInches];
		$frontDiv 		= $spineWidth+$spineIndent+0.126;
		$spineDiv 		= $spineIndent+0.126;
		$coverContent = makeCoverData();
		$backContent = makeBack();
		$spineContent = makeSpine();
		//==============================================================================//
		// ENCAPSULATE HTML BEFORE PARSING												//
		//==============================================================================//
			
			$string = '';
			$string.= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
		 
			$string.= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >';
			$string.= '<head>';
			
			$string.= '<style type="text/css"> @page{ size: '.$width.'in '.$height.'in; margin:0mm;background-color: white;} </style>';
			
			$string.= '</head>';
			$string.= '<body>';
			
			//$string.= makeCover($pages,$frontDiv);
			$string.='<div id="back" style="position: absolute; left:0.126in; width:'.$spineIndent.'in;">'.$backContent.'</div>';
			$string.='<div id="spine" style="position: absolute; left:'.$spineDiv.'in; width:'.$spineWidth.'in;">'.$spineContent.'</div>';
			$string.='<div id="front" style="position: absolute; left:'.$frontDiv.'in; width:'.$spineIndent.'in;">'.$coverContent.'</div>';
			
			
			$string.= '</body>';
			$string.= '</html>';
			
			//==============================================================================//
			// PARSE HTML STRING TO PDF FILE													//
			//==============================================================================//
			if ($result = $prince->convert_string_to_file($string, "../pdfs/cover_".$filename.".pdf")){
				return true;
			}
			
	}
	function producePDF($type, $attribute){
	
		$prince = new Prince('/usr/local/bin/prince');
		$prince->addStyleSheet("/home/pcatlas/domains/atlasofpentecostalism.net/public_html/print/atlas/styles/book.css");
		if ($type=='book')buildBookContent();
		if ($type=='appendix'){
			$lat = $_SESSION['lat'];
			$lon = $_SESSION['lon'];
			buildAppendixContent($attribute, $lat, $lon);
		}
		//==============================================================================//
		// ENCAPSULATE HTML BEFORE PARSING												//
		//==============================================================================//
		
			$string = '';
			$string.= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
		 
			$string.= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >';
			$string.= '<head>';
			
			
			$string.= '</head>';
			$string.= '<body>';
			$layoutData = getLayout();
			$string.= $layoutData[0];
			
			$string.= '</body>';
			$string.= '</html>';
			//==============================================================================//
			// PARSE HTML STRING TO PDF FILE												//
			//==============================================================================//
			if ($type=='book'){
				
				$filename = md5(uniqid(mt_rand(),ﾊtrue));
				$saveLocation = "../book/".$filename."_atlas";
			}
			if ($type=='appendix'){
				$saveLocation = "/home/pcatlas/domains/atlasofpentecostalism.net/public_html/appendices/pdf/appendix_";
				$filename = $attribute.'_'.$layoutData[1];
			}
			
			if ($result = $prince->convert_string_to_file($string, $saveLocation.".pdf")){
				if ($type=='book'){
					
						
						$feedback = array($layoutData[1],$filename);
						return $feedback;
					
				}
				if ($type=='appendix'){
					$feedback = array($layoutData[1],$filename);
					return $feedback;
				}
				
			}
			
			//$result =  $prince->convert_string_to_file($string, "../pdfs/hoera2.pdf");
			//echo $result;
			
	}
	
	function convertToImg($numberOfPages,$bookID){
		doLog('begin converting to image');
		echo "convert -density 75 ../book/".$bookID."_atlas.pdf -scale 100% ../img/image_".$bookID."_%03d.png";
		//==============================================================================//
		// CONVERT MULTI-PAGE PDF TO IMAGES	AND READ FOLDER CONTENTS					//
		//==============================================================================//
			exec("convert -density 75 ../book/".$bookID."_atlas.pdf -scale 100% ../img/image_".$bookID."_%03d.png");
			//-density 72  -resample 75
			$images = array();
			
			if ($handle = opendir('../img/')) {
		    	while (false !== ($file = readdir($handle))) {
		    		echo "<img src=\"img/".$file."\">";
		    		$images[]=$file;
				}
				closedir($handle);
			}
			sort($images,SORT_STRING);
			doLog('done converting to image');
			return true;
	}
	
	function convertCoverToImg($bookID){
		//==============================================================================//
		// CONVERT COVER PDF TO IMAGE													//
		//==============================================================================//
			exec("convert -density 150 ../book/".$bookID."_atlas.pdf -scale 50% ../img/cover_".$bookID."_%03d.jpg");
			//-density 72  -resample 75
			$images = array();
			
			if ($handle = opendir('../img/')) {
		    	while (false !== ($file = readdir($handle))) {
		    		//echo "<img src=\"img/".$file."\">";
		    		$images[]=$file;
				}
				closedir($handle);
			}
			sort($images,SORT_STRING);
			return true;

	}	
	
	
	function convertFirstPageToImg($bookID){
		
		doLog('begin first page converting to image');
		//==============================================================================//
		// CONVERT MULTI-PAGE PDF TO IMAGES	AND READ FOLDER CONTENTS					//
		//==============================================================================//
			exec("convert -density 288 ../book/".$bookID."_atlas.pdf[0] -scale 25% ../cover/cover.jpg");
			//echo "convert -density 288 ../book/".$bookID."_atlas.pdf[0] -scale 25% ../cover/cover.jpg";
			//-density 72  -resample 75
			$images = array();
			
			if ($handle = opendir('../img/')) {
		    	while (false !== ($file = readdir($handle))) {
		    		//echo "<img src=\"img/".$file."\">";
		    		$images[]=$file;
				}
				closedir($handle);
			}
			sort($images,SORT_STRING);
			doLog('done converting first page to image');
			return true;
	}
	
	
	function convertAppendixToImg($numberOfPages,$bookID){
		
		//==============================================================================//
		// CONVERT MULTI-PAGE PDF TO IMAGES	AND READ FOLDER CONTENTS					//
		//==============================================================================//
			exec("convert -density 75 /home/pcatlas/domains/atlasofpentecostalism.net/public_html/appendices/pdf/appendix_".$bookID.".pdf -scale 100% /home/pcatlas/domains/atlasofpentecostalism.net/public_html/appendices/img/image_".$bookID."_%03d.png");
			//-density 72  -resample 75
			$images = array();
			
			if ($handle = opendir('/home/pcatlas/domains/atlasofpentecostalism.net/public_html/appendices/img/')) {
		    	while (false !== ($file = readdir($handle))) {
		    		//echo "<img src=\"img/".$file."\">";
		    		$images[]=$file;
				}
				closedir($handle);
			}
			sort($images,SORT_STRING);
			return true;
	}
	
function get_spineSize($pages){
	$url = "https://apps.lulu.com/api/pdfgen/covers/v1/calculateSize";

	$data = array(	"numberOfPages" => $pages,
	"color" => false,
	"trimSize" => "US_TRADE",
	"bindingType" => "perfect",
	"paperType" => "regular");
	
	$postData = "api_key=".APIKEY."&data=" . json_encode($data);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/JSON'));
	
	$response = curl_exec($ch);
	curl_close($ch);
	return(json_decode($response, true));
}	

function get_cost($pages, $formatType, $luluFormatID){
	
	if ($formatType=='hardcover')$binding = 'casewrap-hardcover';
	else $binding = 'perfect';
	
	$url = 'https://apps.lulu.com/api/publish/v1/base_cost';
	$physicalAttributes=array(
		'color'=>true,
		'trim_size'=>$luluFormatID,
		'binding_type'=> $binding,
		'paper_type'=> "regular"
	);
	$projectInformation = array(
		'product'=> 'print',
		'currency_code'=> 'EUR',
		'project_type'=>$formatType,
		'physical_attributes'=>$physicalAttributes
	);
	
	$data = array(
		'api_key'=>APIKEY,
		'auth_user'=> '',
		'auth_token'=> AUTHTOKEN,
		'project'=>json_encode($projectInformation)	,
		'page_count'=>$pages
	);
	
	//json_encode($data);
	$handle = curl_init();
	curl_setopt($handle, CURLOPT_VERBOSE, 0);
	curl_setopt($handle, CURLOPT_URL, $url);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handle, CURLOPT_POST, true);
	curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
	curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
	//curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/JSON'));
	
	$result = curl_exec($handle);
	return json_decode($result);	


/*

	$url = "https://apps.lulu.com/api/publish/v1/base_cost";

	$data = array(	"numberOfPages" => $pages,
	"project_type"=>"softcover",
	"color" => false,
	"trimSize" => "POCKET",
	"bindingType" => "perfect",
	"paperType" => "regular");
	
	$postData = "api_key=".APIKEY."auth_user=".USERNAME."&auth_token=".AUTHTOKEN."&page_count=100&project=" . json_encode($data)."";
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/JSON'));
	
	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
*/
}
function get_auth_token(){
	$url = 'https://www.lulu.com/account/endpoints/authenticator.php';
	$data = array(
		'username'=> USERNAME,
		'password'=> PASSWORD,
		'responseType' => 'json'
	);
	
	$handle = curl_init();
	curl_setopt($handle, CURLOPT_URL, $url);
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($handle, CURLOPT_POST, true);
	curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
	curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0); 
	
	$result = json_decode(curl_exec($handle), true);
	if($result['authenticated'] == 1){
		return $result['authToken'];
	}else{
		exit("Authentication failed!");
	}
}

?>