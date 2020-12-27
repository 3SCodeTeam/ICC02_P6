<div class="menu-items-cont">
    <div class="item {{($selectedMenu == 'profile') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'profile')) ? 'href='.asset('/admin/profile').'':''}}>Perfil</a>
    </div>
    <div class="item {{($selectedMenu == 'teachers') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'teachers')) ? 'href='.asset('/admin/teachers').'':''}}>Profesores</a>
    </div>
    <div class="item {{($selectedMenu == 'courses') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'courses')) ? 'href='.asset('/admin/courses').'':''}}>Cursos</a>
    </div>
    <div class="item {{(in_array($selectedMenu,['classesSchedule','classes'])) ? 'selected':''}}">
        <a {{(!(in_array($selectedMenu,['classesSchedule','classes']))) ? 'href='.asset('/admin/classes').'':''}}>Asignaturas</a>
    </div>
    <div class="item {{($selectedMenu == 'delete') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'delete')) ? 'href='.asset('/admin/delete').'':''}}>Eliminar</a>
    </div>
    @if(in_array($selectedMenu,['studentsDetails','classesDetails','subjectDetails','subjectsOfStudent','record']))
        <div id="courseAdmin-menu" class="menu-items-cont submenu">
            <div class="item {{($selectedMenu == 'studentsDetails') ? 'selected':''}}">
                <a {{(!($selectedMenu == 'studentsDetails')) ? 'href='.asset('details/students/'.$course['id_course']).'':''}}>Estudiantes</a>
            </div>
            {{--<div class="item {{((in_array($selectedMenu,['classesDetails'])) || $course['classes']<1) ? 'selected':''}}">
                <a {{(!(in_array($selectedMenu,['classesDetails']))&&$course['classes']>0) ? 'href='.asset('/details/classes/'.$course['id_course']).'':''}}>Asignaturas</a>
            </div>--}}
            <div class="item {{((in_array($selectedMenu,['classesDetails']))) ? 'selected':''}}">
                <a {{(!(in_array($selectedMenu,['classesDetails']))) ? 'href='.asset('details/classes/'.$course['id_course']).'':''}}>Asignaturas</a>
            </div>
        </div>
    @endif
</div>

