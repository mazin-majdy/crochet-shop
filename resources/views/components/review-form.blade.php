{{--
  _____                _
 |  __ \              (_)
 | |__) |_____   _____ _  _____      __
 |  _  // _ \ \ / / _ \ |/ _ \ \ /\ / /
 | | \ \  __/\ V /  __/ |  __/\ V  V /
 |_|  \_\___| \_/ \___|_|\___| \_/\_/

 مكوّن نموذج التقييم — يُستدعى هكذا:
 @include('components.review-form', ['productId' => $product->id])
 أو بدون productId للتقييم العام من صفحة من نحن
--}}

@php
  $formId    = 'review-form-' . ($productId ?? 'general');
  $productId = $productId ?? null;
@endphp

<div class="review-form-wrap" id="{{ $formId }}-wrap">

  {{-- ── Success message ─────────────────────────────────────── --}}
  @if(session('review_success'))
  <div class="rv-success-banner">
    <div class="rv-success-icon">🌟</div>
    <div>
      <div class="rv-success-title">تم إرسال رأيك!</div>
      <div class="rv-success-sub">{{ session('review_success') }}</div>
    </div>
  </div>
  @else

  {{-- ── Form ────────────────────────────────────────────────── --}}
  <form action="{{ route('reviews.store') }}" method="POST"
        id="{{ $formId }}" class="review-form" novalidate>
    @csrf
    @if($productId)
      <input type="hidden" name="product_id" value="{{ $productId }}">
    @endif

    {{-- Validation errors --}}
    @if($errors->has('body') || $errors->has('reviewer_name') || $errors->has('rating'))
    <div class="rv-error-box">
      <i class="bi bi-exclamation-triangle-fill"></i>
      @foreach(['reviewer_name','rating','body'] as $field)
        @error($field)<div>{{ $message }}</div>@enderror
      @endforeach
    </div>
    @endif

    {{-- ── Star Rating ─────────────────────────────────────────── --}}
    <div class="rv-stars-section">
      <div class="rv-label">كيف تقيّم تجربتك؟ <span class="rv-required">*</span></div>
      <div class="rv-stars" id="star-picker-{{ $formId }}" role="group" aria-label="تقييم بالنجوم">
        @for($i = 5; $i >= 1; $i--)
        <input type="radio" name="rating" id="star-{{ $formId }}-{{ $i }}"
               value="{{ $i }}" class="rv-star-input"
               {{ old('rating') == $i ? 'checked' : '' }}
               required>
        <label for="star-{{ $formId }}-{{ $i }}"
               class="rv-star-label"
               title="{{ ['','مروّع','ضعيف','مقبول','جيد جداً','رائع!'][$i] }}">
          ★
        </label>
        @endfor
      </div>
      <div class="rv-stars-hint" id="hint-{{ $formId }}">اختر من 1 إلى 5 نجوم</div>
    </div>

    {{-- ── Fields ──────────────────────────────────────────────── --}}
    <div class="rv-fields">

      <div class="rv-row">
        <div class="rv-field">
          <label class="rv-label" for="name-{{ $formId }}">
            اسمك الكريم <span class="rv-required">*</span>
          </label>
          <input type="text" name="reviewer_name" id="name-{{ $formId }}"
                 class="rv-input {{ $errors->has('reviewer_name') ? 'rv-input-error' : '' }}"
                 value="{{ old('reviewer_name') }}"
                 placeholder="مثال: أم محمد" required maxlength="80">
        </div>
        <div class="rv-field">
          <label class="rv-label" for="city-{{ $formId }}">مدينتك</label>
          <input type="text" name="reviewer_city" id="city-{{ $formId }}"
                 class="rv-input"
                 value="{{ old('reviewer_city') }}"
                 placeholder="رام الله، غزة..." maxlength="60">
        </div>
      </div>

      <div class="rv-field">
        <label class="rv-label" for="title-{{ $formId }}">عنوان رأيك <span class="rv-opt">(اختياري)</span></label>
        <input type="text" name="title" id="title-{{ $formId }}"
               class="rv-input"
               value="{{ old('title') }}"
               placeholder="مثال: جودة رائعة وتوصيل سريع!"
               maxlength="120">
      </div>

      <div class="rv-field">
        <label class="rv-label" for="body-{{ $formId }}">
          رأيك وتجربتك <span class="rv-required">*</span>
        </label>
        <textarea name="body" id="body-{{ $formId }}"
                  class="rv-input rv-textarea {{ $errors->has('body') ? 'rv-input-error' : '' }}"
                  rows="4"
                  placeholder="أخبرينا بتجربتك... ما الذي أعجبك؟ ماذا تنصح الآخرين؟"
                  required minlength="10" maxlength="800">{{ old('body') }}</textarea>
        <div class="rv-char-count">
          <span id="char-{{ $formId }}">0</span> / 800 حرف
        </div>
      </div>

    </div>

    {{-- ── Privacy note ─────────────────────────────────────────── --}}
    <div class="rv-privacy">
      <i class="bi bi-shield-check" style="color:#1a8a4a"></i>
      رقم جوالك لن يُعرض. رأيك قد يخضع لمراجعة بسيطة قبل نشره.
    </div>

    {{-- ── Submit ───────────────────────────────────────────────── --}}
    <button type="submit" class="rv-submit" id="submit-{{ $formId }}">
      <span class="rv-submit-text">
        <i class="bi bi-send-fill"></i> إرسال التقييم
      </span>
      <span class="rv-submit-loading" style="display:none">
        <span class="rv-spinner"></span> جارٍ الإرسال...
      </span>
    </button>

  </form>
  @endif

</div>

<style>
/* ── Review Form Styles ──────────────────────────────────────────── */
.review-form-wrap { }

.rv-success-banner {
  display: flex; align-items: center; gap: 16px;
  background: linear-gradient(135deg, #e8faf0, #d4f5e2);
  border: 1.5px solid rgba(26,138,74,0.2);
  border-radius: 18px; padding: 22px 24px;
  animation: rv-pop 0.5s cubic-bezier(0.34,1.56,0.64,1) both;
}
@keyframes rv-pop { from { opacity:0; transform:scale(0.9); } }
.rv-success-icon { font-size: 2.5rem; flex-shrink: 0; }
.rv-success-title { font-size: 1rem; font-weight: 800; color: #1a8a4a; margin-bottom: 3px; }
.rv-success-sub   { font-size: 0.85rem; color: #2d7a4a; }

.rv-error-box {
  background: #fdf0f0; border: 1.5px solid rgba(123,17,19,0.15);
  border-radius: 12px; padding: 13px 16px;
  font-size: 0.87rem; color: #7b1113; font-weight: 600;
  margin-bottom: 20px; display: flex; flex-direction: column; gap: 3px;
}
.rv-error-box i { margin-bottom: 4px; font-size: 1rem; }

/* Stars */
.rv-stars-section { margin-bottom: 24px; }
.rv-label  { font-size: 0.87rem; font-weight: 800; color: #422018; margin-bottom: 10px; display: block; }
.rv-required { color: #7b1113; }
.rv-opt      { color: #bbb; font-weight: 500; }

.rv-stars {
  display: flex; flex-direction: row-reverse;
  gap: 4px; margin-bottom: 8px;
}
.rv-star-input { display: none; }
.rv-star-label {
  font-size: 2.2rem; cursor: pointer; color: #e0d0c0;
  transition: color 0.15s, transform 0.15s;
  line-height: 1;
}
.rv-star-label:hover,
.rv-star-label:hover ~ .rv-star-label,
.rv-star-input:checked ~ .rv-star-label {
  color: #f1c40f;
  transform: scale(1.08);
}
.rv-stars:hover .rv-star-label { color: #e0d0c0; }
.rv-stars:hover .rv-star-label:hover,
.rv-stars:hover .rv-star-label:hover ~ .rv-star-label { color: #f1c40f; }

.rv-stars-hint {
  font-size: 0.78rem; color: #aaa; font-weight: 600;
  transition: color 0.2s;
  min-height: 18px;
}

/* Fields */
.rv-fields { display: flex; flex-direction: column; gap: 16px; margin-bottom: 16px; }
.rv-row    { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

.rv-field { display: flex; flex-direction: column; gap: 6px; }

.rv-input {
  width: 100%; padding: 11px 14px;
  border: 1.5px solid #e8ddd4; border-radius: 12px;
  font-family: 'Cairo', sans-serif; font-size: 0.9rem;
  color: #2d1e14; background: #fff; transition: 0.2s;
  outline: none;
}
.rv-input:focus { border-color: #7b1113; box-shadow: 0 0 0 3px rgba(123,17,19,0.08); }
.rv-input-error { border-color: #7b1113; background: #fdf9f9; }
.rv-textarea    { resize: vertical; min-height: 110px; }

.rv-char-count {
  font-size: 0.75rem; color: #bbb; text-align: left; margin-top: 4px;
}

/* Privacy */
.rv-privacy {
  font-size: 0.78rem; color: #aaa; margin-bottom: 18px;
  display: flex; align-items: center; gap: 6px; font-weight: 600;
}

/* Submit */
.rv-submit {
  width: 100%; background: #7b1113; color: #fff;
  border: none; border-radius: 14px; padding: 14px 24px;
  font-family: 'Cairo', sans-serif; font-size: 0.95rem; font-weight: 800;
  cursor: pointer; transition: all 0.25s;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  position: relative; overflow: hidden;
}
.rv-submit::before {
  content: ''; position: absolute; inset: 0;
  background: linear-gradient(135deg, transparent 30%, rgba(255,255,255,0.1));
  opacity: 0; transition: opacity 0.3s;
}
.rv-submit:hover { background: #5a0d0e; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(123,17,19,0.4); }
.rv-submit:hover::before { opacity: 1; }
.rv-submit:active { transform: translateY(0) scale(0.98); }
.rv-submit:disabled { opacity: 0.65; cursor: not-allowed; transform: none; }

/* Spinner */
.rv-spinner {
  display: inline-block; width: 16px; height: 16px;
  border: 2px solid rgba(255,255,255,0.35);
  border-top-color: #fff; border-radius: 50%;
  animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

@media (max-width: 540px) {
  .rv-row { grid-template-columns: 1fr; }
  .rv-star-label { font-size: 2rem; }
}
</style>

<script>
(function() {
  const formId = '{{ $formId }}';
  const form   = document.getElementById(formId);
  if (!form) return;

  // ── Star hints ───────────────────────────────────────────────
  const hints = { 1:'مروّع 😞',2:'ضعيف 😐',3:'مقبول 🙂',4:'جيد جداً 😊',5:'رائع جداً! 🌟' };
  const hintEl = document.getElementById('hint-' + formId);

  form.querySelectorAll('.rv-star-input').forEach(input => {
    input.addEventListener('change', () => {
      if (hintEl) {
        hintEl.textContent = hints[input.value] || '';
        hintEl.style.color = input.value >= 4 ? '#1a8a4a' : input.value == 3 ? '#9a7d0a' : '#7b1113';
      }
    });
  });

  // ── Char counter ──────────────────────────────────────────────
  const textarea = document.getElementById('body-' + formId);
  const charEl   = document.getElementById('char-' + formId);
  if (textarea && charEl) {
    const update = () => {
      charEl.textContent = textarea.value.length;
      charEl.style.color = textarea.value.length > 720 ? '#d35400' : '#bbb';
    };
    textarea.addEventListener('input', update);
    update();
  }

  // ── Loading state on submit ───────────────────────────────────
  form.addEventListener('submit', function() {
    const btn     = document.getElementById('submit-' + formId);
    const textEl  = btn?.querySelector('.rv-submit-text');
    const loadEl  = btn?.querySelector('.rv-submit-loading');
    if (btn && textEl && loadEl) {
      textEl.style.display = 'none';
      loadEl.style.display = 'flex';
      btn.disabled = true;
    }
  });

})();
</script>
