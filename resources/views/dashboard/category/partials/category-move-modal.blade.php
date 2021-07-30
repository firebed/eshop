<form x-data="{
        tree: [{id: 0, name: 'Home', children: []}],
        ids: [],
        expanded: [],
        expandingNode: null,
        targetNode: null,
        targetId: '',
        submitting: false,

        select(node, id) {
            this.targetNode?.classList.remove('active')

            this.targetId = id
            this.targetNode = node.querySelector('.node')
            this.targetNode.classList.add('active')
        },

        addNode(parent, id, name) {
            const li = document.getElementById('node').content.firstElementChild.cloneNode(true)
            li.querySelector('.node').innerText = name
            li.querySelector('.fa-chevron-right').addEventListener('click', () => this.expand(li, id))
            li.querySelector('.fa-chevron-down').addEventListener('click', () => this.collapse(li, id))
            li.querySelector('.node').addEventListener('click', () => this.select(li, id))
            parent.appendChild(li)

            return li
        },

        expand(node, id) {
            node.classList.add('loading')

            fetch('/dashboard/categories/expand/' + id)
            .then(response => response.json())
            .then(response => {
                node.classList.remove('loading')
                node.classList.add('expanded')
                this.expanded.push(id)

                const ul = document.createElement('ul')
                ul.classList.add('list-unstyled', 'ps-3')

                for(const [id, name] of Object.entries(response)) {
                    this.addNode(ul, id, name)
                }

                node.appendChild(ul)
            })
        },

        collapse(node, id) {
            node.classList.remove('expanded')
            this.expanded.splice(this.expanded.indexOf(id))
            node.querySelector('ul').remove()
        }
      }"
      x-on:submit="submitting = true"
      action="{{ route('categories.move') }}"
      method="post"
>
    @csrf

    <div class="modal fade" id="category-move-modal" tabindex="-1"
         x-init="
            $el.addEventListener('show.bs.modal', e => {
                ids = [...document.querySelectorAll('.category:checked')].map(i => i.value)
                if (ids.length === 0) {
                    $dispatch('show-toast', {type:'warning', body: '{{ __('eshop::category.select_rows') }}'})
                    e.preventDefault()
                }

                $refs.tree.innerHTML = ''
                const li = addNode($refs.tree, '', '{{ __('eshop::category.home') }}')
                expand(li, '')
            })
        "
    >
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('eshop::category.move') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="target_id" x-model="targetId">

                    <template x-for="id in ids" :key="id">
                        <input type="hidden" name="source_ids[]" x-model="id">
                    </template>

                    <template id="node">
                        <li>
                            <div class="d-flex align-items-baseline gap-1">
                                <em class="fas fa-spinner fa-spin w-1r text-secondary"></em>
                                <em class="fas fa-chevron-down w-1r"></em>
                                <em class="fas fa-chevron-right w-1r"></em>
                                <div class="node w-100 p-1"></div>
                            </div>
                        </li>
                    </template>

                    <ul x-ref="tree" class="list-unstyled tree"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('eshop::buttons.cancel') }}</button>

                    <button x-bind:disabled="submitting" type="submit" class="btn btn-primary">
                        <em x-cloak x-show="submitting" class="spinner-border spinner-border-sm"></em>
                        {{ __('eshop::buttons.move') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>