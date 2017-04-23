SELECT *
FROM game_genres
JOIN genres on game_genres.genreID = genres.genreID
WHERE gameID = :gameID