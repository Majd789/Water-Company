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
                <td colspan="2" style="border: 1px solid #000; color: #ff0000; font-weight: bold; text-align: center;">{{ $leave->leaveType->type_name ?? 'Annual' }}</td>
                <td style="font-weight: bold; border: 1px solid #000; background-color: #fafafa; padding: 8px;">الموظف البديل:</td>
                <td colspan="2" style="border: 1px solid #000; color: #808080; text-align: center;">................................</td>
            </tr>



           <tr>
                <td colspan="3" style="border: 1px solid #000; padding: 12px; text-align: center;">
                    الإجازات المستنفذة: <strong style="color: #d9534f;">{{ $leave->employee->total_allowed_days - $leave->employee->remaining_days }}</strong> يوم
                </td>
                <td colspan="3" style="border: 1px solid #000; padding: 12px; text-align: center;">
                    الرصيد المتبقي للموظف: <strong style="color: #5cb85c;">{{ $leave->employee->remaining_days }}</strong> يوم
                </td>
            </tr>

            <tr><td colspan="6" style="height: 40px;"></td></tr>

            <tr>
                <td colspan="6" style="text-align: center;">
                    <table style="width: 50%; margin: 0 auto; border-collapse: collapse;">
                         <tr>
                <td colspan="6" style="padding-top: 10px;text-align: center; font-size: 12px; font-weight: bold;">
                   <span style="font-weight: normal;text_ border-bottom: 1px dotted #000;">&nbsp; {{ date('Y/m/d') }} &nbsp;</span>
                </td>
            </tr>



            <tr>
                <td colspan="3" style="font-weight: bold; text-align: center; font-size: 14px;"><u>توقيع مقدم الطلب</u></td>
                <td colspan="3" style="font-weight: bold; text-align: center; font-size: 14px;"><u>توقيع المدير المباشر</u></td>
            </tr>
            <tr>
                <td colspan="3" style="height: 70px; vertical-align: bottom; text-align: center; color: #888;"></td>
                <td colspan="3" style="height: 70px; vertical-align: bottom; text-align: center; color: #888;"></td>
            </tr>


              <tr>
                            <td style="font-weight: bold;  font-size: 15px; "><u>توقيع المدير الإداري</u></td>
                        </tr>


                    </table>
                </td>
            </tr>
            <tr><td colspan="6" style="height: 10px;"></td></tr>
        </tbody>
    </table>
</div>
