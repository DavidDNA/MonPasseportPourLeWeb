import {Controller} from '@hotwired/stimulus';

/**
 * This component is a confirm dialog. The confirm dialog
 * asks the user to prompt a confirmation message. This dialog
 * is mainly used to delete elements.
 */
export default class extends Controller {

    static targets = ["confirm", "confirmInput"];

    activeClass = 'active';

    confirmCallback;

    closeOnConfirm = false;

    /**
     * Initialization.
     */
    connect() {
    }

    /**
     * Opens the dialog and resets values
     * to their defaults.
     */
    open(confirmCallback, closeOnConfirm) {
        this.element.classList.add(this.activeClass);
        this.confirmCallback = confirmCallback;
        this.confirmTarget.classList.remove('loading');
        this.closeOnConfirm = closeOnConfirm;
        if (this.hasConfirmInputTarget) {
            this.confirmInputTarget.value = "";
            this.confirmTarget.disabled = true;
        }
        this.element.focus();
    }

    /**
     * Closes the dialog.
     */
    close() {
        this.element.classList.remove(this.activeClass);
    }

    /**
     * Cancels the action.
     */
    cancel() {
        this.close();
    }

    /**
     * Confirm the action.
     */
    confirm() {
        if (this.confirmCallback) {
            this.confirmCallback();
            if (this.closeOnConfirm) {
                this.close();
            } else {
                this.confirmTarget.classList.add('loading');
            }
        }
    }

    /**
     * Handles when the user prompts the confirmation message.
     * Disables the button as long as the confirmation message does
     * not match the word.
     */
    onConfirmInputUp() {
        this.confirmTarget.disabled = this.confirmInputTarget.value !== this.confirmInputTarget.dataset.confirmWord;
    }
}
