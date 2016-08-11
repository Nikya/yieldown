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
	<h1>Administration</h1>

	<?php if (!empty($msg)) echo "<p class=\"msg $msgClass\">$msg</p>"; ?>

	<h2>Actions</h2>
	<form action="." method="post" enctype="multipart/form-data" >

		<ul id="actions">
			<li>
				<a class="btn" href=".?action=help">Aide</a>
				 : Consulter l'aide global de <em class="tdd">Yieldown</em>. Version en ligne <a href="https://github.com/Nikya/yieldown">Github de Yieldown</a>
			 </li>
			<li>
				<Strong>Voir</strong>
				 : Visualiser les fichiers dans leurs format <strong>brute</strong>, sans mise en forme. Utiliser les boutons <em>Voir</em> dans la <em class="tdd">table des données</em> ci-dessous.
			 </li>
			<li>
				<Strong>Suppr.</strong>
				 : Supprimer définitivement des fichiers. Utiliser les boutons <em>Suppr.</em> dans la <em class="tdd">table des données</em> ci-dessous.
			 </li>
			<li>
				<input type="hidden" name="MAX_FILE_SIZE" value="41943040" /><input type="file" name="nFile" accept="image/x-png, image/gif, image/jpeg, .json, .md"/>.
				Ajouter ou remplacer un fichier existant. Choisir un fichier puis utiliser les boutons <em>Ajouter à</em> dans la <em class="tdd">table des données</em> ci-dessous. (Attention au nom, taille et extension du fichier).
			 </li>
			<li>
				<a class="btn" href=".?action=regen">Regénérer</a>
				 : Appliquer au site les modifications de la <em class="tdd">table des données</em>. Supprime toute les pages du site, pour forcer leurs regénération avec les changements affectuées.
			 </li>
		</ul>

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
				<?php foreach ($dataListing as $path => $data) {
					echo <<<FOLDER_H
						<tr class="folder">
							<td class="action"><input type="submit" name="actionAdd[$path]" value="Ajouter à" class="btn"/></td>
							<td class="path">$path</td>
						</tr>
FOLDER_H;
					foreach ($data as $filePath => $fileName) {
						$fileId = urlencode($path.DIRECTORY_SEPARATOR.$fileName);
						echo <<<FILE
						<tr class="file">
							<td class="action">
								<a class="btn btnDelete" href=".?action=delete&fileId=$fileId">Suppr.</div>
								<a class="btn" href=".?action=see&fileId=$fileId">Voir</a>
							</td>
							<td class="fileName">$fileName</td>
						</tr>
FILE;
					}
				}
				?>
			</tbody>
		</table>
	</form>

	<footer>
		Powered by <a href="https://github.com/Nikya/yieldown">Yieldown</a> | Admin chemin absolu : <?php echo realpath('.') ?>
	</footer>
</body>
</html>
