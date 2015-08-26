<link rel="stylesheet" href="css/style.css" type="text/css">
<?php $intid=isset($_GET["intid"])?$_GET["intid"]:"" ?>
<?php

class Action { 

	/* Credentials */
	public $servername = "tigris";
	public $username = "bmbyforms";
	public $password = "bmbyforms";
	public $dbname = "bmbyforms";
	public $generalTable = "mbeat_form";
	public $fieldsTable = "mbeat_form_fields";
    private $arrPost;
    private $conn;
	
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
        /*case 'GetUserCompanyProjects':
        case 'GetCompanyUsers':
        case 'GetTask':
        case 'insert':
        case 'update':
        case 'delete':
        case 'UploadFile':
        case 'GetFilterCount':
        case 'SendOnlyToProjectUsers': 
        case 'AllowSendSms':*/
          return $data;
          break;
      }
    }
  }
  
    function before() {
        return new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
 
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

    function addNewRefLink($conn, $intid, $refsMedia, $refsAddOns, $refsLinks) {
        try {
            // set the PDO error mode to exception
            $sql = "INSERT INTO $this->refsTable (intid, refsMedia, refsAddOns, refsLinks) VALUES ('$intid', '$refsMedia', '$refsAddOns', '$refsLinks')";
            // use exec() because no results are returned
            $conn->exec($sql);
        }
        catch(PDOException $e) {
            echo $sql . "<br>" . $e->getMessage();
        }
    }   
    function showAlllinks($conn, $intid) {
        $stmt = $conn->prepare("SELECT * from $this->refsTable WHERE intid=$ intid");
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
		    $stmt = $this->conn->prepare("SELECT intid, fieldType, fieldLabel, fieldname, optionalVals, isMandatory, isHidden, validOptions, dateUpdate, userUpdate FROM $this->fieldsTable WHERE intid=$intid");
		    $stmt->execute();
			$allVals = "";
			/*Special Values*/
			//greetingsAddress
			$greetingsAddress = $this->conn->prepare("SELECT greetingsUrl FROM $this->generalTable WHERE intid=$intid");
			$greetingsAddress->execute();
			$gAddCol = $greetingsAddress->fetchColumn();
			//CSS Code
			$cssCode = $this->conn->prepare("SELECT cssInput FROM $this->generalTable WHERE intid=$intid");
			$cssCode->execute();
			$cssToInject = $cssCode->fetchColumn();
			//Button Text
			$bText = $this->conn->prepare("SELECT buttonText FROM $this->generalTable WHERE intid=$intid");
			$bText->execute();
			$bt = $bText->fetchColumn();
			//Condition Terms - Name
			$ctn = $this->conn->prepare("SELECT nameOfCondTerms FROM $this->generalTable WHERE intid=$intid");
			$ctn->execute();
			$ctName = $ctn->fetchColumn();
			//Condition Terms - Link
			$ctl = $this->conn->prepare("SELECT linkToCondTerms FROM $this->generalTable WHERE intid=$intid");
			$ctl->execute();
			$ctLink = $ctl->fetchColumn();
			//Delivery Text
			$dt = $this->conn->prepare("SELECT deliveryText FROM $this->generalTable WHERE intid=$intid");
			$dt->execute();
			$dText = $dt->fetchColumn();
			//RTL
			$rtl = $this->conn->prepare("SELECT RTL FROM $this->generalTable WHERE intid=$intid");
			$rtl->execute();
			$formDirection = $rtl->fetchColumn();
			if($formDirection == 'rtl')
				$formCSSCode = "body {direction: rtl !important;}";
			else $formCSSCode = "body {direction: ltr !important;}";
			//LOP
			$lop = $this->conn->prepare("SELECT LOP FROM $this->generalTable WHERE intid=$intid");
			$lop->execute();
			$orientation = $lop->fetchColumn();
			if($orientation == 'land')
				$formCSSCode .= "";//complete
			else $formCSSCode .= "";//complete
			//Button Font
			$bf = $this->conn->prepare("SELECT buttonFont FROM $this->generalTable WHERE intid=$intid");
			$bf->execute();
			$bFont = $bf->fetchColumn();
			$formCSSCode .= ".button {font-family: ".$bFont." !important;}";
			//Button Font Color
			$bfc = $this->conn->prepare("SELECT buttonFontColor FROM $this->generalTable WHERE intid=$intid");
			$bfc->execute();
			$bFontColor = $bfc->fetchColumn();
			if($bFontColor == 'trans')
				$formCSSCode .= ".button {color: transparent !important;}";
			else	
				$formCSSCode .= ".button {color: red !important;}";
			//Button BG Color
			$bbg = $this->conn->prepare("SELECT buttonColor FROM $this->generalTable WHERE intid=$intid");
			$bbg->execute();
			$buttonBgColor = $bbg->fetchColumn();
			if($buttonBgColor == 'trans')
				$formCSSCode .= ".button {background: transparent !important;}";
			else
				$formCSSCode .= ".button {background: green !important;}";
			//Form Width
			$fw = $this->conn->prepare("SELECT formWidth FROM $this->generalTable WHERE intid=$intid");
			$fw->execute();
			$fWidth = $fw->fetchColumn();
			$formCSSCode .= ".centerred {max-width: ".$fWidth."px !important;}";
			//Form Width
			$fbg = $this->conn->prepare("SELECT formBG FROM $this->generalTable WHERE intid=$intid");
			$fbg->execute();
			$fBackground = $fbg->fetchColumn();
			$formCSSCode .= ".centerred {background: ".$fBackground." !important;}";
			//Form Border Width
			$fbw = $this->conn->prepare("SELECT formBorderWidth FROM $this->generalTable WHERE intid=$intid");
			$fbw->execute();
			$fBorderWidth = $fbw->fetchColumn();
			$formCSSCode .= ".centerred {border-width: ".$fBorderWidth."px !important; border-style: solid;}";
			//Form Frame Color
			$ffc = $this->conn->prepare("SELECT formFrameColor FROM $this->generalTable WHERE intid=$intid");
			$ffc->execute();
			$fFrameColor = $ffc->fetchColumn();
			$formCSSCode .= ".centerred {border-color: ".$fFrameColor." !important;}";
			//Font Size
			$fs = $this->conn->prepare("SELECT fontSize FROM $this->generalTable WHERE intid=$intid");
			$fs->execute();
			$fontSize = $fs->fetchColumn();
			$formCSSCode .= ".centerred {font-size: ".$fontSize."px !important;}";
			//Font Color
			$fc = $this->conn->prepare("SELECT fontColor FROM $this->generalTable WHERE intid=$intid");
			$fc->execute();
			$fontColor = $fc->fetchColumn();
			$formCSSCode .= ".centerred {color: ".$fontSize." !important;}";
			//Field Width
			$fieldW = $this->conn->prepare("SELECT fieldWidth FROM $this->generalTable WHERE intid=$intid");
			$fieldW->execute();
			$fieldWidth = $fieldW->fetchColumn();
			$formCSSCode .= ".input {max-width: ".$fieldWidth."px !important;}";
			//Field BG
			$fieldBG = $this->conn->prepare("SELECT fieldBG FROM $this->generalTable WHERE intid=$intid");
			$fieldBG->execute();
			$fieldBackground = $fieldBG->fetchColumn();
			$formCSSCode .= ".input {background: ".$fieldBackground." !important;}";
			//Field Border Width
			$fieldBW = $this->conn->prepare("SELECT fieldBorderWidth FROM $this->generalTable WHERE intid=$intid");
			$fieldBW->execute();
			$fieldBWidth = $fieldBW->fetchColumn();
			$formCSSCode .= ".input {max-width: ".$fieldBWidth."px !important;}";
			//Field Frame Color
			$fieldFC = $this->conn->prepare("SELECT fieldFrameColor FROM $this->generalTable WHERE intid=$intid");
			$fieldFC->execute();
			$fieldFColor = $fieldFC->fetchColumn();
			$formCSSCode .= ".input {border-color: ".$fieldFColor." !important; border-style: solid;}";
			//Field Form Size
			$fieldFS = $this->conn->prepare("SELECT fieldFormSize FROM $this->generalTable WHERE intid=$intid");
			$fieldFS->execute();
			$fieldFSize = $fieldFS->fetchColumn();
			$formCSSCode .= ".input {max-width: ".$fieldFSize."px !important;}";
			//Field Form Color
			$fieldFCol = $this->conn->prepare("SELECT fieldFormColor FROM $this->generalTable WHERE intid=$intid");
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
	
    // Notice: when you run insertHTMLCodeToDB($conn, $intid, $formHTMLCode) where 
    // $formHTMLCode = generateFormHTMLScript($conn, $intid), you get the required result
    function insertHTMLCodeToDB($conn, $intid, $formHTMLCode) {
        try {
            // set the PDO error mode to exception
            $sql = "UPDATE $this->generalTable SET formHTMLCode=:formHTMLCode WHERE intid=:intid";
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
    // Notice: when you run insertJSCodeToDB($conn, $intid, $formHTMLCode) where
    // $formJSCode = generateFormJSScript($conn, $intid), you get the required result
    function insertJSCodeToDB($conn, $intid, $formJSCode) {
        try {
            // set the PDO error mode to exception
            $sql = "UPDATE $this->generalTable SET formJSCode=:formJSCode WHERE intid=:intid";
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
	/*Validation Functions*/
	private function fieldChecker($mandatory, $hidden) {
			$finalClasses = "";
			if ($mandatory == 1)
				$finalClasses = "mandatory ";
			if ($hidden == 1)
				$finalClasses .= "hidden";
			return $finalClasses;
		}

}


/*MAIN*/
$mainForms = new Forms();
$conn = $mainForms->before();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$mainForms->generateFormHTMLScript($conn, $intid);
//$mainForms->addNewForm($conn);
$mainForms->showAlllinks($conn, $intid);
$conn = $mainForms->after();

?>