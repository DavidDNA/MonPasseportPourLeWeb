import {Controller} from '@hotwired/stimulus';

/**
 * This component opens a confirm dialog and executes
 * the primary action of the host element.
 */
export default class extends Controller {

    confirmed = false;

    /**
     * Initialization.
     */
    connect() {
    }


    /**
     * Opens the dialog and wait for it to be closed.
     *
     * Prevents the default action of the host element and execute it only
     * if the user confirmed the action.
     */
    open(event) {
        const controller = this.application.getControllerForElementAndIdentifier(document.getElementById(event.params.id), 'confirm-dialog');

        if (!this.confirmed) {
            event.preventDefault();
        }
        controller.open(() => {
            this.confirmed = true;
            this.element.click();
            this.confirmed = false;
        }, event.params.autoclose);
    }
}
