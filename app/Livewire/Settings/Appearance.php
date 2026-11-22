<?php

namespace App\Livewire\Settings;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Appearance settings')]
#[Layout('layouts.admin')]
class Appearance extends Component
{
    public function render()
    {
        return view('livewire.settings.appearance')->layout(
            auth()->user()?->role === 'admin' ? 'layouts.admin' : 'layouts.app'
        );
    }
}
