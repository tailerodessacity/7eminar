<?php

namespace App\Features\Logging;

use Monolog\Handler\FilterHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LevelFilterHandler extends FilterHandler
{
    public function __construct(string $path, int $minLevel, int $maxLevel = Logger::EMERGENCY)
    {
        $handler = new StreamHandler($path);
        parent::__construct($handler, $minLevel, $maxLevel);
    }
}
