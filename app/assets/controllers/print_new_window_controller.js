import {Controller} from '@hotwired/stimulus';

/**
 * This component simply prints the current location.
 */
export default class extends Controller {

    print(event) {
        console.warn(event.params);
        const url = new URL(event.params.url, window.location.origin);
        const win = window.open(url);
        win.focus();
        setTimeout(() => {
            win.print();
        }, 500);
        setTimeout(() => {
            win.close();
        }, 2500);
    }
}
