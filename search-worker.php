<?php
// Este arquivo serve como um endpoint para buscas de jogos
// Ele implementa a funcionalidade que antes era do search-worker.js

// Definir cabeçalhos para permitir acesso e indicar o tipo de conteúdo
header('Content-Type: application/json');
header('Cache-Control: max-age=3600'); // Cache por 1 hora

// Obter a consulta de pesquisa
$query = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';

// Verificar se a consulta está vazia
if (empty($query)) {
    echo json_encode([]);
    exit;
}

// Diretório para arquivos de cache
$cache_dir = sys_get_temp_dir() . '/game_cache/';
if (!is_dir($cache_dir)) {
    mkdir($cache_dir, 0755, true);
}

// Função para obter jogos baseados na primeira letra
function getGamesByFirstLetter($letter) {
    global $cache_dir;
    
    // Verificar cache
    $cache_file = $cache_dir . 'games_' . $letter . '.json';
    
    if (file_exists($cache_file) && (time() - filemtime($cache_file) < 86400)) {
        // Cache válido por 24 horas
        return json_decode(file_get_contents($cache_file), true);
    }
    
    // Se não tiver cache, buscar dados
    // Em uma implementação real, você conectaria ao banco de dados
    // Aqui vamos usar dados estáticos organizados por letra para simular
    
    $games_by_letter = [];
    
    // Alguns jogos populares para cada letra
    $all_games = [
        'a' => [
            ['id' => 1, 'name' => 'Assassin\'s Creed Valhalla'],
            ['id' => 2, 'name' => 'Apex Legends'],
            ['id' => 3, 'name' => 'Atomic Heart'],
            ['id' => 4, 'name' => 'Age of Empires IV'],
            ['id' => 5, 'name' => 'Among Us'],
        ],
        'b' => [
            ['id' => 6, 'name' => 'Battlefield 2042'],  
            ['id' => 7, 'name' => 'Borderlands 3'],
            ['id' => 8, 'name' => 'Black Desert Online'],
            ['id' => 9, 'name' => 'Baldur\'s Gate 3'],
            ['id' => 10, 'name' => 'Biomutant'],
        ],
        'c' => [
            ['id' => 11, 'name' => 'Cyberpunk 2077'],
            ['id' => 12, 'name' => 'Counter-Strike 2'],
            ['id' => 13, 'name' => 'Call of Duty: Modern Warfare II'],
            ['id' => 14, 'name' => 'Control'],
            ['id' => 15, 'name' => 'Cities: Skylines'],
        ],
        'd' => [
            ['id' => 16, 'name' => 'Destiny 2'],
            ['id' => 17, 'name' => 'Death Stranding'],
            ['id' => 18, 'name' => 'Dying Light 2'],
            ['id' => 19, 'name' => 'Disco Elysium'],
            ['id' => 20, 'name' => 'DOOM Eternal'],
        ],
        'e' => [
            ['id' => 21, 'name' => 'Elden Ring'],
            ['id' => 22, 'name' => 'Escape from Tarkov'],
            ['id' => 23, 'name' => 'Euro Truck Simulator 2'],
            ['id' => 24, 'name' => 'EVE Online'],
            ['id' => 25, 'name' => 'Elite Dangerous'],
        ],
        'f' => [
            ['id' => 26, 'name' => 'FIFA 23'],
            ['id' => 27, 'name' => 'Fortnite'],
            ['id' => 28, 'name' => 'Final Fantasy XIV'],
            ['id' => 29, 'name' => 'Forza Horizon 5'],
            ['id' => 30, 'name' => 'Fallout 76'],
        ],
        'g' => [
            ['id' => 31, 'name' => 'God of War Ragnarök'],
            ['id' => 32, 'name' => 'Genshin Impact'],
            ['id' => 33, 'name' => 'Grand Theft Auto V'],
            ['id' => 34, 'name' => 'Ghost of Tsushima'],
            ['id' => 35, 'name' => 'Ghostwire: Tokyo'],
        ],
        'h' => [
            ['id' => 36, 'name' => 'Horizon Forbidden West'],
            ['id' => 37, 'name' => 'Halo Infinite'],
            ['id' => 38, 'name' => 'Hogwarts Legacy'],
            ['id' => 39, 'name' => 'Hunt: Showdown'],
            ['id' => 40, 'name' => 'Half-Life: Alyx'],
        ],
        'i' => [
            ['id' => 41, 'name' => 'It Takes Two'],
            ['id' => 42, 'name' => 'Inscryption'],
            ['id' => 43, 'name' => 'Immortals Fenyx Rising'],
            ['id' => 44, 'name' => 'Inside'],
            ['id' => 45, 'name' => 'Injustice 2'],
        ],
        'j' => [
            ['id' => 46, 'name' => 'Just Cause 4'],
            ['id' => 47, 'name' => 'Jump Force'],
            ['id' => 48, 'name' => 'Journey'],
            ['id' => 49, 'name' => 'Jurassic World Evolution 2'],
            ['id' => 50, 'name' => 'Jedi: Fallen Order'],
        ],
        'k' => [
            ['id' => 51, 'name' => 'Kingdom Hearts III'],
            ['id' => 52, 'name' => 'Kena: Bridge of Spirits'],
            ['id' => 53, 'name' => 'Killing Floor 2'],
            ['id' => 54, 'name' => 'Knockout City'],
            ['id' => 55, 'name' => 'Kerbal Space Program'],
        ],
        'l' => [
            ['id' => 56, 'name' => 'Lost Ark'],
            ['id' => 57, 'name' => 'League of Legends'],
            ['id' => 58, 'name' => 'Little Nightmares II'],
            ['id' => 59, 'name' => 'Life is Strange: True Colors'],
            ['id' => 60, 'name' => 'Left 4 Dead 2'],
        ],
        'm' => [
            ['id' => 61, 'name' => 'Minecraft'],
            ['id' => 62, 'name' => 'Marvel\'s Spider-Man'],
            ['id' => 63, 'name' => 'Mass Effect Legendary Edition'],
            ['id' => 64, 'name' => 'Monster Hunter Rise'],
            ['id' => 65, 'name' => 'Microsoft Flight Simulator'],
        ],
        'n' => [
            ['id' => 66, 'name' => 'No Man\'s Sky'],
            ['id' => 67, 'name' => 'NBA 2K23'],
            ['id' => 68, 'name' => 'Need for Speed Heat'],
            ['id' => 69, 'name' => 'New World'],
            ['id' => 70, 'name' => 'NieR:Automata'],
        ],
        'o' => [
            ['id' => 71, 'name' => 'Overwatch 2'],
            ['id' => 72, 'name' => 'Outer Wilds'],
            ['id' => 73, 'name' => 'Outlast'],
            ['id' => 74, 'name' => 'Ori and the Will of the Wisps'],
            ['id' => 75, 'name' => 'Outriders'],
        ],
        'p' => [
            ['id' => 76, 'name' => 'Path of Exile'],
            ['id' => 77, 'name' => 'PlayerUnknown\'s Battlegrounds'],
            ['id' => 78, 'name' => 'Portal 2'],
            ['id' => 79, 'name' => 'Psychonauts 2'],
            ['id' => 80, 'name' => 'Prey'],
        ],
        'r' => [
            ['id' => 81, 'name' => 'Red Dead Redemption 2'],
            ['id' => 82, 'name' => 'Resident Evil Village'],
            ['id' => 83, 'name' => 'Rainbow Six Siege'],
            ['id' => 84, 'name' => 'Rust'],
            ['id' => 85, 'name' => 'Rocket League'],
        ],
        's' => [
            ['id' => 86, 'name' => 'Starfield'],
            ['id' => 87, 'name' => 'Sea of Thieves'],
            ['id' => 88, 'name' => 'Stardew Valley'],
            ['id' => 89, 'name' => 'Star Wars Jedi: Survivor'],
            ['id' => 90, 'name' => 'Sekiro: Shadows Die Twice'],
        ],
        't' => [
            ['id' => 91, 'name' => 'The Witcher 3: Wild Hunt'],
            ['id' => 92, 'name' => 'The Last of Us Part II'],
            ['id' => 93, 'name' => 'Terraria'],
            ['id' => 94, 'name' => 'The Legend of Zelda: Tears of the Kingdom'],
            ['id' => 95, 'name' => 'The Elder Scrolls V: Skyrim'],
        ],
        'v' => [
            ['id' => 96, 'name' => 'Valorant'],
            ['id' => 97, 'name' => 'Vampire Survivors'],
            ['id' => 98, 'name' => 'VRChat'],
            ['id' => 99, 'name' => 'Valheim'],
            ['id' => 100, 'name' => 'V Rising'],
        ],
        'w' => [
            ['id' => 101, 'name' => 'Warframe'],
            ['id' => 102, 'name' => 'World of Warcraft'],
            ['id' => 103, 'name' => 'Warhammer 40,000: Darktide'],
            ['id' => 104, 'name' => 'Watch Dogs: Legion'],
            ['id' => 105, 'name' => 'Wolfenstein: Youngblood'],
        ],
    ];
    
    // Se a letra existe no array
    if (isset($all_games[$letter])) {
        $games_by_letter = $all_games[$letter];
    }
    
    // Salvar em cache
    file_put_contents($cache_file, json_encode($games_by_letter));
    
    return $games_by_letter;
}

// Obter a primeira letra da consulta
$first_letter = substr($query, 0, 1);
$rest_of_query = substr($query, 1);

// Obter jogos pela primeira letra
$games_by_first_letter = getGamesByFirstLetter($first_letter);

// Filtrar jogos que contêm o resto da consulta
$filtered_games = [];
foreach ($games_by_first_letter as $game) {
    if (strpos(strtolower($game['name']), $rest_of_query) !== false) {
        $filtered_games[] = $game;
    }
}

// Limitar número de resultados
$filtered_games = array_slice($filtered_games, 0, 10);

// Retornar os resultados como JSON
echo json_encode($filtered_games);
?>