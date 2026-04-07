@props(['active' => false, 'href' => '#'])

<a href="{{ $href }}"
   class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ $active ? 'text-primary-700 bg-primary-50' : 'text-slate-600 hover:text-primary-700 hover:bg-slate-50' }}">
    {{ $slot }}
</a>
