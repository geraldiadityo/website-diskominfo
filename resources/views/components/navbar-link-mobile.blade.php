@props(['active' => false, 'href' => '#'])

<a href="{{ $href }}"
   class="block px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $active ? 'text-primary-700 bg-primary-50' : 'text-slate-600 hover:text-primary-700 hover:bg-slate-50' }}">
    {{ $slot }}
</a>
