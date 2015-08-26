<link rel="stylesheet" href="css/style.css" type="text/css">
<?php $intid=isset($_GET["intid"])?$_GET["intid"]:"" ?>
<?php

class Action { 

	/* Credentials */
	public $servername = "localhost";
	public $username = "root";
	public $password = "";
	public $dbname = "bmby";
	public $generalTable = "mbeat_form";
	public $fieldsTable = "mbeat_form_fields";
	
	/*Structure Functions*/
	function __construct( ){}	
	function before() {
		return new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	}
	function after() {
		return $conn = null;
	}

	/*External DB Functions*/
	function getUsersForDropdown() {
		/*SHADI COMPLETES*/
	}
	function getContentForFieldsDropdown() {
		/*SHADI COMPLETES*/
	}
	
	/*API Functions*/
	/*-------------*/
	/*Form Manipulations*/ 	   
    function addNewForm($conn, $intid) {
	    try {
			    // set the PDO error mode to exception
			    $sql = "INSERT INTO $this->generalTable (intid) VALUES (:intid)";
							    // use exec() because no results are returned
				 $conn->exec($sql);
			}
			catch(PDOException $e) {
			    echo $sql . "<br>" . $e->getMessage();
			}
	}
	function retrieveFormData($conn, $intid) {
		try {
			$ft = $this->fieldsTable;
			$gt = $this->generalTable;
			$stmt = $conn->prepare("SELECT $ft.intid, $ft.fieldType, $ft.fieldLabel, $ft.fieldname, $ft.optionalVals, $ft.isMandatory,
					$ft.isHidden, $ft.validOptions, $ft.dateUpdate, $ft.userUpdate, $gt.greetingsUrl
					FROM $ft
					JOIN $gt
					ON $ft.intid = $gt.intid
					WHERE $ft.intid=$intid");
			$stmt->execute();
			foreach(new RecursiveArrayIterator($stmt->fetchAll()) as $k=>$v) {
				print_r($v);
			}
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
	}
	function showAllForms($conn) {
		$stmt = $conn->prepare("SELECT intid from $this->generalTable");
		$stmt->execute();
		foreach (new RecursiveArrayIterator($stmt->fetchAll()) as $k=>$v){
			$this->retrieveFormData($conn, $v["intid"]);
		}
	}

	
	/*Update&Save Screens*/
    function updateFormScreen1($conn, $intid, $landingPageName, $landingPageUrl, $media, $handleReLid) { 
	    try {
		    // set the PDO error mode to exception
		    $sql = "UPDATE $this->generalTable SET landingPageName=:landingPageName, landingPageUrl=:landingPageUrl, media:=media, handleReLid=:handleReLid WHERE intid=:intid";
		    // Prepare statement
		    $stmt = $conn->prepare($sql);
		    // execute the query
		    $stmt->execute();
		    }
		catch(PDOException $e)
		    {
		    echo $sql . "<br>" . $e->getMessage();
		    }
    } 	
    function updateFormScreen2($conn, $intid, $buttonText, $sms, $mail, $deliveryText, $linkToCondTerms, $nameOfCondTerms) {
    	    try {
		    // set the PDO error mode to exception
		    $sql = "UPDATE $this->generalTable SET buttonText=:buttonText, sms=:sms, mail:=mail, deliveryText=:deliveryText, linkToCondTerms=:linkToCondTerms, nameOfCondTerms=:nameOfCondTerms WHERE intid=:intid";
		    // Prepare statement
		    $stmt = $conn->prepare($sql);
		    // execute the query
		    $stmt->execute();
		    }
		catch(PDOException $e)
		    {
		    echo $sql . "<br>" . $e->getMessage();
		    }
    }   
    function updateFormScreen3($conn, $RTL, $LOP, $buttonFont, $buttonFontColor, $buttonColor, $buttonImgId, $formWidth, $formBG, $formBorderWidth, $formFrameColor, $fontSize, $fontColor, $fieldWidth, $fieldBG, $fieldBorderWidth, $fieldFrameColor, $fieldFormSize, $fieldFormColor, $cssInput) {
        try {
		    // set the PDO error mode to exception
		    $sql = "UPDATE $this->generalTable SET RTL=:RTL, LOP=:LOP, buttonFont:=buttonFont, buttonFontColor=:buttonFontColor, buttonColor=:buttonColor, buttonImgId=:buttonImgId, formWidth=:formWidth, formBG=:formBG, formBorderWidth=:formBorderWidth, formFrameColor=:formFrameColor, fontSize=:fontSize, fontColor=:fontColor, fieldWidth=:fieldWidth, fieldBG=:fieldBG, fieldBorderWidth=:fieldBorderWidth, fieldFrameColor=:fieldFrameColor, fieldFormSize=:fieldFormSize, fieldFormColor=:fieldFormColor, cssInput=:cssInput WHERE intid=:intid";
		    // Prepare statement
		    $stmt = $conn->prepare($sql);
		    // execute the query
		    $stmt->execute();
		    }
		catch(PDOException $e)
		    {
		    echo $sql . "<br>" . $e->getMessage();
		    }
    }    
    function updateFormScreen4($conn, $campaignName, $adsGroup, $creativeName, $refsMedia, $refsAddOns, $refsLinks) {
        try {
		    // set the PDO error mode to exception
		    $sql = "UPDATE $this->generalTable SET campaignName=:campaignName, adsGroup=:adsGroup, creativeName:=creativeName, refsMedia=:refsMedia, refsAddOns=:refsAddOns, refsLinks=:refsLinks WHERE intid=:intid";
		    // Prepare statement
		    $stmt = $conn->prepare($sql);
		    // execute the query
		    $stmt->execute();
		    }
		catch(PDOException $e)
		    {
		    echo $sql . "<br>" . $e->getMessage();
		    }
    }    
    function updateFieldsValues($conn, $id, $intid, $fieldType, $fieldLabel, $fieldname, $optionalVals, $isMandatory, $isHidden, $validOptions) {
    	try {
    		// set the PDO error mode to exception
    		$sql = "UPDATE $this->fieldsTable SET intid=:intid, fieldType:=fieldType, fieldLabel=:fieldLabel, fieldname=:fieldname, optionalVals=:optionalVals, isMandatory=:isMandatory, isHidden=:isHidden, validOptions=:validOptions WHERE id=:id";
    		// Prepare statement
    		$stmt = $conn->prepare($sql);
    		// execute the query
    		$stmt->execute();
    	}
    	catch(PDOException $e)
    	{
    		echo $sql . "<br>" . $e->getMessage();
    	}
    }

    /*Generation Functions*/
    function generateFormJSScript($conn, $intid) {
    	//echo '<script type="text/javascript">';
		echo "if (str.length==0) { 
				    document.getElementById('currentForm').innerHTML='';
				    return;
				} else {
				    var xmlhttp=new XMLHttpRequest();
				    xmlhttp.onreadystatechange=function() {
				        if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				            document.getElementById('currentForm').innerHTML=xmlhttp.responseText;
				        }
				    }
				    xmlhttp.open('GET','domFromDB.php?intid='$intid,true);
				    xmlhttp.send();
				}";

    }	
	function generateFormHTMLScript($conn, $intid){
		try {
		    $stmt = $conn->prepare("SELECT intid, fieldType, fieldLabel, fieldname, optionalVals, isMandatory, isHidden, validOptions, dateUpdate, userUpdate FROM $this->fieldsTable WHERE intid=$intid");
		    $stmt->execute();
			$allVals = "";
			/*Special Values*/
			//greetingsAddress
			$greetingsAddress = $conn->prepare("SELECT greetingsUrl FROM $this->generalTable WHERE intid=$intid");
			$greetingsAddress->execute();
			$gAddCol = $greetingsAddress->fetchColumn();
			//CSS Code
			$cssCode = $conn->prepare("SELECT cssInput FROM $this->generalTable WHERE intid=$intid");
			$cssCode->execute();
			$cssToInject = $cssCode->fetchColumn();
			//Button Text
			$bText = $conn->prepare("SELECT buttonText FROM $this->generalTable WHERE intid=$intid");
			$bText->execute();
			$bt = $bText->fetchColumn();
			//Condition Terms - Name
			$ctn = $conn->prepare("SELECT nameOfCondTerms FROM $this->generalTable WHERE intid=$intid");
			$ctn->execute();
			$ctName = $ctn->fetchColumn();
			//Condition Terms - Link
			$ctl = $conn->prepare("SELECT linkToCondTerms FROM $this->generalTable WHERE intid=$intid");
			$ctl->execute();
			$ctLink = $ctl->fetchColumn();
			//Delivery Text
			$dt = $conn->prepare("SELECT deliveryText FROM $this->generalTable WHERE intid=$intid");
			$dt->execute();
			$dText = $dt->fetchColumn();
			//RTL
			$rtl = $conn->prepare("SELECT RTL FROM $this->generalTable WHERE intid=$intid");
			$rtl->execute();
			$formDirection = $rtl->fetchColumn();
			if($formDirection == 'rtl')
				$formCSSCode = "body {direction: rtl !important;}";
			else $formCSSCode = "body {direction: ltr !important;}";
			//LOP
			$lop = $conn->prepare("SELECT LOP FROM $this->generalTable WHERE intid=$intid");
			$lop->execute();
			$orientation = $lop->fetchColumn();
			if($orientation == 'land')
				$formCSSCode .= "";//complete
			else $formCSSCode .= "";//complete
			//Button Font
			$bf = $conn->prepare("SELECT buttonFont FROM $this->generalTable WHERE intid=$intid");
			$bf->execute();
			$bFont = $bf->fetchColumn();
			$formCSSCode .= ".button {font-family: ".$bFont." !important;}";
			//Button Font Color
			$bfc = $conn->prepare("SELECT buttonFontColor FROM $this->generalTable WHERE intid=$intid");
			$bfc->execute();
			$bFontColor = $bfc->fetchColumn();
			if($bFontColor == 'trans')
				$formCSSCode .= ".button {color: transparent !important;}";
			else	
				$formCSSCode .= ".button {color: red !important;}";
			//Button BG Color
			$bbg = $conn->prepare("SELECT buttonColor FROM $this->generalTable WHERE intid=$intid");
			$bbg->execute();
			$buttonBgColor = $bbg->fetchColumn();
			if($buttonBgColor == 'trans')
				$formCSSCode .= ".button {background: transparent !important;}";
			else
				$formCSSCode .= ".button {background: green !important;}";
			//Form Width
			$fw = $conn->prepare("SELECT formWidth FROM $this->generalTable WHERE intid=$intid");
			$fw->execute();
			$fWidth = $fw->fetchColumn();
			$formCSSCode .= ".centerred {max-width: ".$fWidth."px !important;}";
			//Form Width
			$fbg = $conn->prepare("SELECT formBG FROM $this->generalTable WHERE intid=$intid");
			$fbg->execute();
			$fBackground = $fbg->fetchColumn();
			$formCSSCode .= ".centerred {background: ".$fBackground." !important;}";
			//Form Border Width
			$fbw = $conn->prepare("SELECT formBorderWidth FROM $this->generalTable WHERE intid=$intid");
			$fbw->execute();
			$fBorderWidth = $fbw->fetchColumn();
			$formCSSCode .= ".centerred {border-width: ".$fBorderWidth."px !important; border-style: solid;}";
			//Form Frame Color
			$ffc = $conn->prepare("SELECT formFrameColor FROM $this->generalTable WHERE intid=$intid");
			$ffc->execute();
			$fFrameColor = $ffc->fetchColumn();
			$formCSSCode .= ".centerred {border-color: ".$fFrameColor." !important;}";
			//Font Size
			$fs = $conn->prepare("SELECT fontSize FROM $this->generalTable WHERE intid=$intid");
			$fs->execute();
			$fontSize = $fs->fetchColumn();
			$formCSSCode .= ".centerred {font-size: ".$fontSize."px !important;}";
			//Font Color
			$fc = $conn->prepare("SELECT fontColor FROM $this->generalTable WHERE intid=$intid");
			$fc->execute();
			$fontColor = $fc->fetchColumn();
			$formCSSCode .= ".centerred {color: ".$fontSize." !important;}";
			//Field Width
			$fieldW = $conn->prepare("SELECT fieldWidth FROM $this->generalTable WHERE intid=$intid");
			$fieldW->execute();
			$fieldWidth = $fieldW->fetchColumn();
			$formCSSCode .= ".input {max-width: ".$fieldWidth."px !important;}";
			//Field BG
			$fieldBG = $conn->prepare("SELECT fieldBG FROM $this->generalTable WHERE intid=$intid");
			$fieldBG->execute();
			$fieldBackground = $fieldBG->fetchColumn();
			$formCSSCode .= ".input {background: ".$fieldBackground." !important;}";
			//Field Border Width
			$fieldBW = $conn->prepare("SELECT fieldBorderWidth FROM $this->generalTable WHERE intid=$intid");
			$fieldBW->execute();
			$fieldBWidth = $fieldBW->fetchColumn();
			$formCSSCode .= ".input {max-width: ".$fieldBWidth."px !important;}";
			//Field Frame Color
			$fieldFC = $conn->prepare("SELECT fieldFrameColor FROM $this->generalTable WHERE intid=$intid");
			$fieldFC->execute();
			$fieldFColor = $fieldFC->fetchColumn();
			$formCSSCode .= ".input {border-color: ".$fieldFColor." !important; border-style: solid;}";
			//Field Form Size
			$fieldFS = $conn->prepare("SELECT fieldFormSize FROM $this->generalTable WHERE intid=$intid");
			$fieldFS->execute();
			$fieldFSize = $fieldFS->fetchColumn();
			$formCSSCode .= ".input {max-width: ".$fieldFSize."px !important;}";
			//Field Form Color
			$fieldFCol = $conn->prepare("SELECT fieldFormColor FROM $this->generalTable WHERE intid=$intid");
			$fieldFCol->execute();
			$fieldFColor = $fieldFCol->fetchColumn();
			$formCSSCode .= ".input {color: ".$fieldFColor." !important;}";
			//Apply CSS on Form
			echo "<style>".$formCSSCode."</style>";
		    // set the resulting array to associative
		    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
		    echo '<form action="'.$gAddCol.'" method="post" class="STYLE-NAME"><div class="centerred elegant-aero"><h1>טופס לדוגמא<span>אנא הזן את פרטיך:</span></h1>';
		    foreach(new RecursiveArrayIterator($stmt->fetchAll()) as $k=>$v) {
		    		$optValsStr = $v["optionalVals"];
		    		$optArray = explode('|', $optValsStr);
		    		$currClasses = $this->fieldChecker($v["isMandatory"], $v["isHidden"]);
		    		echo '<label class="fieldLabel '.$currClasses.'">'.$v["fieldLabel"].' ';
		   			switch ($v["fieldType"]) {
		   				case "textarea":
		   					echo '<div class="textarea"><textarea></textarea></div><br><br>';
		   					break;
		   				case "input":
		   					$currClasses = $this->fieldChecker($v["isMandatory"], $v["isHidden"]);
		   					echo '<input class="input"><br><br>';
		   					break;
		   				case "radio":
		   					$currClasses = $this->fieldChecker($v["isMandatory"], $v["isHidden"]);
		   					foreach($optArray as $value)
		   					{
		   						echo '<input type="radio" name="radioOptions" value="'.$value.'">'.$value;
		   					}
		   					echo '<br><br>';
		   					break;
		   				case "checkbox":
		   					$currClasses = $this->fieldChecker($v["isMandatory"], $v["isHidden"]);
							foreach($optArray as $value)
							{
							    echo '<input type="checkbox" name="checkboxOptions" value="'.$value.'">'.$value;
							}
							echo '<br><br>';
		   					break;
		   				case "dropdown":
		   					$currClasses = $this->fieldChecker($v["isMandatory"], $v["isHidden"]);
		   					echo '<select class="dropdown">';
		    				foreach($optArray as $value)
		    				{
		    					echo '<option value="'.$value.'">'.$value.'</option>';
		    				}
		    				echo '</select><br><br>';
		   					break;
		   				default:
		   					echo "";
		   			} 
					echo "</label><br>";
		    }
		}
		catch(PDOException $e) {
		    echo "Error: " . $e->getMessage();
		}
		echo '<br><br><label><span>&nbsp;</span><input type="submit" class="button" value="'.$bt.'" /></label>';
		echo '<br><br><a href="www.google.co.il">'.$ctName.'</a>';
		echo '<br><br><label><span>&nbsp;</span>'.$dText.'</label>';
		echo '</div></form>';
		}	
	
	/*Validation Functions*/
	function fieldChecker($mandatory, $hidden) {
			$finalClasses = "";
			if ($mandatory == 1)
				$finalClasses = "mandatory ";
			if ($hidden == 1)
				$finalClasses .= "hidden";
			return $finalClasses;
		}

}


/*MAIN*/
$mainAction = new Action();
$conn = $mainAction->before();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$mainAction->generateFormHTMLScript($conn, $intid);
$conn = $mainAction->after();

?>