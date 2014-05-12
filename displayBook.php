<?
	function displayBook($numberOfPages,$bookID){
		$output = '';
		$output.= '<div style="font-size:10px; font-family:sans-serif;">';
		$output.= 'bookID= '.$bookID."<br/>";
		$output.= $numberOfPages." pages <br/>";
		$output.= '<a href="../pdfs/book_'.$bookID.'.pdf"">download pdf</a><br/><br/>';
		$output.= '<div style="position:absolute; width:970px; background:grey; padding:20px; text-align:center;">';
		
		for($i=0;$i<$numberOfPages; $i++){
			
			$pageNumber = leading_zero($i, 3, 0);
			if($pageNumber<1){
				$pageNumber="000";
				$output.= '<img src="../emptyPage.png" style="width:450px; margin:10px;">';
			}
			$output.= '<img src="../img/image_'.$bookID.'_'.$pageNumber.'.png" style="margin:10px;">';
			if( $i%2 ==1){
				$paginaNummer = ($i+1)." / ".($i+2);
				$labelY = ($i/2*695)+379;
				$output.= '<div style="position:absolute; top: '.$labelY.'px; right:0px; background-color:white; width:40px; height:40px;">'.$paginaNummer.'</div>';
			}
			if( $i%2 ==1 && $i==$numberOfPages-1)$output.= '<img src="../emptyPage.png" style=" width:450px; margin:10px;">';
		}
		$output.='</div>';
		$output.='</div>';
		return $output;
	}
	
	
	function leading_zero( $aNumber, $intPart, $floatPart=NULL, $dec_point=NULL, $thousands_sep=NULL) {        
		$formattedNumber = $aNumber;
  		if (!is_null($floatPart)) {
    		$formattedNumber = number_format($formattedNumber, $floatPart, $dec_point, $thousands_sep);
    	}
     	$formattedNumber = str_repeat("0",($intPart + -1 - floor(log10($formattedNumber)))).$formattedNumber;
  		return $formattedNumber;
  	}
  
  ?>