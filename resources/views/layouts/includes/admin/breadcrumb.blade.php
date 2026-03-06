
{{--verifica si hay un elemento en el arreglo breadcrumb--}}
@if (count($breadcrumbs))
    {{--marigin bottom--}}
    <nav class="mb-2 block">
        <ol class="flex flex-wrap text-slate-700 text-sm">
            @foreach ($breadcrumbs as $item)
                {{--centra los li--}}
                <li class="flex items-center">
                    {{--si no es el primer elemento usa el separador "/" --}}
                    @unless($loop->first)
                        {{--px es padding en elñ eje x, decir horizontal--}}
                        <span class="px-2 text-gray-400">/</span>
                    @endunless

                    @isset($item['href'])
                        <a href="{{ $item['href'] }}" 
                        class="opacity-60 hover:opacity-100 transition">
                            {{ $item['name'] }}
                        </a>
                    @else
                        {{ $item['name'] }}
                    @endisset
                </li>
            @endforeach
        </ol>
        {{--para qe el último elemento salga resaltado--}}
        @if (count($breadcrumbs) > 1)
            <h6 class="font-bold mt-2">
                {{ end($breadcrumbs)['name'] }}
            </h6>
        @endif
    </nav>
@endif