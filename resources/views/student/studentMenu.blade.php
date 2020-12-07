<div class="menu-items-cont">
    <div class="item {{($selectedMenu == 'profile') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'profile')) ? 'href='.asset('/student/profile').'':''}}>Perfil</a>
    </div>
    <div class="item {{($selectedMenu == 'enrollment') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'enrollment')) ? 'href='.asset('/student/enrollment').'':''}}>Matrícula</a>
    </div>
    <div class="item {{(in_array($selectedMenu,['mSchedule','wSchedule','dSchedule'])) ? 'selected':''}}">
        <a {{(!(in_array($selectedMenu,['mSchedule','wSchedule','dSchedule']))) ? 'href='.asset('/student/mSchedule').'':''}}>Horario</a>
    </div>
    <div class="item {{($selectedMenu == 'record') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'record')) ? 'href='.asset('/student/record').'':''}}>Expediente</a>
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
