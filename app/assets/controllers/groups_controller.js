import {Controller} from '@hotwired/stimulus';

/**
 * This component groups elements sharing a same "data-" attribute together.
 */
export class GroupsController extends Controller {

    /**
     * Initializes the tab component. Groups all children with
     * the same group name under a title.
     */
    connect() {
        const tabGroupName = this.element.dataset.groupName;
        const children = this.element.querySelectorAll(`[data-${tabGroupName}]`);
        const groups = [];
        for (let child of children) {
            const itemGroup = child.dataset[this.toCamel(tabGroupName)];
            if (groups.indexOf(itemGroup) === -1) {
                groups.push(itemGroup);
                const title = document.createElement(`div`);
                title.classList.add('tab-name');
                title.textContent = itemGroup;
                this.element.insertBefore(title, child);
            }
        }
    }

    /**
     * Converts the given string from snake/kebap case to camelCase.
     */
    toCamel(s) {
        return s.replace(/([-_][a-z])/ig, ($1) => {
            return $1.toUpperCase()
                .replace('-', '')
                .replace('_', '');
        });
    };
}

export default class Groups_controller {
}
