<table>
    <tr>
        <th>收款</th>
        <th>工作日期</th>
        <th>工作类型</th>
    </tr>
    
    @foreach ($rs as $value)
    <tr>
        <td>{{ $value['money'] }}</td>
        <td>{{ $value['date'] }}</td>
        <td>{{ $value['typenames'] }}</td>
    </tr>
    @endforeach

</table>