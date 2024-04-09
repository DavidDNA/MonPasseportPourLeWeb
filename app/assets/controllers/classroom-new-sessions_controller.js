import {GroupsController} from "./groups_controller";

/**
 * This component is a tab controller specific to the
 * classroom creation. It disables all first and last
 * checkboxes of a group because teachers are not allowed
 * to remove the first and last sessions of a session plan.
 */
export default class extends GroupsController {

    disabledClass = "disabled";

    /**
     * Initialization.
     */
    connect() {
        super.connect();
        const checkboxes = this.element.querySelectorAll("input[type=checkbox][checked]");
        if (checkboxes.length > 0) {
            checkboxes[0].parentNode.classList.add(this.disabledClass);
            checkboxes[checkboxes.length - 1].parentNode.classList.add(this.disabledClass);
        }
    }
}
