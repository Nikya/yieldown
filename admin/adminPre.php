<?php

////////////////////////////////////////////////////////////////////////////////
	// To read a folder content
	function getDirContents($dir){
		$files = scandir($dir);
		$results = array();

		foreach($files as $key => $value) {
			if ($value != '.' and $value != '..' and $value != '.htaccess') {
				$rPath = $dir.$value;
				if(!is_dir($rPath)) {
					$results[$rPath] = $value;
				} else {
					$subDir = getDirContents($rPath.DIRECTORY_SEPARATOR);
					$results[$rPath] = $subDir;
				}
			}
		}

		return $results;
	}

	// To see raw file
	function seeData($fileId){
		global $msg;

		if (!file_exists($fileId)) {
			$msg = "Le fichier '$fileId' est introuvable.";
			return;
		}

		header("Content-Type: ".mime_content_type($fileId));
		header('Content-disposition: inline; filename="'.basename($fileId).'"');

		readfile($fileId);
		exit;
	}

	// To see raw file
	function deleteData($fileId){
		global $msg;

		if (!file_exists($fileId)) {
			$msg = "Le fichier '$fileId' est introuvable.";
			return;
		}

		if (stripos($fileId, '_template.md')!==false)
			$msg = "Le fichier TEMPLATE ne peut pas être supprimé";
		else if (unlink ($fileId))
			$msg = "Fichier supprimé $fileId";
		else
			$msg = "Impossible de supprimer le fichier $fileId";
	}

	// To get the readme file as help text
	function getHelp() {
		global $msg;

		require_once('../yieldownEngine/vendors/erusev/parsedown/Parsedown.php');
		$file = '../README.md';

		if (!file_exists($file)) {
			$msg = "Le fichier '$file' est introuvable.";
			return $msg;
		}

		$hTxt = file_get_contents($file);
		$hTxt = Parsedown::instance()->text($hTxt);

		return $hTxt;
	}

	// To get the readme file as help text
	function regen() {
		global $msg;
		$fileCnt = 0;
		$fileDel = 0;

		$files = glob('../cache/*');
		foreach($files as $file) {
			$fileCnt++;
			if(unlink($file)) {
				$fileDel++;
			} else {
				$msg .= ',$file';
			}
		}

		if ($fileCnt==0) {
			$msg = 'Aucun fichier à supprimer';
		} else {
			$msg = "Cache vidé $fileDel/$fileCnt fichiers supprimés. ". (!empty($msg) ? "Fichiers en erreur : $msg " : '');
		}
	}

	// To upload a new file
	function uploadFile($destPath) {
		global $msg;
		$msg = null;
		$fichier = basename($_FILES['nFile']['name']);
		$taille_maxi = 5000000;
		$taille = $_FILES['nFile']['size'];
		$extensions = array('.png', '.gif', '.jpg', '.jpeg', '.md', '.json', '.json');
		$extension = strrchr($_FILES['nFile']['name'], '.');
		//Début des vérifications de sécurité...
		if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
			$msg = "Vous devez uploader un fichier de type .png, .gif, .jpg, .md, .json";
		if($taille>$taille_maxi)
			$msg = "Le fichier est trop gros : $taille>$taille_maxi o";
		if(!isset($msg)) //S'il n'y a pas d'erreur, on upload
		{
			//On formate le nom du fichier ici...
			$fichier = utf8_encode($fichier);
			$fichier = strtr($fichier, utf8_encode('ÁÀÂÄÃÅÇÉÈÊËÍÏÎÌÑÓÒÔÖÕÚÙÛÜÝ'), utf8_encode('AAAAAACEEEEEIIIINOOOOOUUUUY'));
			$fichier = strtr($fichier, utf8_encode('áàâäãåçéèêëíìîïñóòôöõúùûüýÿ'), utf8_encode('aaaaaaceeeeiiiinooooouuuuyy'));
			$fichier = preg_replace('/([^-.a-z0-9]+)/i', '_', $fichier);

			//var_dump($_FILES['nFile']['tmp_name'].'>>>'. $destPath .DIRECTORY_SEPARATOR. $fichier);
			if(move_uploaded_file($_FILES['nFile']['tmp_name'], $destPath .DIRECTORY_SEPARATOR. $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
				$msg = "Chargement du fichier '$fichier' effectué avec succès.";
			else //Sinon (la fonction renvoie FALSE).
				$msg = "Echec du chargement du fichier '$fichier' - " . $_FILES['nFile']['error'];
		}
	}

////////////////////////////////////////////////////////////////////////////////
	// Process
	$dataListing = getDirContents('../data/');
	//echo '<pre>'.print_r($dataListing, true).'</pre>';

////////////////////////////////////////////////////////////////////////////////
	// Read form

	// Add action
	if(isset($_POST['actionAdd']) and !empty($_POST['actionAdd'])) {
		if(isset($_FILES['nFile']['name']) and !empty($_FILES['nFile']['name'])) {
			foreach ($_POST['actionAdd'] as $destPath => $value) {
				uploadFile($destPath);
			}
		} else
			$msg = "Aucun fichier sélectionée.";

	// Other Action
	} else if(isset($_GET['action']) and !empty($_GET['action']) ) {
		$action = $_GET['action'];
		if (isset($_GET['fileId']) and !empty($_GET['fileId']))
			$fileId = $_GET['fileId'];
		else
			$fileId = null;

		switch ($action) {
			case 'delete':
				deleteData($fileId);
				break;

			case 'see':
				seeData($fileId);
				break;

			case 'help':
				$helpMsg = getHelp();
				break;

			case 'regen':
				regen();
				break;

			default:
				$msg = "Action inconnue !!";
				break;
		}
	}

////////////////////////////////////////////////////////////////////////////////
	// Load data table
	$dataListing = getDirContents('../data/');
