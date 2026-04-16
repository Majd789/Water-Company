<div style="border: 3px double #000; padding: 10px; width: 100%; box-sizing: border-box;">
    <table style="width: 100%; border-collapse: collapse; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;" dir="rtl">
        <thead>
            <tr>
                <th colspan="6" style="text-align: center; font-size: 20px; font-weight: bold; padding: 10px; border-bottom: 2px solid #000; background-color: #f2f2f2;">
                    الشركة العامة لمياه الشرب و الصرف الصحي بإدلب
                </th>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center; font-size: 16px; font-weight: bold; padding: 8px; background-color: #e6e6e6;">
                    طـلـب إجــازة (Leave Application)
                </th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="6" style="height: 15px;"></td></tr>

            <tr>
                <td style="font-weight: bold; border: 1px solid #000; background-color: #fafafa; padding: 8px; width: 15%;">الاسم الثلاثي:</td>
                <td colspan="2" style="border: 1px solid #000; text-align: center; font-size: 15px;">{{ $leave->employee->full_name }}</td>
                <td style="font-weight: bold; border: 1px solid #000; background-color: #fafafa; padding: 8px; width: 15%;">الوحدة / القسم:</td>
                <td colspan="2" style="border: 1px solid #000; text-align: center; font-size: 15px;">{{ $leave->employee->unit->unit_name ?? 'إدلب' }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; border: 1px solid #000; background-color: #fafafa; padding: 8px;">تاريخ البدء:</td>
                <td colspan="2" style="border: 1px solid #000; text-align: center;">{{ $leave->start_date }}</td>
                <td style="font-weight: bold; border: 1px solid #000; background-color: #fafafa; padding: 8px;">تاريخ الانتهاء:</td>
                <td colspan="2" style="border: 1px solid #000; text-align: center;">{{ $leave->end_date }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold; border: 1px solid #000; background-color: #fafafa; padding: 8px;">نوع الإجازة:</td>
                <td colspan="5" style="border: 1px solid #000; color: #ff0000; font-weight: bold; text-align: center;">{{ $leave->leaveType->type_name ?? 'Annual' }}</td>
            </tr>

            <tr>
                <td colspan="3" style="border: 1px solid #000; padding: 12px; text-align: center;">
                    الإجازات المستنفذة: <strong style="color: #d9534f;">{{ $leave->employee->total_allowed_days - $leave->employee->remaining_days }}</strong> يوم
                </td>
                <td colspan="3" style="border: 1px solid #000; padding: 12px; text-align: center;">
                    الرصيد المتبقي للموظف: <strong style="color: #5cb85c;">{{ $leave->employee->remaining_days }}</strong> يوم
                </td>
            </tr>

            <tr><td colspan="6" style="height: 20px;"></td></tr>

            <tr>
                <td colspan="6" style="text-align: center; font-size: 12px; font-weight: bold; padding-bottom: 15px;">
                   التاريخ: <span style="font-weight: normal; border-bottom: 1px dotted #000;">&nbsp; {{ date('Y/m/d') }} &nbsp;</span>
                </td>
            </tr>

            <tr>
                <td colspan="2" style="font-weight: bold; text-align: center; font-size: 13px;"><u> مقدم الطلب</u></td>
                <td colspan="2" style="font-weight: bold; text-align: center; font-size: 13px;"><u> المدير المباشر</u></td>
                <td colspan="2" style="font-weight: bold; text-align: center; font-size: 13px;"><u> المدير الإداري</u></td>
            </tr>
            <tr>
                <td colspan="2" style="height: 80px; vertical-align: bottom; text-align: center; color: #888;">...........................</td>
                <td colspan="2" style="height: 80px; vertical-align: bottom; text-align: center; color: #888;">...........................</td>
                <td colspan="2" style="height: 80px; vertical-align: bottom; text-align: center; color: #888;">...........................</td>
            </tr>

            <tr><td colspan="6" style="height: 10px;"></td></tr>
        </tbody>
    </table>
</div>
