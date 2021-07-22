<div aria-live="polite" aria-atomic="true" class="position-relative" style="z-index: 9999">
    <div class="toast-container position-fixed pt-5 top-0 start-50 translate-middle-x" id="toast-top-center"
         x-data="{
            toasts: [@if(session('toast')) {{ json_encode(session('toast', ), JSON_THROW_ON_ERROR) }} @endif],

            addToast(type, msg, autohide = true) {
                this.toasts.push({id: Date.now(), type: type, body: msg, autohide: autohide})
            },

            removeToast(toast) {
                this.toasts.splice(this.toasts.indexOf(toast), 1)
            }
         }"
         x-on:show-toast.window="addToast($event.detail.type, $event.detail.body, $event.detail.autohide)"
    >
        <template x-for="toast in toasts" :key="toast.id">
            <div class="toast fade"
                 x-init="
                    new bootstrap.Toast($el, {autohide: toast.autohide}).show()
                    $el.addEventListener('hidden.bs.toast', () => removeToast(toast))
                "
                 x-bind:class="{
                    'bg-teal-500' : toast.type === 'success',
                    'bg-yellow-400' : toast.type === 'warning',
                    'bg-red-500' : toast.type === 'error',
                }"
            >
                <div class="d-flex gap-2 align-items-start">
                    <div class="toast-body" x-html="toast.body"></div>
                    <button type="button" class="btn-close p-3 ms-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </template>
    </div>
</div>