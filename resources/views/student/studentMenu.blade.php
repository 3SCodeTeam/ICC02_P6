<div class="menu-items-cont">
    <div class="item {{($selectedMenu == 'profile') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'profile')) ? 'href='.asset('/student/profile').'':''}}>Perfil</a>
    </div>
    <div class="item {{($selectedMenu == 'enrollment') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'enrollment')) ? 'href='.asset('/student/enrollment').'':''}}>Matrícula</a>
    </div>
    <div class="item {{($selectedMenu == 'schedule') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'schedule')) ? 'href='.asset('/student/schedule').'':''}}>Horario</a>
    </div>
    <div class="item {{($selectedMenu == 'record') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'record')) ? 'href='.asset('/student/record').'':''}}>Expediente</a>
    </div>
</div>
