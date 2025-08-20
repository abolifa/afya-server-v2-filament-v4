<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\ToggleButtons;

class BooleanField extends ToggleButtons
{
    public function setUp(): void
    {
        parent::setUp();

        $this
            ->label('نشط')
            ->boolean()
            ->inline()
            ->grouped()
            ->required()
            ->default(true);
    }
}
