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
    <div class="item {{($selectedMenu == 'classes') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'classes')) ? 'href='.asset('/admin/classes').'':''}}>Asignaturas</a>
    </div>
    <div class="item {{($selectedMenu == 'delete') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'delete')) ? 'href='.asset('/admin/delete').'':''}}>Eliminar</a>
    </div>
</div>

@if(in_array($selectedMenu,['mSchedule','wSchedule','dSchedule']))
    <div id="schedule-menu" class="menu-items-cont submenu">
        <div class="item {{($selectedMenu == 'dSchedule') ? 'selected':''}}">
            <a {{(!($selectedMenu == 'dSchedule')) ? 'href='.asset('/student/dSchedule').'':''}}>Diario</a>
        </div>
        <div class="item {{($selectedMenu == 'wSchedule') ? 'selected':''}}">
            <a {{(!($selectedMenu == 'wSchedule')) ? 'href='.asset('/student/wSchedule').'':''}}>Semanal</a>
        </div>
        <div class="item {{($selectedMenu == 'mSchedule') ? 'selected':''}}">
            <a {{(!($selectedMenu == 'mSchedule')) ? 'href='.asset('/student/mSchedule').'':''}}>Mensual</a>
        </div>
        <div class="item backward">
            <a href="{{asset('/student/'.$selectedMenu.'/backward')}}"><</a>
        </div>
        <div class="item today">
            <a href="{{asset('/student/'.$selectedMenu.'/today')}}">Hoy</a>
        </div>
        <div class="item forward">
            <a href="{{asset('/student/'.$selectedMenu.'/forward')}}">></a>
        </div>
    </div>
@endif
