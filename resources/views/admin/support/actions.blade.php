<div class="flex items-center space-x-2">

    <x-wire-button
        href="{{ route('admin.support.show', $support) }}"
        gray xs>
        <i class="fa-solid fa-eye"></i>
    </x-wire-button>

    <x-wire-button
        href="{{ route('admin.support.edit', $support) }}"
        blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

</div>
