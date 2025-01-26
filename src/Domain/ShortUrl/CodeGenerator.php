<?php

declare(strict_types=1);

namespace Rodrifarias\ShortUrl\Domain\ShortUrl;

class CodeGenerator
{
    public static function create(): string
    {
        $randCode = (string) rand(1000, 99999);
        $randChar = rand(65, 90);
        $randInx = rand(0, 4);
        $randCode[$randInx] = chr($randChar);
        return $randCode;
    }
}
