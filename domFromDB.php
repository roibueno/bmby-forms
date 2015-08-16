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
body{
	direction: rtl;
}
.centerred{
	margin-right: auto;
    margin-left: auto;
    font-family: cursive;
    font-size: 18px;
    font-weight: bold;
}
/* Elegant Aero */
.elegant-aero {
    margin-left:auto;
    margin-right:auto;

    max-width: 500px;
    background: #D2E9FF;
    padding: 20px 20px 20px 20px;
    font: 12px Arial, Helvetica, sans-serif;
    color: #666;
}
.elegant-aero h1 {
    font: 24px "Trebuchet MS", Arial, Helvetica, sans-serif;
    padding: 10px 10px 10px 20px;
    display: block;
    background: #C0E1FF;
    border-bottom: 1px solid #B8DDFF;
    margin: -20px -20px 15px;
}
.elegant-aero h1>span {
    display: block;
    font-size: 11px;
}

.elegant-aero label>span {
    float: left;
    margin-top: 10px;
    color: #5E5E5E;
}
.elegant-aero label {
    display: block;
    margin: 0px 0px 5px;
}
.elegant-aero label>span {
    float: left;
    width: 20%;
    text-align: right;
    padding-right: 15px;
    margin-top: 10px;
    font-weight: bold;
}
.elegant-aero input[type="text"], .elegant-aero input[type="email"], .elegant-aero textarea, .elegant-aero select {
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
.elegant-aero textarea{
    height:100px;
    padding: 5px 0px 0px 5px;
    width: 70%;
}
.elegant-aero select {
    background: #fbfbfb url('down-arrow.png') no-repeat right;
    background: #fbfbfb url('down-arrow.png') no-repeat right;
   appearance:none;
    -webkit-appearance:none; 
   -moz-appearance: none;
    text-indent: 0.01px;
    text-overflow: '';
    width: 70%;
}
.elegant-aero .button{
    padding: 10px 30px 10px 30px;
    background: #66C1E4;
    border: none;
    color: #FFF;
    box-shadow: 1px 1px 1px #4C6E91;
    -webkit-box-shadow: 1px 1px 1px #4C6E91;
    -moz-box-shadow: 1px 1px 1px #4C6E91;
    text-shadow: 1px 1px 1px #5079A3;
    
}
.elegant-aero .button:hover{
    background: #3EB1DD;
}
</style>
<?php $intid=isset($_GET["intid"])?$_GET["intid"]:"" ?>
<?php
class TableRows extends RecursiveIteratorIterator { 
    function __construct($it) { 
        parent::__construct($it, self::LEAVES_ONLY); 
    }

    function current() {
        return parent::current();
    }

    function beginChildren() { 
        //echo "<tr>"; 
    } 

    function endChildren() { 
        //echo "</tr>" . "\n";
    } 
} 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bmby";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $stmt = $conn->prepare("SELECT bmby_form.intid, fieldType, fieldLabel, fieldname, optionalVals, isMandatory, isHidden FROM bmby_form WHERE intid=$intid"); 
    $stmt->execute();
	$allVals = "";
	$greetingsAddress = $conn->prepare("SELECT greetingsUrl FROM bmby_generalattrs WHERE intid=$intid");
	$greetingsAddress->execute();
	$gAddCol = $greetingsAddress->fetchColumn();
	$cssCode = $conn->prepare("SELECT cssInput FROM bmby_generalattrs WHERE intid=$intid");
	$cssCode->execute();
	$cssToInject = $cssCode->fetchColumn();
	echo "<style>".$cssToInject."</style>";
    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    echo '<form action="'.$gAddCol.'" method="post" class="STYLE-NAME"><div class="centerred elegant-aero"><h1>טופס לדוגמא<span>אנא הזן את פרטיך:</span></h1>';
    foreach(new RecursiveArrayIterator($stmt->fetchAll()) as $k=>$v) {
    		$optValsStr = $v["optionalVals"];
    		$optArray = explode('|', $optValsStr);
    		$currClasses = fieldChecker($v["isMandatory"], $v["isHidden"]);
    		echo '<label class="fieldLabel '.$currClasses.'">'.$v["fieldLabel"].' ';
   			switch ($v["fieldType"]) {
   				case "textarea":
   					echo '<div class="textarea"><textarea></textarea></div><br><br>';
   					break;
   				case "input":
   					$currClasses = fieldChecker($v["isMandatory"], $v["isHidden"]);
   					echo '<input class="input"><br><br>';
   					break;
   				case "radio":
   					$currClasses = fieldChecker($v["isMandatory"], $v["isHidden"]);
   					foreach($optArray as $value)
   					{
   						echo '<input type="radio" name="radioOptions" value="'.$value.'">'.$value;
   					}
   					echo '<br><br>';
   					break;
   				case "checkbox":
   					$currClasses = fieldChecker($v["isMandatory"], $v["isHidden"]);
					foreach($optArray as $value)
					{
					    echo '<input type="checkbox" name="checkboxOptions" value="'.$value.'">'.$value;
					}
					echo '<br><br>';
   					break;
   				case "dropdown":
   					$currClasses = fieldChecker($v["isMandatory"], $v["isHidden"]);
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
echo '<br><br><label><span>&nbsp;</span><input type="submit" class="button" value="שלח" /></label></div></form>';
$conn = null;

function fieldChecker($mandatory, $hidden) {
	$finalClasses = "";
	if ($mandatory == 1)
		$finalClasses = "mandatory ";
	if ($hidden == 1)
		$finalClasses .= "hidden";
	return $finalClasses;
}

?>