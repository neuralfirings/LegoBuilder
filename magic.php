<?php

$triangles = $_POST["triangles"];
$name = $_POST["username"];
$pw = $_POST['password'];
if ($_POST['modeltitle'] != "")
	$title = $_POST['modeltitle'];
else
	$title = "Creator Model No. " . rand(1, 9999);

$username = preg_replace("[^A-Za-z0-9]", "", $name); 
$fileext = ".stl";
$filename = "temp-".rand(1,99999);
$filepath = "exports/";

if (sizeof($triangles)>=0)
{
	#
	# Mary's Tool: Takes Triangles and generates an STL 
	#

	//save .png file
	$dataurl = str_replace(" ", "+", $_POST["pic"]);
	$data = substr($dataurl, strpos($dataurl, ","));
		
		
	//generate .stl file
	$fileHandle = fopen($filepath . $filename . $fileext, 'w') or die("can't open file");

	$start= "solid OBJECT\n";
	fwrite($fileHandle, $start);

	for ($triangle=0; $triangle<sizeof($triangles); $triangle++) {
		
		$T = explode(",", $triangles[$triangle]);
			for ($i=0; $i<sizeof($T); $i++) {
				$T[$i]=$T[$i];
			}
			
		$line1= "  facet normal ".$T[9]." ".$T[10]." ".$T[11]."\n";
		fwrite($fileHandle, $line1);
		$line2= "    outer loop\n";
		fwrite($fileHandle, $line2);
		$line3= "      vertex ".$T[0]." ".$T[1]." ".$T[2]."\n";
		fwrite($fileHandle, $line3);
		$line4= "      vertex ".$T[3]." ".$T[4]." ".$T[5]."\n";
		fwrite($fileHandle, $line4);
		$line5= "      vertex ".$T[6]." ".$T[7]." ".$T[8]."\n";
		fwrite($fileHandle, $line5);
		$line6= "    endloop\n";
		fwrite($fileHandle, $line6);
		$line7= "  endfacet\n";
		fwrite($fileHandle, $line7);
	   
	}   
	$end= "endsolid OBJECT";
	fwrite($fileHandle, $end);
	fclose($fileHandle);

	#
	# Shapeways SOAP API reference implementation v1.0
	#
	# API documentation is available at
	# http://www.shapeways.com/tutorials/shapeways__upload_interface
	error_reporting(E_ALL|E_STRICT);
	$demo = new SoapAPI();

	$demo->wsdlconnect("http://api.shapeways.com/v1/wsdl.php");
	$demo->login($name, $pw);
	$demo->uploadFile($filepath, $filename, $fileext, "STL", $title, false);
	exit;
}
else
{
	echo "There are no triangles to export. Draw something first!"; 
}

////////////////////////// CLASSES AND AUXILIARY FUNCTIONS BELOW ////////////////////
class SoapAPI {
    public $client;
    public $session;

    public function wsdlconnect($apiAddr) {
        try {
            $this->client = new SoapClient($apiAddr, array('trace'=>1) );
        } catch(Exception $e) {
            var_dump('No connection could be made');
            var_dump($e);
            exit;
        }
    }

    public function login($user, $password) {
        try {
            $this->session = $this->client->login($user, $password);
            $hasSession = strlen($this->session) >= 0 ? true : false;
        } catch(Exception $e) {
            var_dump('No login could be made');
            var_dump($e);
            exit;
        }
    }

    public function uploadFile($filepath, $filename, $fileext, $fileType, $title, $color) {
        try {
            $fileHandle = file_get_contents($filepath . $filename . $fileext, FILE_USE_INCLUDE_PATH);
            $model = array ('title'     => $title,
                            'file'      => base64_encode($fileHandle),
                            'filename'  => $filename . $fileext,
                            'modeltype' => $fileType,
                            'has_color' => $color,
							'scale'		=> '0.001',
                            'view_state'=> 1);
            $result = $this->client->submitModel($this->session, $model, null, "generated-creator");
			echo $result;
        } catch (Exception $e) {
            var_dump('Exception');
            var_dump($e->getMessage());
        }
    }
}
?>