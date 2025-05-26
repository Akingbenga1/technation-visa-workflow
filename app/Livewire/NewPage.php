<?php

namespace App\Livewire;

use Livewire\Component;

class NewPage extends Component
{
    public function render()
    {
        return view('livewire.new-page')->layout('layouts.guest');
    }
}
