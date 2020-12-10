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
</div>

