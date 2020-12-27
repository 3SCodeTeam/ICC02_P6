<div class="menu-items-cont">
    <div class="item {{($selectedMenu == 'profile') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'profile')) ? 'href='.asset('/teachers/profile').'':''}}>Perfil</a>
    </div>
    <div class="item {{($selectedMenu == 'classes') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'classes')) ? 'href='.asset('/teachers/classes').'':''}}>Asignaturas</a>
    </div>
</div>

@if(!in_array($selectedMenu,['profile','classes']))
    <div id="schedule-menu" class="menu-items-cont submenu">
        <div class="item {{($selectedMenu == 'students') ? 'selected':''}}">
            <a {{(!($selectedMenu == 'students')) ? 'href='.asset('/teachers/students/'.$id_class).'':''}}>Estudiantes</a>
        </div>
        <div class="item {{($selectedMenu == 'subjects') ? 'selected':''}}">
            <a {{(!($selectedMenu == 'subjects')) ? 'href='.asset('/subjects/subjects/'.$id_class).'':''}}>Trabajos y exámenes</a>
        </div>
    </div>
@endif
