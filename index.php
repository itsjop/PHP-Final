<?php

// Create and include a configuration file with the database connection
include('config.php');

// Include functions for application
include('functions.php');

// Get search term from URL using the get function
$term = get('search-term');

// Get a list of books using the searchBooks function
// Print the results of search results
// Add a link printed for each book to game.php with an passing the isbn
// Add a link printed for each book to form.php with an action of edit and passing the isbn
$games = searchGames($term, $database);

?>

    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">

        <title>Games</title>
        <meta name="description" content="The HTML5 Herald">
        <meta name="author" content="SitePoint">

        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  	<![endif]-->
    </head>

    <body>

        <!--<p>Currently accessed by
            <?php echo $_SESSION['name'] ?>
        </p>-->
        <!--<p><a href="logout.php">Logout</a></p>-->
        <div class="page">
            <span class="pagetitle">Games</span>
            <form class="search" method="GET">
                <input type="text" name="search-term" placeholder="Search..." />
                <input type="submit" />
            </form>
            <br>
            <hr>
            <?php foreach($games as $game) : ?>
            <div class="game_card" style="background-image: url('images/<?php echo $game['art'] ?>')">
                <div class="details">
                    <p><span class="gametitle"><?php echo $game['title']; ?><br /></span></p>
                    <div class="info">
                        <p class="price">$
                            <?php echo $game['price']; ?>
                        </p>
                        <p class="developer">Developer: <br>
                            <span class="dev2"><?php echo $game['developer']; ?> </span></p>
                    </div>

                    <a class="game-icon game-edit" href="form.php?action=edit&gameID=<?php echo $game['gameID'] ?>"><i class="  fa fa-pencil-square" aria-hidden="true"></i></a>

                    <a class="game-icon game-view" href="game.php?gameID=<?php echo $game['gameID'] ?>"><i class=" fa fa-search" aria-hidden="true"></i></a>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- print currently accessed by the current username -->


            <!-- A link to the logout.php file -->
            <p>

            </p>
        </div>
    </body>

    </html>
