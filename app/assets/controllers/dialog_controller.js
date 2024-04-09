import {Controller} from '@hotwired/stimulus';

/**
 * This component is a dialog which dynamically loads
 * its content and allow the user to navigate inside it.
 *
 * A dialog may be set fullscreen and its content may be
 * printed.
 */
export default class extends Controller {

    static targets = ["back", "inner"];

    content;

    shadow;

    activeClass = 'active';

    loadedClass = 'loaded';

    fullscreenClass = 'fullscreen';

    history = [];

    /**
     * Initialization.
     */
    connect() {
        this.shadow = this.element.querySelector('.dialog-content').attachShadow({mode: "open"});
        this.content = this.element.querySelector('.dialog-content');
        this.innerTarget.onfullscreenchange = (_) => {
            if (!document.fullscreenElement) {
                this.element.classList.remove(this.fullscreenClass);
            }
        };
    }

    /**
     * Opens the dialog and loads the content.
     *
     * The open method is also ued for internal navigation. Each
     * time new content is loaded, the component will scan for
     * possible routes (element with attribute data-dialog-route) inside
     * the view and listen for any click on the links.
     *
     * The open method manages the state of the back button which lets
     * the user going back to the previous page.
     */
    open(url, clearHistory = true) {
        if (clearHistory) {
            this.history = [];
            this.shadow.innerHTML = "";
        }
        this.history.push(url);

        const hasHistory = this.history.length > 1;
        const isBackVisible = this.backTarget.classList.contains('visible');

        if (hasHistory && !isBackVisible) {
            this.backTarget.classList.add('visible');
        } else if (!hasHistory && isBackVisible) {
            this.backTarget.classList.remove('visible');
        }

        this.element.classList.add(this.activeClass);
        this.element.classList.remove(this.loadedClass);
        this.element.focus();

        fetch(url)
            .then(res => res.text())
            .then(res => {
                this.shadow.innerHTML = res;
                this.content.scrollTo(0, 0);
                this.element.classList.add(this.loadedClass);
                this.scanRoutes();
            });
    }

    /**
     * Closes the dialog.
     */
    close() {
        this.closeFullscreen();
        this.element.classList.remove(this.activeClass);
    }

    /**
     * Opens a new window pointing to the content's URL along with
     * a viewMode parameter to tell the view that we want to print it.
     */
    print() {
        const url = new URL(`${this.history[this.history.length - 1]}?viewMode=print`, window.location.origin);
        const win = window.open(url);
        win.focus();
        setTimeout(() => {
            win.print();
        }, 500);
        setTimeout(() => {
            win.close();
        }, 2500);
    }

    /**
     * Toggles the fullscreen state of the dialog.
     */
    toggleFullscreen() {
        if (!document.fullscreenElement) {
            this.innerTarget.requestFullscreen();
            this.element.classList.add(this.fullscreenClass);
        } else {
            this.closeFullscreen();
        }
    }

    /**
     * Exits fullscreen.
     */
    closeFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen().catch(() => {
            });
            this.element.classList.remove(this.fullscreenClass);
        }
    }

    /**
     * Goes back in the history by one step and
     * load the content.
     */
    back() {
        this.history.pop();
        this.open(this.history.pop(), false);
    }

    /**
     * Scan for any internal route (element matching
     * [data-dialog-route]).
     */
    scanRoutes() {
        const routes = this.shadow.querySelectorAll("[data-dialog-route]");
        for (let route of routes) {
            route.addEventListener('click', event => {
                event.preventDefault();
                this.open(route.getAttribute("href"), false);
            });
        }
    }
}
