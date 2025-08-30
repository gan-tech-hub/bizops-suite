<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            ダッシュボード
        </h2>
    </x-slot>

    <div class="py-12 px-6">
        <div class="bg-white p-6 rounded shadow">
            <p>ようこそ、{{ Auth::user()->name }}さん！</p>
            @if(Auth::user()->position)
                <p>役職: {{ Auth::user()->position }}</p>
            @endif
            <p>※役職がある場合は表示します。</p>
        </div>
    </div>
</x-app-layout>
