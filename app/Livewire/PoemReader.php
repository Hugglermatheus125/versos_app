<?php

namespace App\Livewire;

use App\Models\Collection;
use App\Models\Poem;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PoemReader extends Component
{
    public int $currentPoem = 0;

    public bool $isLiked = false;

    public bool $isFavorite = false;

    public bool $isShared = false;

    public bool $showDrawer = false;

    public bool $showAccount = false;

    public string $selectedCategory = 'Todos os poemas';

    /** @var array<int, array{external_id: string, title: string, author: string, year: string, category: string, stanzas: array<int, array<int, string>>}> */
    public array $poems = [
        [
            'external_id' => 'canção-do-exílio',
            'title' => 'Canção do exílio',
            'author' => 'Gonçalves Dias',
            'year' => '1843',
            'category' => 'Natureza',
            'stanzas' => [
                ['Minha terra tem palmeiras,', 'Onde canta o Sabiá;', 'As aves, que aqui gorjeiam,', 'Não gorjeiam como lá.'],
                ['Nosso céu tem mais estrelas,', 'Nossas várzeas têm mais flores,', 'Nossos bosques têm mais vida,', 'Nossa vida mais amores.'],
                ['Em cismar, sozinho, à noite,', 'Mais prazer encontro eu lá;'],
            ],
        ],
        [
            'external_id' => 'meus-oito-anos',
            'title' => 'Meus oito anos',
            'author' => 'Casimiro de Abreu',
            'year' => '1859',
            'category' => 'Saudade',
            'stanzas' => [
                ['Oh! que saudades que tenho', 'Da aurora da minha vida,', 'Da minha infância querida', 'Que os anos não trazem mais!'],
                ['Que amor, que sonhos, que flores,', 'Naquelas tardes fagueiras', 'À sombra das bananeiras,', 'Debaixo dos laranjais!'],
            ],
        ],
    ];

    public function next(): void
    {
        $visiblePoems = $this->visiblePoems();

        if ($visiblePoems === []) {
            return;
        }

        $this->currentPoem = ($this->currentPoem + 1) % count($visiblePoems);
        $this->isShared = false;
        $this->refreshInteractionState();
    }

    public function select(int $index): void
    {
        if (! array_key_exists($index, $this->visiblePoems())) {
            return;
        }

        $this->currentPoem = $index;
        $this->isShared = false;
        $this->refreshInteractionState();
    }

    public function selectCategory(string $category): void
    {
        $allowed = ['Todos os poemas', 'Favoritos', 'Amor', 'Saudade', 'Natureza', 'Noite', 'Alma'];

        if (! in_array($category, $allowed, true)) {
            return;
        }

        $this->selectedCategory = $category;
        $this->currentPoem = 0;
        $this->showDrawer = false;
        $this->refreshInteractionState();
    }

    public function share(): void
    {
        $this->isShared = true;
    }

    public function closeOverlays(): void
    {
        $this->showDrawer = false;
        $this->showAccount = false;
    }

    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirectRoute('home');
    }

    public function toggleLike(): void
    {
        if (! Auth::check()) {
            $this->redirectRoute('login');

            return;
        }

        $poem = $this->persistCurrentPoem();
        Auth::user()->likes()->toggle($poem->id);
        $this->isLiked = Auth::user()->likes()->whereKey($poem->id)->exists();
    }

    public function toggleFavorite(): void
    {
        if (! Auth::check()) {
            $this->redirectRoute('login');

            return;
        }

        $poem = $this->persistCurrentPoem();
        $collection = Collection::firstOrCreate(['user_id' => Auth::id(), 'name' => 'Favoritos']);

        if ($collection->poems()->whereKey($poem->id)->exists()) {
            $collection->poems()->detach($poem->id);
            $this->isFavorite = false;
        } else {
            $collection->poems()->attach($poem->id, ['added_at' => now()]);
            $this->isFavorite = true;
        }
    }

    public function render()
    {
        $visiblePoems = $this->visiblePoems();

        return view('livewire.poem-reader', ['poem' => $visiblePoems[$this->currentPoem] ?? null])
            ->layout('layouts.reader');
    }

    private function persistCurrentPoem(): Poem
    {
        $current = $this->visiblePoems()[$this->currentPoem];

        return Poem::firstOrCreate(
            ['external_id' => $current['external_id'], 'source_api' => 'curated'],
            [
                'title' => $current['title'],
                'author' => $current['author'],
                'content' => collect($current['stanzas'])->flatten()->implode(PHP_EOL),
            ],
        );
    }

    private function refreshInteractionState(): void
    {
        $this->isLiked = false;
        $this->isFavorite = false;

        if (! Auth::check()) {
            return;
        }

        $current = $this->visiblePoems()[$this->currentPoem] ?? null;

        if (! $current) {
            return;
        }

        $poem = Poem::where('external_id', $current['external_id'])
            ->where('source_api', 'curated')
            ->first();

        if ($poem) {
            $this->isLiked = Auth::user()->likes()->whereKey($poem->id)->exists();
            $this->isFavorite = Auth::user()->collections()->whereHas('poems', fn ($query) => $query->whereKey($poem->id))->exists();
        }
    }

    public function visiblePoems(): array
    {
        if ($this->selectedCategory === 'Todos os poemas') {
            return $this->poems;
        }

        if ($this->selectedCategory === 'Favoritos') {
            if (! Auth::check()) {
                return [];
            }

            $favoriteIds = Auth::user()->collections()
                ->where('name', 'Favoritos')
                ->with('poems:id,external_id')
                ->get()
                ->flatMap(fn ($collection) => $collection->poems->pluck('external_id'))
                ->all();

            return array_values(array_filter($this->poems, fn ($poem) => in_array($poem['external_id'], $favoriteIds, true)));
        }

        return array_values(array_filter($this->poems, fn ($poem) => $poem['category'] === $this->selectedCategory));
    }
}
