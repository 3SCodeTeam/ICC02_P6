<div class="menu-items-cont">
    <div class="item {{($selectedMenu == 'studentsDetails') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'studentsDetails')) ? 'href='.asset('/details/students/'.$course['id_course']).'':''}}>Estudiantes</a>
    </div>
    <div class="item {{(in_array($selectedMenu,['classesDetails'])) ? 'selected':''}}">
        <a {{(!(in_array($selectedMenu,['classesDetails']))) ? 'href='.asset('/details/classes/'.$course['id_course']).'':''}}>Asignaturas</a>
    </div>
</div>
