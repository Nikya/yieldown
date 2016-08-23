<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Administration</title>
	<meta name="keywords" content="yieldown, admin" />
	<meta name="description" content="yieldown Admin page" />
	<link href="main.css" rel="stylesheet">
</head>

<body>
	<a href='..'>&lArr; Retour</a>


	<h1>Administration
		<p>Yieldown v<?php echo $yieldownVersion ?></p>
	</h1>


	<?php if (!empty($msg)) echo "<p class=\"msg $msgClass\">$msg</p>"; ?>

	<h2>Actions</h2>
	<form action="." method="post" enctype="multipart/form-data" >

		<dl id="actions">
			<dt>Aide</dt>
			<dd></dd>
			<dd>Consulter l'aide global de <em class="kw">Yieldown</em> : <a class="btn" href=".?action=help">Aide</a></dd>
			<dd>Version en ligne <a href="https://github.com/Nikya/yieldown">Github de Yieldown</a></dd>

			<dt>Voir</dt>
			<dd>Visualiser les fichiers dans leurs format <strong>brute</strong>, sans mise en forme. </dd>
			<dd>Utiliser les boutons <em>Voir</em> dans la <em class="kw">table des données</em> ci-dessous.</dd>

			<dt>Suppr.</dt>
			<dd>Supprimer définitivement des fichiers.</dd>
			<dd>Utiliser les boutons <em>Suppr.</em> dans la <em class="kw">table des données</em> ci-dessous.</dd>

			<dt>Ajouter</dt>
			<dd>Ajouter ou remplacer un fichier existant. </dd>
			<dd>Choisir un fichier puis utiliser les boutons <em>Ajouter à</em> dans la <em class="kw">table des données</em> ci-dessous. </dd>
			<dd>Attention au nom, taille et extension du fichier.</dd>
			<dd><input type="hidden" name="MAX_FILE_SIZE" value="41943040" /><input type="file" name="nFile" accept="image/x-png, image/gif, image/jpeg, .json, .md"/>.</dd>

			<dt>Regénérer</dt>
			<dd>Appliquer au site les modifications de la <em class="kw">table des données</em>.  </dd>
			<dd>Pour cela, supprime toute les pages du site, pour forcer leurs regénération avec les changements affectuées. </dd>
			<dd><a class="btn" href=".?action=regen">Regénérer</a> </dd>
			<dd>Effectue en plus une sauvegarde complète de la <em class="kw">table des données</em>. </dd>
			<dd>Il existe actuellement <em class="kw">x<?php echo $backupCount ?></em> sauvegardes. Consulter le dossier <a href="../databackup">databackup</a>.</dd>
		</dl>

		<?php if (isset($helpMsg)) { echo <<<HELPBOX
		<h2>Aide</h2>
		<div id="helpBox">
			$helpMsg
		</div>
HELPBOX;
} ?>

		<h2>Table des données</h2>
		<table id='dataListing'>
			<tbody>
				<?php foreach ($dataListing as $oData) {
					echo <<<FOLDER_H
						<tr class="subhead">
							<td class="title" colspan="2">$oData->title</td>
							<td class="desc">$oData->desc</td>
						</tr>
						<tr class="folder">
							<td class="action"><input type="submit" name="actionAdd[$oData->path]" value="Ajouter à" class="btn"/></td>
							<td class="path" colspan="2">$oData->path</td>
						</tr>
FOLDER_H;
					foreach ($oData->filesList as $fileName) {
						$fileId = urlencode($oData->path.DIRECTORY_SEPARATOR.$fileName);
						echo <<<FILE
						<tr class="file">
							<td class="action">
								<a class="btn btnDelete" href=".?action=delete&fileId=$fileId">Suppr.</div>
								<a class="btn" href=".?action=see&fileId=$fileId">Voir</a>
							</td>
							<td class="fileName" colspan="2">$fileName</td>
						</tr>
FILE;
					}
				}
				?>
			</tbody>
		</table>
	</form>

	<footer>
		Powered by <a href="https://github.com/Nikya/yieldown">Yieldown</a> v<?php echo $yieldownVersion ?> | Admin chemin absolu : <?php echo realpath('.') ?>
	</footer>
</body>
</html>
