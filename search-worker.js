let games = {};

fetch("./steam-games-by-letter.json")
  .then((response) => response.json())
  .then((data) => {
    games = data;
  });

self.onmessage = function (event) {
  const { query } = event.data;

  const [firstChar, ...rest] = query;

  const gamesByFirstChar = games[firstChar];

  self.postMessage(
    gamesByFirstChar.filter((game) => game.name.includes(rest.join("")))
  );
};
