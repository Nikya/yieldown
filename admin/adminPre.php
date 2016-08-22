<?php

////////////////////////////////////////////////////////////////////////////////
// Admin functions
////////////////////////////////////////////////////////////////////////////////

	// To read a folder content
	function getDirContents($dir){
		$toHideFiles = array (
			'.',
			'..',
			'.htaccess'
		);

		$files = scandir($dir);

		return array_diff($files, $toHideFiles);
	}

	// To see raw file
	function seeData($fileId){
		global $msg;

		if (!file_exists($fileId)) {
			$msg = MSG_KO."Le fichier '$fileId' est introuvable.";
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
			$msg = MSG_KO."Le fichier '$fileId' est introuvable.";
			return;
		}

		if (stripos($fileId, '_template.md')!==false)
			$msg = MSG_KO."Le fichier TEMPLATE ne peut pas être supprimé";
		else if (unlink ($fileId))
			$msg = MSG_OK."Fichier supprimé $fileId";
		else
			$msg = MSG_KO."Impossible de supprimer le fichier $fileId";
	}

	// To get the readme file as help text
	function getHelp() {
		global $msg;

		require_once('../yieldownEngine/vendors/erusev/parsedown/Parsedown.php');
		$file = '../README.md';

		if (!file_exists($file)) {
			$msg = MSG_KO."Le fichier '$file' est introuvable.";
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
			$msg = MSG_OK.'Cache déjà vierge.';
		} else {
			$msg = MSG_OK."Cache vidé $fileDel/$fileCnt fichiers supprimés. ". (!empty($msg) ? "Fichiers en erreur : $msg." : '');
		}
	}

	// Save all data
	function backup() {
		global $msg;

		$src = '../data';
		$dst = '../databackup/databackup_' . date('Ymd_His');

		recurse_copy($src,$dst);

		if (is_dir($dst))
			$msg .= ' '.MSG_OK.' Backup réalisé.';
		else
			$msg .= ' '.MSG_KO." Echec de la réalisation du backup de '$src' vers '$dst'";
	}

	// Copy a folder and his content
	function recurse_copy($src,$dst) {
		$dir = opendir($src);

		mkdir($dst);

		while(false !== ( $file = readdir($dir)) ) {
			if (( $file != '.' ) && ( $file != '..' )) {
				if ( is_dir($src . '/' . $file) ) {
					recurse_copy($src . '/' . $file,$dst . '/' . $file);
				}
				else {
					copy($src . '/' . $file,$dst . '/' . $file);
				}
			}
		}
		closedir($dir);
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
			$msg = MSG_KO."Vous devez uploader un fichier de type .png, .gif, .jpg, .md, .json";
		if($taille>$taille_maxi)
			$msg = MSG_KO."Le fichier est trop gros : $taille>$taille_maxi o";
		if(!isset($msg)) //S'il n'y a pas d'erreur, on upload
		{
			//On formate le nom du fichier ici...
			$fichier = utf8_encode($fichier);
			$fichier = strtr($fichier, utf8_encode('ÁÀÂÄÃÅÇÉÈÊËÍÏÎÌÑÓÒÔÖÕÚÙÛÜÝ'), utf8_encode('AAAAAACEEEEEIIIINOOOOOUUUUY'));
			$fichier = strtr($fichier, utf8_encode('áàâäãåçéèêëíìîïñóòôöõúùûüýÿ'), utf8_encode('aaaaaaceeeeiiiinooooouuuuyy'));
			$fichier = preg_replace('/([^-.a-z0-9]+)/i', '_', $fichier);

			//var_dump($_FILES['nFile']['tmp_name'].'>>>'. $destPath .DIRECTORY_SEPARATOR. $fichier);
			if(move_uploaded_file($_FILES['nFile']['tmp_name'], $destPath .DIRECTORY_SEPARATOR. $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
				$msg = MSG_OK."Chargement du fichier '$fichier' effectué avec succès.";
			else //Sinon (la fonction renvoie FALSE).
				$msg = MSG_KO."Echec du chargement du fichier '$fichier' - " . $_FILES['nFile']['error'];
		}
	}

	// Get Yieldown version
	require_once('../yieldownEngine/Yieldown.php');
	$yieldownVersion = Yieldown::VERSION;

////////////////////////////////////////////////////////////////////////////////
	// Read form

	// Add action
	if(isset($_POST['actionAdd']) and !empty($_POST['actionAdd'])) {
		if(isset($_FILES['nFile']['name']) and !empty($_FILES['nFile']['name'])) {
			foreach ($_POST['actionAdd'] as $destPath => $value) {
				uploadFile($destPath);
			}
		} else
			$msg = MSG_KO."Aucun fichier sélectionée.";

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
				backup();
				break;

			default:
				$msg = MSG_KO."Action inconnue !!";
				break;
		}
	}

////////////////////////////////////////////////////////////////////////////////
	// Load data table

	// text
	$oText = new stdClass();
	$oText->title = 'Les textes';
	$oText->desc = 'Contient des textes formatables. Un texte par fichier au format Markdown (.md)';
	$oText->path = '../data/text';
	$oText->filesList = getDirContents($oText->path);

	// Collection
	$oCollection = new stdClass();
	$oCollection->title = 'Les collections';
	$oCollection->desc = 'Contient des collections de données. Une collection par fichier au format Json (.json)';
	$oCollection->path = '../data/collection';
	$oCollection->filesList = getDirContents($oCollection->path);

	// Blog
	$oBlog = new stdClass();
	$oBlog->title = 'Le blog';
	$oBlog->desc = 'Contient le blog. Utiliser le fichier TEMPLATE comme modèle pour rédiger un nouvel article. Un article de blog par fichier au format Markdown (.md)';
	$oBlog->path = '../data/blog';
	$oBlog->filesList = getDirContents($oBlog->path);

	// Aside
	$oAside = new stdClass();
	$oAside->title = 'A côté';
	$oAside->desc = 'Contient des éléments secondaires utilisables dans le blog ou le reste du site. (images, téléchargeables, ...)';
	$oAside->path = '../data/aside';
	$oAside->filesList = getDirContents($oAside->path);

	$dataListing = array(
		$oText,
		$oCollection,
		$oBlog,
		$oAside
	);
