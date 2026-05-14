@php
    $m = $module ?? null;
@endphp

<div>
    <label class="block text-sm font-semibold text-slate-700">Nama modul</label>
    <input type="text" name="name" value="{{ old('name', $m?->name) }}" required
           class="ui-input mt-2">
    @error('name')<p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-semibold text-slate-700">Deskripsi</label>
    <textarea name="description" rows="3"
              class="ui-input mt-2">{{ old('description', $m?->description) }}</textarea>
    @error('description')<p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
</div>

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-semibold text-slate-700">Bobot (%)</label>
        <input type="number" step="0.01" min="0" max="100" name="weight" value="{{ old('weight', $m?->weight ?? 0) }}" required
               class="ui-input mt-2">
        @error('weight')<p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700">Urutan tampil</label>
        <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $m?->sort_order ?? 0) }}"
               class="ui-input mt-2">
        @error('sort_order')<p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
    </div>
</div>
