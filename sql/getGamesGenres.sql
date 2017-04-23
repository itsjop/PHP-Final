SELECT game_genres.gameID, genres.genreID, genres.name
FROM game_genres
JOIN genres on game_genres.genreID = genres.genreID;