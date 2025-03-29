<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use Illuminate\Support\Str;

class CreateCompany extends Command
{
    protected $signature = 'create:company {name} {phone}';
    protected $description = 'Cria uma empresa com hash único';

    public function handle()
    {
        $name = $this->argument('name');
        $phone = $this->argument('phone');
        $hash = Str::random(256); // gera um hash seguro
        
        $company = Company::create([
            'name' => $name,
            'phone' => $phone,
            'hash' => $hash,
        ]);

        $this->info("Empresa '{$name}' criada com hash: {$hash}");
    }
}
