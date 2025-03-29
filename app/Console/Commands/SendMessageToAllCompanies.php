<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Company;

class SendMessageToAllCompanies extends Command
{
    protected $signature = 'whatsapp:sendAll';

    protected $description = 'Envia uma mensagem via API para todas as empresas cadastradas';

    public function handle()
    {
        // Carrega todas as empresas
        $companies = Company::all();

        // Itera sobre cada empresa
        foreach ($companies as $company) {
            // Ajuste se o nome da coluna de telefone for outro, ex.: phone_number
            $number = $company->phone;
            $hash = $company->hash;

            $url = "https://votacao.ajudatche.com/vote/{$hash}";
            $messages = [];
            $messages[] = "Você foi convocado para uma votação do CDL. Representando a empresa {$company->name}. ATENÇÃO APÓS REALIZADO O VOTO, NÃO É POSSÍVEL ALTERA-LO. Para votar basta acessar o seguinte link:";
            $messages[] = $url;
            
            // Se a empresa não tiver número, pode pular
            if (!$number) {
                $this->warn("Empresa {$company->name} não tem telefone cadastrado. Pulando...");
                continue;
            }

            try {
                // Faz a requisição POST
                foreach($messages as $message) {
                    $response = Http::post('http://localhost:3000/send', [
                        'number'  => $number,
                        'message' => $message,
                    ]);
    
                    // Verifica se deu certo
                    if ($response->successful()) {
                        $this->info("Mensagem enviada para {$company->name} (Telefone: $number)");
                    } else {
                        $this->error("Falha ao enviar para {$company->name} (Telefone: $number). Resposta: " . $response->body());
                    }
                }
                
            } catch (\Exception $e) {
                $this->error("Erro ao enviar para {$company->name} (Telefone: $number): " . $e->getMessage());
            }
        }
    }
}
