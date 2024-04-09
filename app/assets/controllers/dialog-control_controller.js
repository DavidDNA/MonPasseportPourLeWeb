import {Controller} from '@hotwired/stimulus';

/**
 * This component will open a dialog.
 */
export default class extends Controller {

    open(event) {
        let dialogController = this.application.getControllerForElementAndIdentifier(document.querySelector('#dialog'), 'dialog');
        dialogController.open(event.params.url);
    }
}
