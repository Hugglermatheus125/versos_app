<?php

namespace Database\Seeders;

use App\Models\Poem;
use Illuminate\Database\Seeder;

class PoemSeeder extends Seeder
{
    public function run(): void
    {
        $authors = ['Autor de teste', 'Pessoa visitante', 'Escrita local'];
        $themes = ['amor', 'saudade', 'natureza', 'noite', 'alma'];

        foreach (range(1, 5) as $number) {
            $theme = $themes[$number - 1];

            Poem::updateOrCreate(
                ['external_id' => "seed-poem-{$number}", 'source_api' => 'seed'],
                [
                    'title' => "Poema de teste {$number}",
                    'author' => $authors[array_rand($authors)],
                    'content' => "Um verso simples para testar o leitor.\n\nEste conteúdo será substituído quando uma fonte de poemas for integrada.",
                ],
            );
        }
    }
}
