<main class="reader-shell" wire:keydown.escape="closeOverlays">
    <header class="reader-header">
        <a class="reader-brand" href="{{ route('home') }}" wire:navigate aria-label="Verso, início">
            <span class="brand-mark">V</span>
            <span><strong>Verso</strong><small>todos os poemas</small></span>
        </a>
        <div class="reader-actions">
            <a class="premium-header-link" href="{{ route('settings') }}#premium" wire:navigate>Premium</a>
            @auth
                <button class="profile-button" type="button" wire:click="$set('showAccount', true)">
                    <span class="profile-name">{{ auth()->user()->name }}</span>
                    <span class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </button>
            @else
                <a class="profile-button" href="{{ route('login') }}" wire:navigate>
                    <span class="profile-name">Entrar</span>
                    <span class="profile-avatar">&rarr;</span>
                </a>
            @endauth
            <button class="menu-button" type="button" wire:click="$set('showDrawer', true)" aria-label="Abrir menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    <section class="poem-stage" aria-live="polite">
        @if ($poem)
            <div class="poem-meta"><span>{{ $poem['author'] }} &middot; {{ $poem['year'] }}</span><i></i></div>
            <article class="poem-copy">
                <p class="poem-label">{{ $poem['title'] }}</p>
                @foreach ($poem['stanzas'] as $stanza)
                    <div class="poem-stanza">
                        @foreach ($stanza as $line)<p>{{ $line }}</p>@endforeach
                    </div>
                @endforeach
                <div class="poem-actions">
                    <button type="button" wire:click="toggleLike" class="{{ $isLiked ? 'action-active' : '' }}" aria-label="Curtir poema">{{ $isLiked ? '♥' : '♡' }} <span>{{ $isLiked ? 'Curtido' : 'Curtir' }}</span></button>
                    <button type="button" wire:click="toggleFavorite" class="{{ $isFavorite ? 'action-active' : '' }}" aria-label="Favoritar poema">▣ <span>{{ $isFavorite ? 'Favoritado' : 'Favoritar' }}</span></button>
                    <button type="button" wire:click="share" class="{{ $isShared ? 'action-active' : '' }}" aria-label="Compartilhar poema">↗ <span>{{ $isShared ? 'Compartilhado' : 'Compartilhar' }}</span></button>
                </div>
            </article>
            <button class="next-poem" type="button" wire:click="next" aria-label="Próximo poema">&darr;</button>
        @else
            <div class="empty-state"><h2>Nenhum poema aqui ainda.</h2><p>Favorite poemas para encontrá-los nesta lista.</p><button type="button" wire:click="selectCategory('Todos os poemas')">Ver todos</button></div>
        @endif
    </section>

    <div class="poem-progress" aria-label="Poema {{ $currentPoem + 1 }} de {{ count($this->visiblePoems()) }}">
        <span>{{ str_pad($currentPoem + 1, 2, '0', STR_PAD_LEFT) }}</span><b>&mdash;</b><span>{{ str_pad(count($this->visiblePoems()), 2, '0', STR_PAD_LEFT) }}</span>
        <div class="progress-dots">
            @foreach ($this->visiblePoems() as $index => $item)
                <button type="button" class="{{ $index === $currentPoem ? 'active' : '' }}" wire:click="select({{ $index }})" aria-label="Ler {{ $item['title'] }}"></button>
            @endforeach
        </div>
    </div>

    @if ($showDrawer)
        <div class="drawer-layer" wire:click.self="$set('showDrawer', false)">
            <aside class="menu-drawer" role="dialog" aria-label="Menu principal">
                <div class="drawer-top"><a class="reader-brand" href="{{ route('home') }}" wire:navigate><span class="brand-mark">V</span><span><strong>Verso</strong><small>poemas para ler devagar</small></span></a><button class="drawer-close" type="button" wire:click="$set('showDrawer', false)" aria-label="Fechar menu">&times;</button></div>
                <nav class="drawer-nav"><p class="drawer-label">navegar</p><button class="drawer-link {{ $selectedCategory === 'Todos os poemas' ? 'selected' : '' }}" type="button" wire:click="selectCategory('Todos os poemas')"><span>▣</span> Todos os poemas</button><button class="drawer-link {{ $selectedCategory === 'Favoritos' ? 'selected' : '' }}" type="button" wire:click="selectCategory('Favoritos')"><span>♡</span> Favoritos</button></nav>
                <div class="category-area"><p class="drawer-label">categorias</p><div class="category-list">@foreach (['Amor', 'Saudade', 'Natureza', 'Noite', 'Alma'] as $category)<button class="{{ $selectedCategory === $category ? 'selected' : '' }}" type="button" wire:click="selectCategory('{{ $category }}')">{{ $category }}</button>@endforeach</div></div>
                <div class="drawer-bottom"><div class="daily-thought"><p class="drawer-label">pensamento do dia</p><q>A leitura em silêncio é também uma forma de companhia.</q></div><p class="drawer-description">Verso reúne poemas para ler sem pressa.</p><a class="premium-link" href="{{ route('settings') }}#premium" wire:navigate>Conhecer Premium <span>&rarr;</span></a></div>
            </aside>
        </div>
    @endif

    @if ($showAccount)
        <div class="account-overlay" wire:click.self="$set('showAccount', false)"><section class="account-modal" role="dialog" aria-label="Minha conta"><div class="account-modal-heading"><div><p class="eyebrow text-coral">minha conta</p><h2>Olá, {{ auth()->user()->name }}</h2></div><button class="account-close" type="button" wire:click="$set('showAccount', false)" aria-label="Fechar conta">&times;</button></div><div class="account-summary"><span class="profile-avatar profile-avatar-large">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><div><strong>{{ auth()->user()->name }}</strong><small>conta gratuita</small></div></div><a class="account-option premium-option" href="{{ route('settings') }}#premium" wire:navigate><span><b>Tornar-se Premium</b><small>Uma experiência sem interrupções.</small></span><span>&rarr;</span></a><a class="account-option" href="{{ route('settings') }}" wire:navigate><span><b>Configurações</b><small>Preferências e aparência.</small></span><span>&rarr;</span></a><button class="account-logout" type="button" wire:click="logout">Sair da conta</button></section></div>
    @endif
</main>
