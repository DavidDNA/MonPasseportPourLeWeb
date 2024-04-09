import {Controller} from '@hotwired/stimulus';

/**
 * This component adds a class on a button on click in
 * order to disable the button and prevent multiple submission
 * during browser load.
 */
export default class extends Controller {

    /**
     * Initializes the component. Simply applies a class
     * on click which will prevents clicking again until the
     * action is done (not handled by this component).
     */
    connect() {
        this.element.addEventListener('click', () => {
            this.element.classList.add('loading');
        });
    }

    /**
     * Removes the loading class.
     */
    release() {
        this.element.classList.remove('loading');
    }
}
