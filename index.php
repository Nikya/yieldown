
<?php
	ini_set('display_errors',1);
	error_reporting(E_ALL);

	require_once("yieldownEngine/Yieldown.php");

	$target='home';
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>L'histoire du jeans</title>
	<meta name="keywords" content="jeans, markdown, yieldown" />
	<meta name="description" content="L'histoire du jeans : pantalon à coutures, coupé dans une toile denim." />
	<link href="style/main.css" rel="stylesheet">
</head>

<body>
	<header class="header">
		<h1>L'histoire du jeans</h1>

		<h3>Site factice de démonstration pour le générateur de site Yieldown</h3>
	</header><!-- .header-->

	<nav>
		<a href=".">Accueil</a>
		<a href="/">Blog</a>
		<a href="/">Historique</a>
		<a href="/">Types de tons</a>
		<a href="/">Types de coupes</a>
	</nav>

	<section> <!-- CONTENT -->
		<?php include("content/$target.php"); ?>
	</section>

	<footer>
		<strong>Site factice de démonstration pour le générateur de site <em>Yieldown</em></strong>
		<br/><br/>
		<p>Auteur : <a href="https://github.com/Nikya">Nikya</a>
		<p>Source : <a href="https://github.com/Nikya/yieldown">Yieldown</a>

		<hr/>

		<p>Source du contenue : <a href="https://fr.wikipedia.org/wiki/Jeans">Wikipedia</a>
		<p>Source du blog : <a href="http://fr.wikihow.com/Special:GoogSearch?q=jeans">Wikihow</a>
		<p>Source photo : <a href="https://pixabay.com/fr/jeans-de-poche-tissu-v%C3%AAtements-1751/">Pixabay</a>
		<p>Markdown Engine : <a href="http://parsedown.org/">Parsedown</a>
	</footer>

</body>
</html>
