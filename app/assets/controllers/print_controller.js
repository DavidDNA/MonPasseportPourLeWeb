import {Controller} from '@hotwired/stimulus';

/**
 * This component simply prints the current location.
 */
export default class extends Controller {

    print() {
        window.print();
    }
}
