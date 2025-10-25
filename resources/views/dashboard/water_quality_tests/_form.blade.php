{{-- resources/views/dashboard/water_quality_tests/_form.blade.php --}}
<div class="card-body">
    <h5 class="mt-2 mb-3" style="border-bottom: 1px solid #ddd; padding-bottom: 10px;">
        <i class="fas fa-vial text-primary ml-2"></i>
        بيانات فحص جودة المياه
    </h5>
    <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label for="station_id">المحطة<span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-industry"></i></span></div>
                    <select name="station_id" id="station_id" class="form-control select2" required>
                        <option value="" disabled selected>-- اختر المحطة --</option>
                        @foreach ($stations as $station)
                            <option value="{{ $station->id }}" {{ (old('station_id', $waterQualityTest->station_id ?? '')) == $station->id ? 'selected' : '' }}>
                                {{ $station->station_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="test_date">تاريخ الفحص<span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar-alt"></i></span></div>
                    <input type="date" name="test_date" id="test_date" class="form-control"
                           value="{{ old('test_date', isset($waterQualityTest->test_date) ? $waterQualityTest->test_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="turbidity">العكارة (NTU)</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-water"></i></span></div>
                    <input type="text" name="turbidity" id="turbidity" class="form-control"
                           value="{{ old('turbidity', $waterQualityTest->turbidity ?? '') }}" placeholder="e.g., 0.3">
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="ph_level">الرقم الهيدروجيني (pH)</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-flask"></i></span></div>
                    <input type="text" name="ph_level" id="ph_level" class="form-control"
                           value="{{ old('ph_level', $waterQualityTest->ph_level ?? '') }}" placeholder="e.g., 7.4">
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="microbial_analysis">التحليل الجرثومي</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-bacterium"></i></span></div>
                    <input type="text" name="microbial_analysis" id="microbial_analysis" class="form-control"
                           value="{{ old('microbial_analysis', $waterQualityTest->microbial_analysis ?? '') }}" placeholder="e.g., 0">
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="complaints">شكاوى المستفيدين (إن وجدت)</label>
                <textarea name="complaints" id="complaints" class="form-control" rows="3"
                          placeholder="صف أي شكاوى مسجلة بخصوص طعم أو رائحة أو لون الماء">{{ old('complaints', $waterQualityTest->complaints ?? '') }}</textarea>
            </div>
        </div>

    </div>
</div>
