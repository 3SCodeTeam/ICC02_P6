<div class="student-enrollment main-container">
    <form class="student-enrollment form" id="student-enrollment" method="POST" action="{{asset('/student/enrollmentPost')}}">
        @csrf
        <table class="student-enrollment course-table"><tbody>
            <tr class="row courses-title">
                <th>Nombre</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Descripción</th>
            </tr>
            @foreach($courses_data as $c)
                @if(in_array($c->id_course, $studentCourses['active']))
                    <tr class="row courses-data enrolled active">
                    @else
                        @if(in_array($c->id_course, $studentCourses['inactive']))
                            <tr class="row courses-data enrolled inactive">
                        @else
                            <tr class="row courses-data">
                        @endif
                @endif
                    <td class="col-course-name"><input type="radio" id="{{$c->name}}" name="courses" value="{{$c->id_course}}"/><span>{{$c->name}}</span></td>
                    <td class ="col course-start"><span>{{$c->date_start}}</span></td>
                    <td class="col course-end"><span>{{$c->date_end}}</span></td>
                    <td class="col course-description"><span>{{$c->description}}</span></td>
                </tr>
            @endforeach
            </tbody></table>
        <input type="submit" value = "Matricularse"/>
    </form>
</div>
