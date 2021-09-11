<li x-data="{ show: false }" x-on:click.prevent="show = !show" x-on:click.outside="if (!mobile) show = false" class="nav-item dropdown">
    <a :class="{'show': show}" class="nav-link dropdown-toggle" href="#" role="button">Παιδικά ρούχα</a>

    <ul :class="show && 'show'" class="dropdown-menu" x-init="$watch('show', show => updateHeights($el, show))">
        <li x-data="{ show: false }" x-on:click.stop="show = !show" x-on:click.outside="if (!mobile) show = false" class="dropdown">
            <div :class="{'show': show}" class="dropdown-item dropdown-toggle">
                <a class="text-decoration-none text-dark" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Mini αγόρι 2-14 χρονών</a>
            </div>

            <ul :class="show && 'show'" class="dropdown-menu" x-init="$watch('show', show => updateHeights($el, show))">
                <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Μπλούζες - Κοντομάνικα</a></li>
                <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Σετ φόρμες</a></li>
                <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Πουκάμισα</a></li>
                <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Παντελόνια - Σορτς</a></li>
                <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Ρετ ρούχων</a></li>
                <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Πιτζάμες</a></li>
            </ul>
        </li>

        <li x-data="{ show: false }" x-on:click.stop="show = !show" x-on:click.outside="if (!mobile) show = false" class="dropdown">
            <div :class="{'show': show}" class="dropdown-item dropdown-toggle">
                <a class="text-decoration-none text-dark" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Mini κορίτσι 2-14 χρονών</a>
            </div>

            <ul :class="show && 'show'" class="dropdown-menu" x-init="$watch('show', show => updateHeights($el, show))">
                <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Φούστες - Φορέματα</a></li>
                <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Μπλούζες - Κοντομάνικα</a></li>
                <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Σετ φόρμες</a></li>
                <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Πουκάμισα</a></li>
                <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Παντελόνια - Σορτς</a></li>
                <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Ρετ ρούχων</a></li>
                <li><a class="dropdown-item" href="http://localhost:8000/el/kormakia/f/brefiko_agori">Πιτζάμες</a></li>
            </ul>
        </li>
    </ul>
</li>