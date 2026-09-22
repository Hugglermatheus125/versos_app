<main class="settings-page">
    <header class="settings-header">
        <a class="back-link" href="{{ route('home') }}" wire:navigate>&larr; voltar para poemas</a>
        <a class="brand-word" href="{{ route('home') }}" wire:navigate>Verso</a>
    </header>

    <section class="settings-content">
        <p class="eyebrow text-coral">sua conta</p>
        <h1>Configurações</h1>
        <p class="settings-intro">Cuide do seu espaço de leitura e escolha como quer viver o Verso.</p>

        <div class="account-card">
            <span class="account-avatar large">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            <div>
                <h2>{{ auth()->user()->name }}</h2>
                <p>{{ auth()->user()->email }} &middot; conta gratuita</p>
            </div>
            <a class="text-link" href="{{ route('profile') }}" wire:navigate>Editar</a>
        </div>

        <form wire:submit="save" class="settings-list">
            <label>
                <span><strong>Aparência</strong><small>Modo de leitura da interface.</small></span>
                <select wire:model="theme"><option value="paper">Papel</option></select>
            </label>
            <label>
                <span><strong>Tamanho do texto</strong><small>Escolha o conforto para ler.</small></span>
                <select wire:model="fontSize"><option value="small">Pequeno</option><option value="medium">Médio</option><option value="large">Grande</option></select>
            </label>
            <label>
                <span><strong>Modo foco</strong><small>Uma interface mais silenciosa para ler.</small></span>
                <input type="checkbox" wire:model="focusMode">
            </label>
            <label>
                <span><strong>Lembretes de leitura</strong><small>Receba um convite gentil para voltar.</small></span>
                <input type="checkbox" wire:model="reminders">
            </label>
            <label>
                <span><strong>Idioma</strong><small>Idioma principal da interface.</small></span>
                <select wire:model="language"><option value="pt">Português</option><option value="en">English</option></select>
            </label>
            <div class="settings-submit">
                <button class="button-primary" type="submit">Salvar preferências</button>
                @if (session('status'))<span class="form-status">{{ session('status') }}</span>@endif
            </div>
        </form>

        <section id="premium" class="premium-card">
            <div><p class="eyebrow">verso premium</p><h2>Mais tempo para o que importa.</h2><p>Uma experiência sem interrupções para seus poemas.</p></div>
            <button class="button-primary" type="button" disabled>Em breve <span>&rarr;</span></button>
        </section>
    </section>
</main>
