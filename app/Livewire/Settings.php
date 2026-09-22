<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Settings extends Component
{
    public string $theme = 'paper';

    public string $fontSize = 'medium';

    public bool $focusMode = false;

    public bool $reminders = true;

    public string $language = 'pt';

    public function mount(): void
    {
        $settings = Auth::user()->settings;

        if ($settings) {
            $this->theme = $settings->theme;
            $this->fontSize = $settings->font_size;
        }
    }

    public function save(): void
    {
        $this->validate([
            'theme' => ['required', 'in:paper'],
            'fontSize' => ['required', 'in:small,medium,large'],
            'language' => ['required', 'in:pt,en'],
        ]);

        Auth::user()->settings()->updateOrCreate([], [
            'theme' => $this->theme,
            'font_size' => $this->fontSize,
        ]);

        session()->flash('status', 'Preferências salvas.');
    }

    public function render()
    {
        return view('livewire.settings')->layout('layouts.reader');
    }
}
