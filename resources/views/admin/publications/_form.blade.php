@php
    $isEdit = $publication !== null;
    $action = $isEdit ? route('admin.publications.update', $publication) : route('admin.publications.store');
    $existingBlocks = $isEdit
        ? $publication->blocks->map(fn ($b) => [
            'type' => $b->type,
            'content' => $b->content ?? [],
            'existing_file_path' => $b->file_path,
            'file_url' => $b->fileUrl(),
        ])->values()->all()
        : [['type' => 'text', 'content' => ['text' => ''], 'existing_file_path' => null, 'file_url' => null]];
    $existingTags = $isEdit
        ? $publication->mentions()->with('mentionedUser')->get()->map(fn($m) => [
            'id' => $m->mentionedUser->id,
            'name' => $m->mentionedUser->name,
            'username' => $m->mentionedUser->username
        ])->values()->all()
        : [];
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data"
      x-data="blockEditor({{ Js::from($existingBlocks) }}, {{ Js::from($existingTags) }})">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="feed-card p-6 space-y-6 mb-6">
        <div>
            <label class="ig-label">Titre</label>
            <input type="text" name="title" value="{{ old('title', $publication?->title) }}" required class="ig-input mt-1">
            @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="ig-label">Statut</label>
            @php($defaultStatus = $publication?->status ?? 'published')
            <select name="status" class="ig-input mt-1">
                <option value="draft" @selected(old('status', $defaultStatus) === 'draft')>Brouillon</option>
                <option value="published" @selected(old('status', $defaultStatus) === 'published')>Publié</option>
            </select>
        </div>

        <div>
            <label class="ig-label">Date de publication (programmation)</label>
            <input type="date" name="published_at"
                   value="{{ old('published_at', $publication?->published_at?->format('Y-m-d')) }}"
                   class="ig-input mt-1">
            <p class="text-xs text-ig-muted mt-1">Laissez vide pour publier tout de suite. Une date future affichera l'article dans le fil seulement à partir de ce jour (minuit).</p>
        </div>

        <div>
            <label class="ig-label">Identifier des personnes (Tags)</label>
            <div class="relative mt-1">
                <input type="text" x-model="searchQuery" @input.debounce.300ms="searchUsers" placeholder="Rechercher par nom ou pseudo..." class="ig-input w-full">
                <div x-show="searchResults.length > 0" @click.away="searchResults = []" class="absolute z-10 w-full mt-1 bg-white border border-ig-border rounded-lg shadow-lg">
                    <template x-for="user in searchResults" :key="user.id">
                        <button type="button" @click="addTag(user)" class="w-full text-left px-4 py-2 text-sm hover:bg-ig-hover">
                            <span x-text="user.name" class="font-medium text-ig-dark"></span>
                            <span class="text-ig-muted text-xs ml-1">@<span x-text="user.username"></span></span>
                        </button>
                    </template>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 mt-3">
                <template x-for="tag in tags" :key="tag.id">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-ig-pink/10 text-ig-pink">
                        <span x-text="tag.name"></span>
                        <input type="hidden" name="tagged_users[]" :value="tag.id">
                        <button type="button" @click="removeTag(tag)" class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-ig-pink/20 focus:outline-none">
                            <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8"><path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7" /></svg>
                        </button>
                    </span>
                </template>
            </div>
        </div>
    </div>

    <div class="feed-card p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-ig-dark">Blocs de contenu</h3>
            <div class="flex gap-2 flex-wrap">
                @foreach (['text' => 'Texte', 'image' => 'Image', 'video' => 'Vidéo', 'audio' => 'Audio', 'gif' => 'GIF', 'file' => 'Fichier', 'embed' => 'Intégration'] as $type => $label)
                    <button type="button" @click="addBlock('{{ $type }}')"
                            class="text-xs px-2 py-1 rounded-lg bg-ig-hover text-ig-dark font-medium hover:bg-ig-pink/10 hover:text-ig-pink">+ {{ $label }}</button>
                @endforeach
            </div>
        </div>

        <template x-for="(block, index) in blocks" :key="index">
            <div class="border border-ig-border rounded-xl p-4 mb-4 bg-ig-surface/50">
                <div class="flex justify-between mb-3">
                    <span class="text-sm font-medium text-gray-700" x-text="block.type"></span>
                    <div class="flex gap-2">
                        <button type="button" @click="moveUp(index)" class="text-xs text-gray-500" x-show="index > 0">↑</button>
                        <button type="button" @click="moveDown(index)" class="text-xs text-gray-500" x-show="index < blocks.length - 1">↓</button>
                        <button type="button" @click="removeBlock(index)" class="text-xs text-red-600">Supprimer</button>
                    </div>
                </div>

                <input type="hidden" :name="'blocks['+index+'][type]'" x-model="block.type">

                <template x-if="block.type === 'text'">
                    <textarea :name="'blocks['+index+'][content][text]'" rows="4" x-model="block.content.text"
                              class="w-full rounded-md border-gray-300" placeholder="Contenu texte…"></textarea>
                </template>

                <template x-if="['image','gif','video','audio','file'].includes(block.type)">
                    <div class="space-y-2">
                        <template x-if="block.file_url">
                            <div class="space-y-2">
                                <p class="text-sm text-gray-500">Fichier actuel : <a :href="block.file_url" target="_blank" class="text-indigo-600">voir</a></p>
                                <input type="hidden" :name="'blocks['+index+'][existing_file_path]'" x-model="block.existing_file_path">
                            </div>
                        </template>
                        <input type="file" :name="'blocks['+index+'][file]'" class="text-sm">
                        <input type="text" :name="'blocks['+index+'][content][caption]'" x-model="block.content.caption"
                               placeholder="Légende (optionnel)" class="w-full rounded-md border-gray-300 text-sm">
                    </div>
                </template>

                <template x-if="block.type === 'video' || block.type === 'embed'">
                    <input type="url" :name="'blocks['+index+'][content][url]'" x-model="block.content.url"
                           placeholder="URL d'intégration (YouTube, Vimeo…)" class="w-full rounded-md border-gray-300 text-sm mt-2">
                </template>
            </div>
        </template>
    </div>

    <div class="mt-6 flex gap-4">
        <button type="submit" class="btn-ig">{{ $isEdit ? 'Enregistrer' : 'Créer' }}</button>
        <a href="{{ route('admin.publications.index') }}" class="btn-ig-outline ms-3">Annuler</a>
    </div>
</form>

<script>
function blockEditor(initialBlocks, initialTags) {
    return {
        blocks: initialBlocks.length ? initialBlocks : [{ type: 'text', content: { text: '' }, existing_file_path: null, file_url: null }],
        tags: initialTags || [],
        searchQuery: '',
        searchResults: [],

        addBlock(type) {
            const content = type === 'text' ? { text: '' } : (type === 'embed' || type === 'video' ? { url: '', caption: '' } : { caption: '' });
            this.blocks.push({ type, content, existing_file_path: null, file_url: null });
        },
        removeBlock(index) {
            this.blocks.splice(index, 1);
        },
        moveUp(index) {
            if (index > 0) {
                [this.blocks[index - 1], this.blocks[index]] = [this.blocks[index], this.blocks[index - 1]];
            }
        },
        moveDown(index) {
            if (index < this.blocks.length - 1) {
                [this.blocks[index + 1], this.blocks[index]] = [this.blocks[index], this.blocks[index + 1]];
            }
        },
        
        async searchUsers() {
            if (this.searchQuery.trim().length < 2) {
                this.searchResults = [];
                return;
            }
            try {
                const response = await fetch(`/admin/users/search?q=${encodeURIComponent(this.searchQuery)}`);
                const data = await response.json();
                // Filter out already selected users
                this.searchResults = data.filter(u => !this.tags.find(t => t.id === u.id));
            } catch (error) {
                console.error("Erreur de recherche:", error);
            }
        },
        addTag(user) {
            if (!this.tags.find(t => t.id === user.id)) {
                this.tags.push(user);
            }
            this.searchQuery = '';
            this.searchResults = [];
        },
        removeTag(tag) {
            this.tags = this.tags.filter(t => t.id !== tag.id);
        }
    };
}
</script>
