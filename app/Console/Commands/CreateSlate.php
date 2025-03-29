<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Slate;

class CreateSlate extends Command
{
    protected $signature = 'create:slate {name}';
    protected $description = 'Cria uma chapa para a votação';

    public function handle()
    {
        $name = $this->argument('name');
        
        $slate = Slate::create([
            'name' => $name
        ]);

        $this->info("Chapa '{$name}' criada com sucesso!");
    }
}
