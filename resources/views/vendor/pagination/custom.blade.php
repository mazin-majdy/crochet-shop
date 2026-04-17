@if ($paginator->hasPages())
<nav aria-label="pagination" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-top:24px">

  {{-- Info --}}
  <div style="font-size:0.82rem;color:#999;font-family:'Cairo',sans-serif">
    عرض
    <strong style="color:#422018">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</strong>
    من
    <strong style="color:#422018">{{ $paginator->total() }}</strong>
    نتيجة
  </div>

  {{-- Pages --}}
  <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">

    {{-- Prev --}}
    @if ($paginator->onFirstPage())
      <span style="width:36px;height:36px;border-radius:9px;border:1.5px solid #f0e8e0;background:#fafaf8;color:#ccc;display:inline-flex;align-items:center;justify-content:center;font-size:1rem;pointer-events:none;cursor:default" aria-disabled="true">
        ›
      </span>
    @else
      <a href="{{ $paginator->previousPageUrl() }}"
         style="width:36px;height:36px;border-radius:9px;border:1.5px solid #e8ddd4;background:#fff;color:#422018;display:inline-flex;align-items:center;justify-content:center;font-size:1rem;text-decoration:none;transition:all 0.2s"
         onmouseover="this.style.borderColor='#7b1113';this.style.color='#7b1113';this.style.background='rgba(123,17,19,0.04)'"
         onmouseout="this.style.borderColor='#e8ddd4';this.style.color='#422018';this.style.background='#fff'"
         rel="prev" aria-label="السابق">
        ›
      </a>
    @endif

    {{-- Page Numbers --}}
    @foreach ($elements as $element)

      {{-- "..." dots --}}
      @if (is_string($element))
        <span style="width:36px;height:36px;border-radius:9px;border:1.5px solid #f0e8e0;background:#fafaf8;color:#bbb;display:inline-flex;align-items:center;justify-content:center;font-size:0.78rem;pointer-events:none">
          …
        </span>
      @endif

      {{-- Page links --}}
      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <span style="width:36px;height:36px;border-radius:9px;background:#7b1113;color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:800;border:1.5px solid #7b1113" aria-current="page">
              {{ $page }}
            </span>
          @else
            <a href="{{ $url }}"
               style="width:36px;height:36px;border-radius:9px;border:1.5px solid #e8ddd4;background:#fff;color:#422018;display:inline-flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;text-decoration:none;transition:all 0.2s"
               onmouseover="this.style.borderColor='#7b1113';this.style.color='#7b1113';this.style.background='rgba(123,17,19,0.04)'"
               onmouseout="this.style.borderColor='#e8ddd4';this.style.color='#422018';this.style.background='#fff'"
               aria-label="صفحة {{ $page }}">
              {{ $page }}
            </a>
          @endif
        @endforeach
      @endif

    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
      <a href="{{ $paginator->nextPageUrl() }}"
         style="width:36px;height:36px;border-radius:9px;border:1.5px solid #e8ddd4;background:#fff;color:#422018;display:inline-flex;align-items:center;justify-content:center;font-size:1rem;text-decoration:none;transition:all 0.2s"
         onmouseover="this.style.borderColor='#7b1113';this.style.color='#7b1113';this.style.background='rgba(123,17,19,0.04)'"
         onmouseout="this.style.borderColor='#e8ddd4';this.style.color='#422018';this.style.background='#fff'"
         rel="next" aria-label="التالي">
        ‹
      </a>
    @else
      <span style="width:36px;height:36px;border-radius:9px;border:1.5px solid #f0e8e0;background:#fafaf8;color:#ccc;display:inline-flex;align-items:center;justify-content:center;font-size:1rem;pointer-events:none;cursor:default" aria-disabled="true">
        ‹
      </span>
    @endif

  </div>
</nav>
@endif
