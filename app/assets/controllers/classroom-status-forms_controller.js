import {Controller} from '@hotwired/stimulus';

/**
 * This component handles the forms of a classroom which
 * let the teacher updates the status of each student/session
 * combination.
 */
export default class extends Controller {

    /**
     * Initialization. Get all <form> from the children
     * element and submit them asynchronously instead
     * of the default browser behaviour.
     *
     * Updates the status once we get the response from the API.
     */
    connect() {
        let forms = this.element.querySelectorAll('form[name="classroom-upgrade-progress"]');
        for (let form of forms) {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                fetch(form.getAttribute('action'), {
                    method: form.getAttribute('method'),
                    body: new FormData(form)
                }).then(res => res.text())
                    .then((data) => {
                        this.applyStatus(form, JSON.parse(data));
                    });
            });
        }
    }

    /**
     * Applies the status to the given form.
     */
    applyStatus(form, data) {
        const apply = (id, status) => {
            const pill = document.querySelector(`#progress-${id}`);
            if (pill) {
                pill.classList = [];
                pill.classList.add(`status`, `status-icon`, `status-${status}`);
            }
        }

        if (Array.isArray(data)) {
            for (let progress of data) {
                apply(progress.id, progress.status);
            }
        } else {
            apply(data.id, data.status);
        }
    }
}
