<div class="teacher studentDetails container">
    <form action="{{asset('/teacher/students/')}}" method="post" name="marks" id="marks">
        <div class="teacher studentDetails exams form">
            <table class="teacher studentDetails table exams">
                <thead>
                    <tr class="row head">
                        <th class="col name"><p>Examen</p></th>
                        {{--<th class="col date"><label for="esubmitted">Presentado</label></th>--}}
                        <th class="col mark"><label for="emark">Nota</label></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exams as $e)
                    <tr class="row data">
                        <td class="col name"><p>{{$e->name}}</p></td>
                        {{--<td class="col date"><p><input type="date" name="esubmitted" id="esubmitted"/></p></td>--}}
                        <td class="col mark"><p><input type="number" min="0" max="10" step="0.5" name="emark" id="emark"/></p></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="teacher studentDetails works form">
            <table class="teacher studentDetails table works">
                <thead>
                <tr class="row head">
                    <th class="col name"><p>Work</p></th>
                    {{--<th class="col date"><label for="wsubmitted">Presentado</label></th>--}}
                    <th class="col mark"><label for="wmark">Nota</label></th>
                </tr>
                </thead>
                <tbody>
                @foreach($works as $w)
                    <tr class="row data">
                        <td class="col name"><p>{{$w->name}}</p></td>
                        {{--<td class="col date"><p><input type="date" name="wsubmitted" id="wsubmitted"/></p></td>--}}
                        <td class="col mark"><p><input type="number" min="0" max="10" step="0.5" name="wmark" id="wmark"/></p></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="techer studentDetails submit container">
            <input type="submit" name="submit" value="Enviar"/>
        </div>
    </form>
</div>
