<?php
declare(strict_types=1);

/**
 * CommandRegistry
 *
 * Single source of truth for available CLI commands.
 * No execution logic lives here.
 * This file only describes what commands EXIST.
 */
final class CommandRegistry
{
    public static function all(): array
    {
        return [
            'help' => [
                'description' => 'Show available commands',
            ],
            'version' => [
                'description' => 'Show system version',
            ],
        ];
    }

    public static function exists(string $command): bool
    {
        return array_key_exists($command, self::all());
    }

    public static function helpText(): string
    {
        $lines = ["Available commands:"];
        foreach (self::all() as $name => $meta) {
            $lines[] = sprintf("  %-10s %s", $name, $meta['description']);
        }

        return implode("\n", $lines);
    }
}
