<?php

// Create and include a configuration file with the database connection
include('config.php');

// Include functions for application
include('functions.php');

// Get type of form either add or edit from the URL (ex. form.php?action=add) using the newly written get function
$action = $_GET['action'];

// Get the book gameID from the URL if it exists using the newly written get function
$gameID = get('gameID');

// Initially set $book to null;
$game = null;

// Initially set $book_categories to an empty array;
$game_genres = array();

// If game gameID is not empty, get book record into $book variable from the database
//     Set $book equal to the first book in $books
// 	   Set $book_categories equal to a list of categories associated to a book from the database
if(!empty($gameID)) {
	$sql = file_get_contents('sql/getGame.sql');
	$params = array(
		'gameID' => $gameID
	);
	$statement = $database->prepare($sql);
	$statement->execute($params);
	$games = $statement->fetchAll(PDO::FETCH_ASSOC);
	
	$game = $games[0];
	
	// Get book categories
	$sql = file_get_contents('sql/getGameGenre.sql');
	$params = array(
		'gameID' => $gameID
	);
	$statement = $database->prepare($sql);
	$statement->execute($params);
	$game_genres_associative = $statement->fetchAll(PDO::FETCH_ASSOC);
	
	foreach($game_genres_associative as $genre) {
		$game_genres[] = $genre['categoryid'];
	}
}

// Get an associative array of categories
$sql = file_get_contents('sql/getGenres.sql');
$statement = $database->prepare($sql);
$statement->execute();
$genres = $statement->fetchAll(PDO::FETCH_ASSOC);

// If form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$gameID = $_POST['gameID'];
	$title = $_POST['game-title'];
	$game_genres = $_POST['game-genre'];
	$developer = $_POST['game-developer'];
	$price = $_POST['game-price'];
	
	if($action == 'add') {
		// Insert book
		$sql = file_get_contents('sql/insertGames.sql');
		$params = array(
			'gameID' => $gameID,
			'title' => $title,
			'developer' => $developer,
			'price' => $price
		);
	
		$statement = $database->prepare($sql);
		$statement->execute($params);
		
		// Set categories for book
		$sql = file_get_contents('sql/insertGameGenre.sql');
		$statement = $database->prepare($sql);
		
		foreach($game_genres as $genre) {
			$params = array(
				'gameID' => $gameID,
				'genreID' => $genre
			);
			$statement->execute($params);
		}
	}
	
	elseif ($action == 'edit') {
		$sql = file_get_contents('sql/updateGame.sql');
        $params = array( 
            'gameID' => $gameID,
            'title' => $title,
            'developer' => $developer,
            'price' => $price
        );
        
        $statement = $database->prepare($sql);
        $statement->execute($params);
        
        //remove current category info 
        $sql = file_get_contents('sql/removeGenres.sql');
        $params = array(
            'gameID' => $gameID
        );
        
        $statement = $database->prepare($sql);
        $statement->execute($params);
        
        //set categories for book
        $sql = file_get_contents('sql/insertGameGenre.sql');
        $statement = $database->prepare($sql);
        
        foreach($game_genres as $genre) {
            $params = array(
                'gameID' => $gameID,
                'genreID' => $genre
            );
            $statement->execute($params);
        };	
	}
	
	// Redirect to book listing page
	header('location: index.php');
}

// In the HTML, if an edit form:
	// Populate textboxes with current data of book selected 
	// Print the checkbox with the book's current categories already checked (selected)
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	
  	<title>Manage Book</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="developer" content="SitePoint">

	<link rel="stylesheet" href="css/style.css">

	<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  	<![endif]-->
</head>
<body>
	<div class="page">
		<h1>Manage Game</h1>
		<form action="" method="POST">
			<div class="form-element">
				<label>Game:</label>
				<?php if($action == 'add') : ?>
					<input type="text" name="gameID" class="textbox" value="<?php echo $game['gameID'] ?>" />
				<?php else : ?>
					<input readonly type="text" name="gameID" class="textbox" value="<?php echo $game['gameID'] ?>" />
				<?php endif; ?>
			</div>
			<div class="form-element">
				<label>Title:</label>
				<input type="text" name="game-title" class="textbox" value="<?php echo $game['title'] ?>" />
			</div>
			<div class="form-element">
				<label>Genre:</label>
				<?php foreach($genres as $genre) : ?>
					<?php if(in_array($genre['genreID'], $game_genres)) : ?>
						<input checked class="radio" type="checkbox" name="game-genre[]" value="<?php echo $genre['genreID'] ?>" /><span class="radio-label"><?php echo $genre['name'] ?></span><br />
					<?php else : ?>
						<input class="radio" type="checkbox" name="game-genre[]" value="<?php echo $genre['genreID'] ?>" /><span class="radio-label"><?php echo $genre['name'] ?></span><br />
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<div class="form-element">
				<label>Developer</label>
				<input type="text" name="game-developer" class="textbox" value="<?php echo $game['developer'] ?>" />
			</div>
			<div class="form-element">
				<label>Price:</label>
				<input type="number" step="any" name="game-price" class="textbox" value="<?php echo $game['price'] ?>" />
			</div>
			<div class="form-element">
				<input type="submit" class="button" />&nbsp;
				<input type="reset" class="button" />
			</div>
		</form>
	</div>
</body>
</html>