<table class="monthly schedule"><tbody>
@foreach($schedule_data as $week)
    @if($loop->first)
        <tr class="row header">
        @foreach($week as $d)
            <th>{{$d}}</th>')
        @endforeach
        </tr>
    @else
        <tr class="row week">
            @foreach($week as $d)
                @if($d['col'] == "SEMANA")
                    <td class="col {{$d['col']}}"><span>{{$d['value']}}</span></td>
                @else
                    <td class="col {{$d['col']}}">
                        @foreach($d['value'] as $v)
                            <div class="subject" style="color: {{$v['class_color']}}"><a href="{{asset('/student/record/'.$v['id_calss'])}}">{{$v['class_name']}}</a></div>
                        @endforeach
                    </td>
                @endif
            @endforeach
        </tr>
    @endif
@endforeach
</tbody></table>





