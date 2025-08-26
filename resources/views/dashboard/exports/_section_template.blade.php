{{-- resources/views/dashboard/exports/_section_template.blade.php --}}

@php
    // إذا كانت العلاقة فارغة، ننشئ "مجموعة" وهمية فارغة لضمان عرض هيكل الجدول مرة واحدة على الأقل.
    // إذا كانت العلاقة تحتوي على بيانات، يتم تقسيمها إلى مجموعات، كل مجموعة تحتوي على 7 عناصر.
    $chunks = $relation->isNotEmpty() ? $relation->chunk(7) : collect([collect()]);
@endphp

@foreach ($chunks as $chunk)
    {{-- إذا كان هذا ليس القسم الأول من التقرير، أضف فاصلاً --}}
    @if (!$loop->first)
        <tr style="height: 20px;">
            <td colspan="9" style="border: none;"></td>
        </tr>
    @endif

    <!-- رأس القسم -->
    <tr>
        <th colspan="9"
            style="background-color: #e3f2fd; color: #0d47a1; font-weight: bold; text-align: center; font-size: 14px; border: 1px solid #cccccc; padding: 8px;">
            {{ $title }}
            {{-- إضافة ترقيم للصفحات إذا كان هناك أكثر من قسم لنفس النوع --}}
            @if ($chunks->count() > 1)
                (صفحة {{ $loop->iteration }}/{{ $chunks->count() }})
            @endif
        </th>
    </tr>

    <!-- رؤوس الأعمدة -->
    <tr>
        <th
            style="background-color: #424242; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #cccccc; padding: 8px;">
            #</th>
        <th
            style="background-color: #424242; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #cccccc; padding: 8px;">
            المواصفات</th>
        @for ($i = 1; $i <= 7; $i++)
            <th
                style="background-color: #424242; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #cccccc; padding: 8px;">
                {{ $itemName }} {{ $loop->index * 7 + $i }}
            </th>
        @endfor
    </tr>

    {{-- عرض صفوف المواصفات --}}
    @foreach ($specs as $spec)
        <tr>
            <td style="text-align: center; border: 1px solid #cccccc; padding: 8px;">{{ $loop->iteration }}</td>
            <td
                style="font-weight: bold; background-color: #f5f5f5; text-align: right; border: 1px solid #cccccc; padding: 8px;">
                {{ $spec['label'] }}</td>

            @for ($i = 0; $i < 7; $i++)
                @php
                    // ✅ الحل هنا: نستخدم values() لإعادة فهرسة المفاتيح لتبدأ من 0 لكل مجموعة
                    $item = $chunk->values()->get($i);
                @endphp
                <td style="text-align: center; border: 1px solid #cccccc; padding: 8px;">
                    @if ($item)
                        {{-- إذا كان العنصر موجوداً، اعرض قيمته --}}
                        @if (isset($spec['type']) && $spec['type'] === 'boolean')
                            {{ data_get($item, $spec['key']) ? 'نعم' : 'لا' }}
                        @else
                            {{ data_get($item, $spec['key']) ?? '-' }}
                        @endif
                    @else
                        {{-- إذا لم يكن العنصر موجوداً (لأن المجموعة أقل من 7)، اعرض خلية فارغة --}}
                        -
                    @endif
                </td>
            @endfor
        </tr>
    @endforeach
@endforeach
