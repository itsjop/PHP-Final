<?php

// Create and include a configuration file with the database connection
include('config.php');

// Include functions
include('functions.php');

// Get the book isbn from the url
$gameID = get('gameID');

// Get a list of books from the database with the isbn passed in the URL
$sql = file_get_contents('sql/getGame.sql');
$params = array(
	'gameID' => $gameID
);
$statement = $database->prepare($sql);
$statement->execute($params);
$games = $statement->fetchAll(PDO::FETCH_ASSOC);

// Set $book equal to the first book in $books
$game = $games[0];

// Get categories of book from the database
$sql = file_get_contents('sql/getGameGenre.sql');
$params = array(
	'gameID' => $gameID
);
$statement = $database->prepare($sql);
$statement->execute($params);
$genres = $statement->fetchAll(PDO::FETCH_ASSOC);

/* In the HTML:
	- Print the book title, author, price
	- List the categories associated with this book
*/
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	
  	<title>Game</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="author" content="SitePoint">

	<link rel="stylesheet" href="css/style.css">

	<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  	<![endif]-->
</head>
<body>
	<div class="page">
		<h1><?php echo $game['title'] ?></h1>
		<p>
			Developed By: <?php echo $game['developer']; ?><br />
			Price: $<?php echo $game['price']; ?><br />
		</p>
		
		<ul>
			<?php foreach($genres as $genre) : ?>
				<li><?php echo $genre['name'] ?></li>
			<?php endforeach; ?>
		</ul>
		
	</div>
</body>
</html>