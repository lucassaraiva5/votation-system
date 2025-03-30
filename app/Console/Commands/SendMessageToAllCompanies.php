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
                    $ch = curl_init();

                    $host = env('WHATSAPP_API') . "/send";
                    
                    curl_setopt($ch, CURLOPT_URL, $host);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                        'number'  => $number,
                        'message' => $message,
                    ]));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json'
                    ]);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    $response = curl_exec($ch);

                    if (curl_errno($ch)) {
                        $this->error("cURL error: " . curl_error($ch));
                        curl_close($ch);
                        continue;
                    }

                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);

                    // Verifica se deu certo
                    if ($httpCode >= 200 && $httpCode < 300) {
                        $this->info("Mensagem enviada para {$company->name} (Telefone: $number)");
                    } else {
                        $this->error("Falha ao enviar para {$company->name} (Telefone: $number). HTTP Code: $httpCode. Resposta: " . $response);
                    }
                }
                
            } catch (\Exception $e) {
                $this->error("Erro ao enviar para {$company->name} (Telefone: $number): " . $e->getMessage());
            }
        }
    }
}
