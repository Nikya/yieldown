<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title><?php echo $title ?></title>
	<meta name="keywords" content="<?php echo $keywords ?>" />
	<meta name="description" content="<?php echo $description ?>" />
	<link href="aspect/main.css" rel="stylesheet">
	<link href="aspect/github-markdown.css" rel="stylesheet">
</head>

<body>
	<header class="header">
		<h1>L'histoire du jeans</h1>

		<h3>Site factice de démonstration pour le générateur de site Yieldown</h3>
	</header><!-- .header-->

	<nav>
		<a href=".">Accueil</a>
		<a href="blog.html">Blog</a>
		<a href="history.html">Historique</a>
		<a href="tone.html">Types de tons</a>
		<a href="cut.html">Types de coupes</a>
	</nav>

	<section> <!-- CONTENT -->
		<?php include("view/$subview"); ?>
	</section>

	<footer>
		<?php echo $tFooter ?>
	</footer>

</body>
</html>
