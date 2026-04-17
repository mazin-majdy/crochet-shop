@if (session()->has('msg'))
    <div class=" mt-3 alert alert-{{ $type }}">
        {{ session('msg') }}
    </div>
@endif
