<li x-data="{ show: false }" x-on:click.prevent="show = !show" x-on:click.outside="if (!mobile) show = false" class="nav-item dropdown">
    <a :class="{'show': show}" class="nav-link dropdown-toggle" href="#" role="button">Αξεσουάρ</a>

    <ul :class="show && 'show'" class="dropdown-menu" x-init="$watch('show', show => updateHeights($el, show))">
        <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Σαλιάρες</a></li>
        <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Κουβέρτες</a></li>
        <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Πετσέτες - Μπουρνούζια</a></li>
        <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Μαγιό</a></li>
        <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Παπούτσια</a></li>
        <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Διάφορα</a></li>
    </ul>
</li>