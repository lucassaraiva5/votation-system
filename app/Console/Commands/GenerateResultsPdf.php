<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Slate;
use PDF;

class GenerateResultsPdf extends Command
{
    protected $signature = 'votation:pdf';
    protected $description = 'Gera um PDF com o resultado da votação e exibe o link no terminal';

    public function handle()
    {
        // 1. Obter as chapas com contagem de votos
        $chapas = Slate::withCount('votes')
            ->orderByDesc('votes_count')
            ->get();

        // 2. Gerar conteúdo HTML para o PDF (pode vir de uma view Blade)
        // Exemplo simples inline (poderia usar View::make('pdf.resultados', compact('chapas')))
        $html = '<h1>Resultado da Votação</h1><ul>';
        foreach ($chapas as $chapa) {
            $html .= '<li>'.$chapa->name.' - '.$chapa->votes_count.' votos</li>';
        }
        $html .= '</ul>';

        // 3. Gerar o PDF com a biblioteca Barryvdh DomPDF
        $pdf = PDF::loadHTML($html);

        // 4. Definir onde salvar (por exemplo, na pasta public/pdfs)
        // Certifique-se de que essa pasta exista e que Laravel tenha permissão de escrita.
        $fileName = 'resultados.pdf';
        $path = public_path('pdfs/' . $fileName);

        // Salva o PDF em disco
        $pdf->save($path);

        // 5. Montar o link para acessar esse PDF
        // Se seu domínio for, por ex., 'https://votacao.example.com', e a pasta 'public/pdfs'
        // for acessível em 'https://votacao.example.com/pdfs'
        $domain = 'https://votacao.ajudatche.com'; // Ajuste conforme seu ambiente
        $linkPdf = $domain . '/pdfs/' . $fileName;

        // 6. Exibir mensagem no terminal
        $this->info('PDF gerado com sucesso em: ' . $path);
        $this->info('Link para acessar no navegador: ' . $linkPdf);

        return 0;
    }
}
