<table>
    <thead>
        <tr>
            <th colspan="6" style="text-align: center; font-size: 18px; font-weight: bold; height: 35px; vertical-align: middle; border: 2px solid #000000; background-color: #f2f2f2;">
                الشركة العامة لمياه الشرب و الصرف الصحي بإدلب
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center; font-size: 16px; font-weight: bold; height: 30px; vertical-align: middle; border: 2px solid #000000; background-color: #e6e6e6;">
                طـلـب إجــازة (Leave Application)
            </th>
        </tr>
    </thead>
    <tbody>
        <tr><td colspan="6" style="height: 10px;"></td></tr>

        <tr>
            <td style="font-weight: bold; border: 1px solid #000000; background-color: #fafafa;">الاسم الثلاثي:</td>
            <td colspan="2" style="border: 1px solid #000000;text-align: center;">{{ $leave->employee->full_name }}</td>
            <td style="font-weight: bold; border: 1px solid #000000; background-color: #fafafa;">الوحدة / القسم:</td>
            <td colspan="2" style="border: 1px solid #000000;text-align: center;">{{ $leave->employee->unit->unit_name ?? 'إدلب' }}</td>
        </tr>

        <tr>
            <td style="font-weight: bold; border: 1px solid #000000; background-color: #fafafa;">تاريخ البدء:</td>
            <td colspan="2" style="border: 1px solid #000000; text-align: center;">{{ $leave->start_date }}</td>
            <td style="font-weight: bold; border: 1px solid #000000; background-color: #fafafa;">تاريخ الانتهاء:</td>
            <td colspan="2" style="border: 1px solid #000000; text-align: center;">{{ $leave->end_date }}</td>
        </tr>

        <tr>
            <td style="font-weight: bold; border: 1px solid #000000; background-color: #fafafa;">نوع الإجازة:</td>
            <td colspan="2" style="border: 1px solid #000000; color: #ff0000; font-weight: bold;text-align: center;">{{ $leave->leaveType->type_name ?? 'Annual' }}</td>
            <td style="font-weight: bold; border: 1px solid #000000; background-color: #fafafa;">الموظف البديل:</td>
            <td colspan="2" style="border: 1px solid #000000; color: #808080;text-align: center;">................................</td>
        </tr>

        <tr>
            <td style="font-weight: bold; border: 1px solid #000000; background-color: #fafafa;">رقم التواصل:</td>
            <td colspan="5" style="border: 1px solid #000000;text-align: center;">................................................................................</td>
        </tr>

        <tr><td colspan="6" style="height: 20px;"></td></tr>
        <tr>
        <td colspan="3" style="font-weight: bold; text-decoration: underline;">توقيع مقدم الطلب</td>
            <td colspan="3"  style="font-weight: bold; text-decoration: underline;">توقيع المدير المباشر</td>
        </tr>
        <tr>
            <td style="height: 40px;">التاريخ:</td>
            <td colspan="2" style="vertical-align: bottom;">{{ date('Y/m/d') }}</td>
            <td>التاريخ:</td>
            <td colspan="2" style="vertical-align: bottom;">.... / .... / 2026</td>
        </tr>

        <tr><td colspan="6" style="height: 15px;"></td></tr>
        <tr>
            <td colspan="6" style="border-top: 2px double #000000; font-weight: bold; background-color: #f9f9f9; text-align: center;">
                خـاص بقسم الموارد البشرية / مراقب الدوام
            </td>
        </tr>
        <tr>
            <td colspan="3" style="border: 1px solid #000000; padding: 5px;">
                الإجازات المستنفذة: <strong style="color: #d9534f;">{{ $leave->employee->total_allowed_days - $leave->employee->remaining_days }}</strong> يوم
            </td>
            <td colspan="3" style="border: 1px solid #000000; padding: 5px;">
                الرصيد المتبقي للموظف: <strong style="color: #5cb85c;">{{ $leave->employee->remaining_days }}</strong> يوم
            </td>
        </tr>

        <tr><td colspan="6" style="height: 20px;"></td></tr>
        <tr>
            <td colspan="3" style="border: 1px solid #000000; height: 60px; text-align: center; vertical-align: top; font-weight: bold;">
                اعتماد مدير الدائرة
                <br><br>
                ...........................
            </td>
            <td colspan="3" style="border: 1px solid #000000; height: 60px; text-align: center; vertical-align: top; font-weight: bold;">
                موافقة المدير العام
                <br><br>
                ...........................
            </td>
        </tr>
    </tbody>
</table>
