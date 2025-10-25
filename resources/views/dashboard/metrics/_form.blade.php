{{-- resources/views/dashboard/metrics/_form.blade.php --}}
<div class="card-body">
    <div class="row">
        {{-- حقول مخفية للنماذج متعددة الأشكال (في حالة الإنشاء فقط) --}}
        @if(isset($metricable_type) && isset($metricable_id))
            <input type="hidden" name="metricable_type" value="{{ $metricable_type }}">
            <input type="hidden" name="metricable_id" value="{{ $metricable_id }}">
        @endif

        <div class="col-md-12">
            <div class="form-group">
                <label for="metric_key">نوع المقياس (Key)<span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-key"></i></span></div>
                    <input type="text" name="metric_key" id="metric_key" class="form-control"
                           value="{{ old('metric_key', $metric->metric_key ?? '') }}" placeholder="مثال: readiness_percentage, capacity" required>
                </div>
                <small class="form-text text-muted">استخدم اسماً إنجليزياً فريداً لسهولة البرمجة (e.g., panel_count).</small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="value">القيمة الرقمية<span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calculator"></i></span></div>
                    <input type="number" step="0.01" name="value" id="value" class="form-control"
                           value="{{ old('value', $metric->value ?? '') }}" placeholder="أدخل القيمة الرقمية" required>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
            <label for="unit">الوحدة</label>
            <select name="unit" id="unit" class="form-control select2">
                <option value="">-- لا يوجد --</option>
                <option value="%" {{ old('unit', $metric->unit ?? '') == '%' ? 'selected' : '' }}>% (نسبة مئوية)</option>
                <option value="m3" {{ old('unit', $metric->unit ?? '') == 'm3' ? 'selected' : '' }}>m³ (متر مكعب)</option>
                <option value="KVA" {{ old('unit', $metric->unit ?? '') == 'KVA' ? 'selected' : '' }}>KVA (كيلو فولت أمبير)</option>
                <option value="kWp" {{ old('unit', $metric->unit ?? '') == 'kWp' ? 'selected' : '' }}>kWp (كيلو واط ذروة)</option>
                <option value="panel" {{ old('unit', $metric->unit ?? '') == 'panel' ? 'selected' : '' }}>لوح</option>
                <option value="لتر" {{ old('unit', $metric->unit ?? '') == 'لتر' ? 'selected' : '' }}>لتر</option>
            </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="measured_at">تاريخ القياس</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar-alt"></i></span></div>
                    <input type="date" name="measured_at" id="measured_at" class="form-control"
                        value="{{ old('measured_at', isset($metric->measured_at) ? $metric->measured_at->format('Y-m-d') : date('Y-m-d')) }}">
                </div>
            </div>
        </div>
    </div>
</div>
