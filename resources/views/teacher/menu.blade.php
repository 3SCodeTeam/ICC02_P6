<div class="menu-items-cont">
    <div class="item {{($selectedMenu == 'profile') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'profile')) ? 'href='.asset('/teachers/profile').'':''}}>Perfil</a>
    </div>
    <div class="item {{($selectedMenu == 'classes') ? 'selected':''}}">
        <a {{(!($selectedMenu == 'classes')) ? 'href='.asset('/teachers/classes').'':''}}>Asignaturas</a>
    </div>
</div>
