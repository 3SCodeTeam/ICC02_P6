<div class="schedule main container">
    @if($selectedMenu == 'mSchedule')
        <table class="monthly schedule"><tbody>
        @foreach($schedule_data as $week)
            @if($loop->first)
                <tr class="row header">
                @foreach($week as $d)
                    <th>{{$d}}</th>
                @endforeach
                </tr>
            @else
                <tr class="row week">
                    @foreach($week as $d)
                        @if($d['col'] == "SEMANA")
                            <td class="col {{$d['col']}}"><span>{{$d['value']}}</span></td>
                        @else
                            <td class="col classes {{$d['col']}}">
                                @foreach($d['value'] as $v)
                                    <div class="subject"><a style="color: {{$v['color']}}" href="{{asset('/student/record/'.$v['id'])}}">{{$v['name']}}</a></div>
                                @endforeach
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endif
        @endforeach
        </tbody></table>
    @endif
    @if($selectedMenu == 'wSchedule')
        <table class="weekly schedule"><tbody>
            @foreach($schedule_data as $row)
                @if($loop->first)
                    <tr class="row header">
                        @foreach($row as $d)
                            <th class="col {{$d}}">{{$d}}</th>
                        @endforeach
                    </tr>
                @else
                    <tr class="row week hour">
                        @foreach($row as $d)
                            @if($d['col']=='HORA')
                                <td class="col {{$d['col']}}"><span>{{$d['value']}}</span></td>
                            @else
                                <td class="col classes {{$d['col']}}">
                                    @foreach($d['value'] as $v)
                                        <span><a style="color:{{$v['color']}}" href="{{asset('student/record/'.$v['id'])}}">{{$v['name']}}</a></span>
                                    @endforeach
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endif
            @endforeach
        </tbody></table>
    @endif
    @if($selectedMenu == 'dSchedule')
            <table class="daily schedule"><tbody>
                @foreach($schedule_data as $row)
                    @if($loop->first)
                        <tr class="row header">
                            @foreach($row as $d)
                                @if($d == 'HORA')
                                    <th class="col {{$d}}">{{$d}}</th>
                                @else
                                    <th class="col date">{{$d}}</th>
                                @endif
                            @endforeach
                        </tr>
                    @else
                        <tr class="row week hour">
                            @foreach($row as $d)
                                @if($d['col']=='HORA')
                                    <td class="col {{$d['col']}}"><span>{{$d['value']}}</span></td>
                                @else
                                    <td class="col date">
                                        @foreach($d['value'] as $v)
                                            <span><a style="color:{{$v['color']}}" href="{{asset('student/record/'.$v['id'])}}">{{$v['name']}}</a></span>
                                        @endforeach
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            </tbody></table>
    @endif
</div>





