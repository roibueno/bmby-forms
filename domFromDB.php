<?php
namespace Mbeat\Interfaces;

class Forms { 

	/* Credentials */
	public $generalTable = "mbeat_form";
	public $fieldsTable = "mbeat_form_fields";
	public $refsTable = "mbeat_refs_fields";
	public $usersTable = "mbeat_users";
	private $arrPost;
	private $conn;
	
	/* 
	public $servername = "tigris";
	public $username = "bmbyforms";
	public $password = "bmbyforms";
	public $dbname = "bmbyforms"; 
	*/
	
	public $servername = "localhost";
	public $username = "root";
	public $password = "";
	public $dbname = "bmby";
	
	/*Structure Functions*/
	function __construct($arrPost = null ){ 
        $this->arrPost = $arrPost;
        //$this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        //$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }	
    
    public function Run($action, $args = null){
	    if(method_exists($this, $action)){
	      $data = $this->$action($args);
	      switch($action){
	        case 'test':
	        case 'before':
		    case 'after':
	        case 'addNewForm':
	        case 'showAllForms':
	        case 'retrieveFormData':
	        case 'updateFormScreen1':
	        case 'updateFormScreen2':
	        case 'updateFormScreen3':
	        case 'updateFormScreen4':
	        case 'updateFieldsValues':
	        case 'generateFormJSScript':
	        case 'generateFormHTMLScript':
	        case 'fieldChecker':
	        case 'insertHTMLCodeToDB':
	        case 'insertJSCodeToDB':
	        case 'resetStyleButton':
	        case 'addNewRefLink':
	        case 'showAlllinks':
	        /*case 'delete':
	        case 'UploadFile':
	        case 'GetFilterCount':
	        case 'SendOnlyToProjectUsers': 
	        case 'AllowSendSms':*/
	          return $data;
	          break;
	      }
	    }
  }
  
    private function before() {
       $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
 
    }

	private function after() {
		return $this->conn = null;
	}

	/*External DB Functions*/
	private function getUsersData() {
		/*SHADI COMPLETES*/
	}
	private function getContentForFieldsDropdown() {
		/*SHADI COMPLETES*/
	}
	
    private function test() {
		/*SHADI COMPLETES*/
        echo 1;
	}
	
	/*API Functions*/
	/*-------------*/
	/*Form Manipulations*/    
    private function addNewForm() {
        $intid = $this->arrPost["intid"];
	    try {
			    // set the PDO error mode to exception
			    $sql = "INSERT INTO $this->generalTable (intid) VALUES (:intid)";
							    // use exec() because no results are returned
				 $this->conn->exec($sql);
			}
			catch(PDOException $e) {
			    echo $sql . "<br>" . $e->getMessage();
			}
	}
	private function showAllForms() {
		$stmt = $this->conn->prepare("SELECT intid from $this->generalTable");
		$stmt->execute();
		foreach (new RecursiveArrayIterator($stmt->fetchAll()) as $k=>$v){
			$this->retrieveFormData($this->conn, $v["intid"]);
		}

	}
	private function retrieveFormData() {
        $intid = $this->arrPost["intid"];
	    try {	
			$ft = $this->fieldsTable;
			$gt = $this->generalTable;
		    $stmt = $this->conn->prepare("SELECT $ft.intid, $ft.fieldType, $ft.fieldLabel, $ft.fieldname, $ft.optionalVals, $ft.isMandatory, 
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

    function addNewRefLink() {
        $intid = $this->arrPost["intid"];
        $refsMedia = $this->arrPost["refsMedia"];
        $refsAddOns = $this->arrPost["refsAddOns"];
        $refsLinks = $this->arrPost["refsLinks"];
        try {
            // set the PDO error mode to exception
            $sql = "INSERT INTO $this->refsTable (intid, refsMedia, refsAddOns, refsLinks) VALUES ('$intid', '$refsMedia', '$refsAddOns', '$refsLinks')";
            // use exec() because no results are returned
            $this->conn->exec($sql);
        }
        catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }   
    function showAlllinks() {
        $intid = $this->arrPost["intid"];
        $stmt = $this->conn->prepare("SELECT * from $this->refsTable WHERE intid=$ intid");
        $stmt->execute();
        foreach (new RecursiveArrayIterator($stmt->fetchAll()) as $k=>$v){
            print_r($v);
        }
    }
	
	/*Update&Save Screens*/
    private function updateFormScreen1() { 
	    $intid = $this->arrPost["intid"];
        $landingPageName = $this->arrPost["landingPageName"];
        $landingPageUrl = $this->arrPost["landingPageUrl"];
        $media = $this->arrPost["media"];
        $handleReLid = $this->arrPost["handleReLid"];
        try {
		    // set the PDO error mode to exception
		    $sql = "UPDATE $this->generalTable SET landingPageName=:landingPageName, landingPageUrl=:landingPageUrl, media:=media, handleReLid=:handleReLid WHERE intid=:intid";
		    // Prepare statement
		    $stmt = $this->conn->prepare($sql);
		    // execute the query
		    $stmt->execute();
		    }
		catch(PDOException $e)
		    {
		    echo $sql . "<br>" . $e->getMessage();
		    }
    } 	
    private function updateFormScreen2() {
    	    $intid = $this->arrPost["intid"];
            $buttonText = $this->arrPost["buttonText"];
            $sms = $this->arrPost["sms"];
            $deliveryText = $this->arrPost["deliveryText"];
            $mail = $this->arrPost["mail"];
            $linkToCondTerms = $this->arrPost["linkToCondTerms"];            
            $nameOfCondTerms = $this->arrPost["nameOfCondTerms"];

            try {
		    // set the PDO error mode to exception
		    $sql = "UPDATE $this->generalTable SET buttonText=:buttonText, sms=:sms, mail:=mail, deliveryText=:deliveryText, linkToCondTerms=:linkToCondTerms, nameOfCondTerms=:nameOfCondTerms WHERE intid=:intid";
		    // Prepare statement
		    $stmt = $this->conn->prepare($sql);
		    // execute the query
		    $stmt->execute();
		    }
		catch(PDOException $e)
		    {
		    echo $sql . "<br>" . $e->getMessage();
		    }
    }   
    private function updateFormScreen3() {
        $RTL = $this->arrPost["RTL"];
        $LOP = $this->arrPost["LOP"];
        $buttonFont = $this->arrPost["buttonFont"];
        $buttonFontColor = $this->arrPost["buttonFontColor"];
        $buttonColor = $this->arrPost["buttonColor"];
        $buttonImgId = $this->arrPost["buttonImgId"];            
        $formWidth = $this->arrPost["formWidth"];
        $formBG = $this->arrPost["formBG"];
        $formBorderWidth = $this->arrPost["formBorderWidth"];
        $formFrameColor = $this->arrPost["formFrameColor"];
        $fontSize = $this->arrPost["fontSize"];
        $fontColor = $this->arrPost["fontColor"];
        $fieldWidth = $this->arrPost["fieldWidth"];            
        $fieldBG = $this->arrPost["fieldBG"];
        $fieldBorderWidth = $this->arrPost["fieldBorderWidth"];
        $fieldFrameColor = $this->arrPost["fieldFrameColor"];
        $fieldFormSize = $this->arrPost["fieldFormSize"];
        $fieldFormColor = $this->arrPost["fieldFormColor"];            
        $cssInput = $this->arrPost["cssInput"];
        try {
		    // set the PDO error mode to exception
		    $sql = "UPDATE $this->generalTable SET RTL=:RTL, LOP=:LOP, buttonFont:=buttonFont, buttonFontColor=:buttonFontColor, buttonColor=:buttonColor, buttonImgId=:buttonImgId, formWidth=:formWidth, formBG=:formBG, formBorderWidth=:formBorderWidth, formFrameColor=:formFrameColor, fontSize=:fontSize, fontColor=:fontColor, fieldWidth=:fieldWidth, fieldBG=:fieldBG, fieldBorderWidth=:fieldBorderWidth, fieldFrameColor=:fieldFrameColor, fieldFormSize=:fieldFormSize, fieldFormColor=:fieldFormColor, cssInput=:cssInput WHERE intid=:intid";
		    // Prepare statement
		    $stmt = $this->conn->prepare($sql);
		    // execute the query
		    $stmt->execute();
		    }
		catch(PDOException $e)
		    {
		    echo $sql . "<br>" . $e->getMessage();
		    }
    }    
    private function updateFormScreen4() {
        $campaignName = $this->arrPost["campaignName"];
        $creativeName = $this->arrPost["creativeName"];
        $adsGroup = $this->arrPost["adsGroup"];
        $refsMedia = $this->arrPost["refsMedia"];
        $refsAddOns = $this->arrPost["refsAddOns"];
        $refsLinks = $this->arrPost["refsLinks"];  
        try {
		    // set the PDO error mode to exception
		    $sql = "UPDATE $this->generalTable SET campaignName=:campaignName, adsGroup=:adsGroup, creativeName:=creativeName, refsMedia=:refsMedia, refsAddOns=:refsAddOns, refsLinks=:refsLinks WHERE intid=:intid";
		    // Prepare statement
		    $stmt = $this->conn->prepare($sql);
		    // execute the query
		    $stmt->execute();
		    }
		catch(PDOException $e)
		    {
		    echo $sql . "<br>" . $e->getMessage();
		    }
    }    
    private function updateFieldsValues() {
    	$intid = $this->arrPost["intid"];
        $id = $this->arrPost["id"];
        $fieldType = $this->arrPost["fieldType"];
        $fieldLabel = $this->arrPost["fieldLabel"];
        $fieldname = $this->arrPost["fieldname"];
        $optionalVals = $this->arrPost["optionalVals"];
        $isMandatory = $this->arrPost["isMandatory"];
        $isHidden = $this->arrPost["isHidden"];
        $validOptions = $this->arrPost["validOptions"];  
        try {
    		// set the PDO error mode to exception
    		$sql = "UPDATE $this->fieldsTable SET intid=:intid, fieldType:=fieldType, fieldLabel=:fieldLabel, fieldname=:fieldname, optionalVals=:optionalVals, isMandatory=:isMandatory, isHidden=:isHidden, validOptions=:validOptions WHERE id=:id";
    		// Prepare statement
    		$stmt = $this->conn->prepare($sql);
    		// execute the query
    		$stmt->execute();
    	}
    	catch(PDOException $e)
    	{
    		echo $sql . "<br>" . $e->getMessage();
    	}
    }

    /*Generation Functions*/
    private function generateFormJSScript() {
    	//echo '<script type="text/javascript">';
        $intid = $this->arrPost["intid"];
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
	private function generateFormHTMLScript(){
		$intid = $this->arrPost["intid"];
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
			if($formDirection == 'rtl' || $formDirection == 'RTL')
				$formCSSCode = "body {direction: rtl !important;}";
			else $formCSSCode = "body {direction: ltr !important;}";
			//LOP
			$lop = $conn->prepare("SELECT LOP FROM $this->generalTable WHERE intid=$intid");
			$lop->execute();
			$orientation = $lop->fetchColumn();
			if($orientation == 'landscape' || $orientation == 'LANDSCAPE')
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
			if($bFontColor == 'transparent' || $bFontColor == 'TRANSPARENT')
				$formCSSCode .= ".button {color: transparent !important;}";
			else	
				$formCSSCode .= ".button {color: ".$bFontColor." !important;}";
			//Button BG Color
			$bbg = $conn->prepare("SELECT buttonColor FROM $this->generalTable WHERE intid=$intid");
			$bbg->execute();
			$buttonBgColor = $bbg->fetchColumn();
			if($buttonBgColor == 'transparent' || $buttonBgColor == 'TRANSPARENT')
				$formCSSCode .= ".button {background: transparent !important;}";
			else
				$formCSSCode .= ".button {background: ".$buttonBgColor." !important;}";
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
			$formCSSCode .= ".centerred {color: ".$fontColor." !important;}";
			//Field Width
			$fieldW = $conn->prepare("SELECT fieldWidth FROM $this->generalTable WHERE intid=$intid");
			$fieldW->execute();
			$fieldWidth = $fieldW->fetchColumn();
			$formCSSCode .= ".textarea {width: ".$fieldWidth."px !important;}.input {width: ".$fieldWidth."px !important;}.radio {width: ".$fieldWidth."px !important;}.checkbox {width: ".$fieldWidth."px !important;}.dropdown {width: ".$fieldWidth."px !important;}.input {width: ".$fieldWidth."px !important;}";
			//Field BG
			$fieldBG = $conn->prepare("SELECT fieldBG FROM $this->generalTable WHERE intid=$intid");
			$fieldBG->execute();
			$fieldBackground = $fieldBG->fetchColumn();
			$formCSSCode .= ".textarea {background: ".$fieldBackground." !important;}.input {background: ".$fieldBackground." !important;}.radio {background: ".$fieldBackground." !important;}.checkbox {background: ".$fieldBackground." !important;}.dropdown {background: ".$fieldBackground." !important;}";
			//Field Border Width
			$fieldBW = $conn->prepare("SELECT fieldBorderWidth FROM $this->generalTable WHERE intid=$intid");
			$fieldBW->execute();
			$fieldBWidth = $fieldBW->fetchColumn();
			$formCSSCode .= ".textarea {border-width: ".$fieldBWidth."px !important;}.input {border-width: ".$fieldBWidth."px !important;}.radio {border-width: ".$fieldBWidth."px !important;}.checkbox {border-width: ".$fieldBWidth."px !important;}.dropdown {border-width: ".$fieldBWidth."px !important;}";
			//Field Frame Color
			$fieldFC = $conn->prepare("SELECT fieldFrameColor FROM $this->generalTable WHERE intid=$intid");
			$fieldFC->execute();
			$fieldFColor = $fieldFC->fetchColumn();
			$formCSSCode .= ".textarea {border-color: ".$fieldFColor." !important; border-style: solid;}.input {border-color: ".$fieldFColor." !important; border-style: solid;}.radio {border-color: ".$fieldFColor." !important; border-style: solid;}.checkbox {border-color: ".$fieldFColor." !important; border-style: solid;}.dropdown {border-color: ".$fieldFColor." !important; border-style: solid;}";
			//Field Form Size
			$fieldFS = $conn->prepare("SELECT fieldFormSize FROM $this->generalTable WHERE intid=$intid");
			$fieldFS->execute();
			$fieldFSize = $fieldFS->fetchColumn();
			$formCSSCode .= ".textarea {font-size: ".$fieldFSize."px !important;}.input {font-size: ".$fieldFSize."px !important;}.radio {font-size: ".$fieldFSize."px !important;}.checkbox {font-size: ".$fieldFSize."px !important;}.dropdown {font-size: ".$fieldFSize."px !important;}";
			//Field Form Color
			$fieldFCol = $conn->prepare("SELECT fieldFormColor FROM $this->generalTable WHERE intid=$intid");
			$fieldFCol->execute();
			$fieldFColor = $fieldFCol->fetchColumn();
			$formCSSCode .= ".textarea {color: ".$fieldFColor." !important;}.input {color: ".$fieldFColor." !important;}.radio {color: ".$fieldFColor." !important;}.checkbox {color: ".$fieldFColor." !important;}.dropdown {color: ".$fieldFColor." !important;}";
			//Apply CSS on Form
			echo "<style>".$formCSSCode."</style>";
		    // set the resulting array to associative
		    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
		    echo '<form action="'.$gAddCol.'" method="post" class="STYLE-NAME"><div class="centerred default-theme"><h1>טופס לדוגמא<span>אנא הזן את פרטיך:</span></h1>';
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
		echo '<br><br><a href="'.$ctLink.'">'.$ctName.'</a>';
		echo '<br><br><label><span>&nbsp;</span>'.$dText.'</label>';
		echo '</div></form>';
		}	
	
    // Notice: when you run insertHTMLCodeToDB($conn, $intid, $formHTMLCode) where 
    // $formHTMLCode = generateFormHTMLScript($conn, $intid), you get the required result
    function insertHTMLCodeToDB() {
        $intid = $this->arrPost["intid"];
        $formHTMLCode = $this->arrPost["formHTMLCode"];
        try {
            // set the PDO error mode to exception
            $sql = "UPDATE $this->generalTable SET formHTMLCode=:formHTMLCode WHERE intid=:intid";
            // Prepare statement
            $stmt = $this->conn->prepare($sql);
            // execute the query
            $stmt->execute();
        }
        catch(PDOException $e)
        {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    // Notice: when you run insertJSCodeToDB($conn, $intid, $formHTMLCode) where
    // $formJSCode = generateFormJSScript($conn, $intid), you get the required result
    function insertJSCodeToDB() {
        $intid = $this->arrPost["intid"];
        $formJSCode = $this->arrPost["formJSCode"];
        try {
            // set the PDO error mode to exception
            $sql = "UPDATE $this->generalTable SET formJSCode=:formJSCode WHERE intid=:intid";
            // Prepare statement
            $stmt = $this->conn->prepare($sql);
            // execute the query
            $stmt->execute();
        }
        catch(PDOException $e)
        {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
    //Reset Button - Server-Side functionality
    private function resetStyleButton() {
        $RTL = $this->arrPost["RTL"];
        $LOP = $this->arrPost["LOP"];
        $buttonFont = $this->arrPost["buttonFont"];
        $buttonFontColor = $this->arrPost["buttonFontColor"];
        $buttonColor = $this->arrPost["buttonColor"];
        $formWidth = $this->arrPost["formWidth"];
        $formBG = $this->arrPost["formBG"];
        $formBorderWidth = $this->arrPost["formBorderWidth"];
        $formFrameColor = $this->arrPost["formFrameColor"];
        $fontSize = $this->arrPost["fontSize"];
        $fontColor = $this->arrPost["fontColor"];
        $fieldWidth = $this->arrPost["fieldWidth"];            
        $fieldBG = $this->arrPost["fieldBG"];
        $fieldBorderWidth = $this->arrPost["fieldBorderWidth"];
        $fieldFrameColor = $this->arrPost["fieldFrameColor"];
        $fieldFormSize = $this->arrPost["fieldFormSize"];
        $fieldFormColor = $this->arrPost["fieldFormColor"];            
        $cssInput = $this->arrPost["cssInput"];
        try {
		    // set the PDO error mode to exception
		    $sql = "UPDATE $this->generalTable SET RTL='rtl', LOP='landscape', buttonFont='Arial', buttonFontColor='white', buttonColor='#9ACA26', formWidth='625px', formBG='#FC464A', formBorderWidth='10px', formFrameColor='#000000', fontSize='15px', fontColor='#000000', fieldWidth=:'230px', fieldBG='#F7F7F7', fieldBorderWidth='1px', fieldFrameColor='#FC464A', fieldFormSize='15px', fieldFormColor='#FC464A', cssInput='' WHERE intid=:intid";
		    // Prepare statement
		    $stmt = $this->conn->prepare($sql);
		    // execute the query
		    $stmt->execute();
		    }
		catch(PDOException $e)
		    {
		    echo $sql . "<br>" . $e->getMessage();
		    }
    } 

	/*Validation Functions*/
	private function fieldChecker() {
        $intid = $this->arrPost["intid"];
        $mandatory = $this->arrPost["mandatory"];
        $hidden = $this->arrPost["hidden"];
			$finalClasses = "";
			if ($mandatory == 1)
				$finalClasses = "mandatory ";
			if ($hidden == 1)
				$finalClasses .= "hidden";
			return $finalClasses;
		}

}


/*MAIN*/
/* $mainObj = new Forms();
$conn = $mainObj->before();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$mainObj->generateFormHTMLScript($conn, 1);
$conn = $mainObj->after(); */

?>

<!-- Default Theme - CSS for Form -->
<style>
.hidden{
	display: none !important;
}
.mandatory{
	box-shadow: 4px 4px 20px rgba(200, 0, 0, 0.85);
	padding: 10px;
}
.mandatory:focus {
	border: 1px solid red;
	outline: none;
}
.mandatory:hover {
	opacity: 1;
}
.default-theme {
	margin-left:auto;
	margin-right:auto;
	max-width: 500px;
	background: #D2E9FF;
	padding: 20px 20px 20px 20px;
	font: 12px Arial, Helvetica, sans-serif;
	color: #666;
}
.default-theme h1 {
	font: 24px "Trebuchet MS", Arial, Helvetica, sans-serif;
	padding: 10px 10px 10px 20px;
	display: block;
	background: #C0E1FF;
	border-bottom: 1px solid #B8DDFF;
	margin: -20px -20px 15px;
}
.default-theme h1>span {
	display: block;
	font-size: 11px;
}

.default-theme label>span {
	float: left;
	margin-top: 10px;
	color: #5E5E5E;
}
.default-theme label {
	display: block;
	margin: 0px 0px 5px;
}
.default-theme label>span {
	float: left;
	width: 20%;
	text-align: right;
	padding-right: 15px;
	margin-top: 10px;
	font-weight: bold;
}
.default-theme input[type="text"], .default-theme input[type="email"], .default-theme textarea, .default-theme select {
	color: #888;
	width: 70%;
	padding: 0px 0px 0px 5px;
	border: 1px solid #C5E2FF;
	background: #FBFBFB;
	outline: 0;
	-webkit-box-shadow:inset 0px 1px 6px #ECF3F5;
	box-shadow: inset 0px 1px 6px #ECF3F5;
	font: 200 12px/25px Arial, Helvetica, sans-serif;
	height: 30px;
	line-height:15px;
	margin: 2px 6px 16px 0px;
}
.default-theme textarea{
	height:100px;
	padding: 5px 0px 0px 5px;
	width: 70%;
}
.default-theme select {
	background: #fbfbfb url('down-arrow.png') no-repeat right;
	background: #fbfbfb url('down-arrow.png') no-repeat right;
	appearance:none;
	-webkit-appearance:none;
	-moz-appearance: none;
	text-indent: 0.01px;
	text-overflow: '';
	width: 70%;
}
.default-theme .button{
	padding: 10px 30px 10px 30px;
	background: #66C1E4;
	border: none;
	color: #FFF;
	box-shadow: 1px 1px 1px #4C6E91;
	-webkit-box-shadow: 1px 1px 1px #4C6E91;
	-moz-box-shadow: 1px 1px 1px #4C6E91;
	text-shadow: 1px 1px 1px #5079A3;

}
.default-theme .button:hover{
	background: #3EB1DD;
}
</style>