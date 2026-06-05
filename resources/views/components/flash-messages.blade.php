@if (session('success'))
    <div class="mb-4 rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-800 font-medium">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-800 font-medium">
        {{ session('error') }}
    </div>
@endif
