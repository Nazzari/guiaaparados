<?

define("UPLOAD_DIR", "../../fotos/");

if (!empty($_FILES["file"])) {
    $file = $_FILES["file"];
    
    if ($file["error"] !== UPLOAD_ERR_OK) {
        echo "<p>An error occurred.</p>";
        exit;
    }

    $name = $file["name"];
    
    $arrfile = pos($name);
	
	$alfabeto = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
	$extensao = explode(".",$file["name"]);
	$extensao = strtolower($extensao[count($extensao)-1]);
	$name = $_POST['nome_campo'].date("_Y_m_d__H_i_s_").".".$extensao;


    // don't overwrite an existing file
    $i = 0;
    $parts = pathinfo($name);
    while (file_exists(UPLOAD_DIR . $name)) {
        $i++;
        $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
    }
    // preserve file from temporary directory
    $success = move_uploaded_file($file["tmp_name"],
        UPLOAD_DIR . $name);
    if (!$success) { 
        echo "<p>Unable to save file.</p>";
        exit;
    }

    // set proper permissions on the new file
    chmod(UPLOAD_DIR . $name, 0644);
    echo '<img src="../../fotos/'.$name.'" height="100" width="100" />';
    echo '<input id="'.$_POST['nome_campo'].'" name="'.$_POST['nome_campo'].'" type="hidden" value='.$name.' />';
}