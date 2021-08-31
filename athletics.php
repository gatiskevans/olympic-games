<?php

    class Players {
        private array $players = [];

        public function __construct(int $noOfPlayers) {
            for($player = 1; $player <= $noOfPlayers; $player++){
                $character = readline("Choose a character/symbols or number for player: $player ");
                $this->players += [$player => $character];
            }
        }

        public function getPlayers(): array {
            return $this->players;
        }

        public function unsetPlayer(string $player){
            $playerRemove = array_search($player, $this->players);
            unset($this->players[$playerRemove]);
        }

        public function checkHowManyRunning(): int {
            return count($this->players);
        }

    }

    class Game {
        private array $grid = [];
        private array $randomValueForPlayers;
        private array $winners = [];
        private int $runwayLength = 25;
        private Players $players;

        public function __construct(int $noOfPlayers, Players $players) {
            $this->players = $players;
            $this->randomValueForPlayers = array_fill_keys($players->getPlayers(), 0);
            for($gridForPlayer = 1; $gridForPlayer <= $noOfPlayers; $gridForPlayer++){
                for($distanceLength = 1; $distanceLength < $this->runwayLength; $distanceLength++){
                    $this->grid[$players->getPlayers()[$gridForPlayer]][$distanceLength] = "-";
                }
            }
        }

        public function getGrid(): array {
            return $this->grid;
        }

        public function getWinners(): array {
            return $this->winners;
        }

        public function run(string $player){
                $this->grid[$player][$this->randomValueForPlayers[$player]] = "-";
                $this->randomValueForPlayers[$player] += rand(1, 2);
                $this->grid[$player][$this->randomValueForPlayers[$player]] = "$player";
        }

        public function findWinner(string $player) {
            if($this->randomValueForPlayers[$player] >= $this->runwayLength){
                $this->winners[] = $player;
                $this->players->unsetPlayer($player);
            }
        }

    }

    class DrawGame {

        private string $board;

        public function createBoard(array $grid){
            $draw = "";
            foreach($grid as $player => $board){
                $draw .= "$player | ";
                foreach($board as $step){
                    $draw .= "$step ";
                }
                $draw .= "\n";
            }
            $this->board = $draw;
        }

        public function getBoard(): string {
            return $this->board;
        }
    }

    $numberOfPlayers = (int) readline("How many players will participate? ");

    $players = new Players($numberOfPlayers);
    $game = new Game($numberOfPlayers, $players);
    $board = new DrawGame();

    $competition = readline("Press any button to start the race!");

    while(isset($competition)){
        $board->createBoard($game->getGrid());
        echo $board->getBoard();
        echo PHP_EOL . PHP_EOL . PHP_EOL;
        if($players->checkHowManyRunning() === 0) break;
        foreach($players->getPlayers() as $player){
            $game->run($player);
            $game->findWinner($player);
        }
        usleep('500000');

    }

    echo PHP_EOL;

    foreach($game->getWinners() as $position => $player){
        $position++;
        echo "{$position} place: player '$player'\n";
    }