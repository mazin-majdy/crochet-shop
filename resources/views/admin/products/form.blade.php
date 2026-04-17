@extends('layouts.admin')

@section('title', isset($product) ? 'تعديل المنتج' : 'إضافة منتج')
@section('page-title', isset($product) ? 'تعديل المنتج' : 'إضافة منتج جديد')

@section('content')
<div class="container-fluid px-4">
  <div class="row justify-content-center">
    <div class="col-lg-9">

      <div class="main-table mt-2">
        <h5 class="fw-bold mb-4 text-brown">
          {{ isset($product) ? '✏️ تعديل: '.$product->name : '✨ إضافة منتج جديد' }}
        </h5>

        <form action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}"
              method="POST" enctype="multipart/form-data">
          @csrf
          @if(isset($product)) @method('PUT') @endif

          <div class="row g-4">

            <div class="col-md-8">
              <label class="form-label">اسم المنتج *</label>
              <input type="text" name="name" class="form-control"
                     value="{{ old('name', $product->name ?? '') }}"
                     placeholder="مثال: شال صوف مطرز" required>
            </div>

            <div class="col-md-4">
              <label class="form-label">السعر (₪) *</label>
              <input type="number" name="price" class="form-control" step="0.5" min="0"
                     value="{{ old('price', $product->price ?? '') }}"
                     placeholder="120.00" required>
            </div>

            <div class="col-md-4">
              <label class="form-label">القسم *</label>
              <select name="category" class="form-select" required>
                <option value="">-- اختر القسم --</option>
                @foreach(['embroidery' => '🪡 التطريز', 'handicraft' => '✂️ أشغال يدوية', 'wool' => '🧶 أعمال الصوف'] as $val => $lbl)
                  <option value="{{ $val }}" {{ old('category', $product->category ?? '') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">الفئة المستهدفة</label>
              <select name="target" class="form-select">
                <option value="">-- اختر الفئة --</option>
                @foreach(['kids' => '👶 أطفال', 'girls' => '👗 بنات', 'women' => '👩 نساء', 'men' => '👔 رجال', 'general' => '🌟 عام'] as $val => $lbl)
                  <option value="{{ $val }}" {{ old('target', $product->target ?? '') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">الحالة</label>
              <select name="is_active" class="form-select">
                <option value="1" {{ old('is_active', $product->is_active ?? 1) == 1 ? 'selected' : '' }}>✅ نشط ومرئي</option>
                <option value="0" {{ old('is_active', $product->is_active ?? 1) == 0 ? 'selected' : '' }}>🔒 مخفي</option>
              </select>
            </div>

            <div class="col-12">
              <label class="form-label">وصف المنتج *</label>
              <textarea name="description" class="form-control" rows="4"
                        placeholder="اكتب وصفاً تفصيلياً للمنتج — المادة، الحجم، الاستخدام..."
                        required>{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            <div class="col-md-6">
              <label class="form-label">الكلمات الدلالية (Tags)</label>
              <input type="text" name="tags" class="form-control"
                     value="{{ old('tags', $product->tags ?? '') }}"
                     placeholder="تطريز, يدوي, فلسطيني (مفصولة بفاصلة)">
            </div>

            <div class="col-md-6">
              <div class="d-flex align-items-center gap-3 mt-4">
                <label class="d-flex align-items-center gap-2 cursor-pointer" style="font-size:0.9rem;font-weight:700;color:#422018">
                  <input type="checkbox" name="is_featured" value="1"
                         {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}
                         style="width:18px;height:18px;accent-color:#7b1113">
                  ⭐ منتج مميز (يظهر في الرئيسية)
                </label>
              </div>
            </div>

            <div class="col-12">
              <label class="form-label">صورة المنتج</label>
              <div id="dropZone" style="border:2px dashed #e8ddd4;border-radius:16px;padding:32px;text-align:center;cursor:pointer;transition:0.2s;background:var(--cream)"
                   onclick="document.getElementById('imageInput').click()"
                   >
                <div id="imgPreview" style="display:none;margin-bottom:12px">
                  <img id="previewImg" style="max-height:140px;border-radius:12px;max-width:100%" alt="">
                </div>
                @if(isset($product) && $product->image)
                  <img src="{{ asset('storage/'.$product->image) }}" style="max-height:120px;border-radius:12px;margin-bottom:12px" alt="">
                  <br>
                @else
                  <div style="font-size:2.5rem;margin-bottom:10px">🖼️</div>
                @endif
                <div style="font-weight:700;color:#6b5040;margin-bottom:4px">اضغط لاختيار صورة</div>
                <div style="font-size:0.8rem;color:#aaa">PNG, JPG, WEBP – حتى 2MB</div>
                <input type="file" id="imageInput" name="image" accept="image/*" class="d-none"
                       >
              </div>
            </div>

          </div>

          <div class="d-flex gap-3 justify-content-end mt-5 pt-3 border-top">
            <a href="{{ route('admin.products.index') }}" class="btn-action py-2 px-4 text-decoration-none">إلغاء</a>
            <button type="submit" class="btn-maroon">
              <i class="bi bi-check-lg"></i>
              {{ isset($product) ? 'حفظ التعديلات' : 'إضافة المنتج' }}
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
  if (input.files?.[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('previewImg').src = e.target.result;
      document.getElementById('imgPreview').style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endpush
