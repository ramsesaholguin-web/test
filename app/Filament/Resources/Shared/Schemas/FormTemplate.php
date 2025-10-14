<?php

namespace App\Filament\Resources\Shared\Schemas;

use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;

/**
 * Small helper to centralize form layout and component defaults to match the
 * Vehicle form design (groups, sections, columns, and common field defaults).
 */
class FormTemplate
{
    public static function basicSection(string $title, array $schema, int $columns = 2): Section
    {
        return Section::make($title)
            ->schema($schema)
            ->columns($columns);
    }

    public static function groupWithSection(array $sections): Group
    {
        return Group::make()
            ->schema($sections);
    }

    public static function labeledText(string $name, ?string $label = null, bool $required = false): TextInput
    {
        $input = TextInput::make($name);

        if ($label) {
            $input->label($label);
        }

        if ($required) {
            $input->required();
        }

        return $input;
    }
}
