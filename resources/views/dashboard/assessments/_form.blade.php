{{-- resources/views/dashboard/assessments/_form.blade.php --}}

<div class="card-body">
    <div class="row">
        @if(isset($assessmentable_type) && isset($assessmentable_id))
            <input type="hidden" name="assessmentable_type" value="{{ $assessmentable_type }}">
            <input type="hidden" name="assessmentable_id" value="{{ $assessmentable_id }}">
        @endif

        <div class="col-md-12">
            <div class="form-group">
                <label for="assessment_key">مفتاح التقييم (Key)<span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-key"></i></span></div>
                    <input type="text" name="assessment_key" id="assessment_key" class="form-control"
                           value="{{ old('assessment_key', $assessment->assessment_key ?? '') }}" placeholder="مثال: technical_condition, team_skills" required>
                </div>
                <small class="form-text text-muted">استخدم اسماً إنجليزياً فريداً (مثل `building_condition`) لسهولة التعامل معه برمجياً.</small>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="value">قيمة التقييم<span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-star-half-alt"></i></span></div>
                    <input type="text" name="value" id="value" class="form-control"
                           value="{{ old('value', $assessment->value ?? '') }}" placeholder="مثال: جيد جداً, كافٍ, لا يوجد" required>
                </div>
                 <small class="form-text text-muted">هذه هي القيمة النصية للتقييم التي ستظهر للمستخدم.</small>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="notes">ملاحظات</label>
                <textarea name="notes" id="notes" class="form-control" rows="3"
                          placeholder="أدخل أي ملاحظات إضافية هنا">{{ old('notes', $assessment->notes ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>
