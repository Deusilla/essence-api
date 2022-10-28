<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;

abstract class AbstractCommand extends Command
{
    /**
     * @var string cut command prefix
     */
    private const COMMAND_PREFIX = 'App\\Command\\';

    /**
     * @var string cut command suffix
     */
    private const COMMAND_SUFFIX = 'Command';

    /**
     * @var string command namespace prefix
     */
    private const COMMAND_NAMESPACE_PREFIX = 'essence';

    /**
     * @var string word command separator
     */
    private const WORD_SEPARATOR = '-';

    /**
     * @var string namespace command separator
     */
    private const NAMESPACE_SEPARATOR = ':';

    public function __construct()
    {
        parent::__construct($this->getCommandName());
    }

    /**
     * @return string
     */
    private function getCommandName(): string
    {
        $parts = array_map(
            fn (string $part) => $this->wordToSlug($part),
            $this->separateNamespace(static::class)
        );

        return implode(
            self::NAMESPACE_SEPARATOR,
            [
                self::COMMAND_NAMESPACE_PREFIX,
                ...$parts,
            ]
        );
    }

    /**
     * @param string $type
     *
     * @return array<string>
     */
    private function separateNamespace(string $type): array
    {
        $type = str_replace(self::COMMAND_PREFIX, '', $type);

        if (str_ends_with($type, self::COMMAND_SUFFIX)) {
            $type = substr($type, 0, -1 * mb_strlen(self::COMMAND_SUFFIX));
        }

        return explode('\\', $type);
    }

    /**
     * @param string $source
     *
     * @return string
     */
    private function wordToSlug(string $source): string
    {
        $baseSplit = str_split($source);
        $upperSplit = str_split(strtoupper($source));
        $intersect = array_intersect($baseSplit, $upperSplit);
        $words = $this->splitByPoints($source, array_keys($intersect));

        return implode(self::WORD_SEPARATOR, array_map(
            fn (string $word) => strtolower($word),
            $words,
        ));
    }

    /**
     * @param string     $source
     * @param array<int> $points
     *
     * @return array<string>
     */
    private function splitByPoints(string $source, array $points): array
    {
        $points[] = mb_strlen($source);
        $words = [];
        $prev = 0;

        foreach ($points as $point) {
            if ($point !== $prev) {
                $length = $point - $prev;
                $words[] = mb_substr($source, $prev, $length);
            }

            $prev = $point;
        }

        return $words;
    }
}
