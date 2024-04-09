import {BaseAvatarController} from "./avatar-base_controller";

/**
 * This component is a dialog which lets the user
 * upgrade their avatar by selecting between multiple
 * options.
 */
export default class extends BaseAvatarController {

    static targets = ["choices", "confirm", "token"];

    static outlets = ["avatar"]

    static values = {
        upgrade: Object, endpoint: String, upgradesi18n: Object
    }

    selectedElement = null;

    selectedClass = "selected";

    visibleClass = "visible";

    loadingClass = "loading";

    errorClass = "error";

    /**
     * Initialization - creates the elements which show
     * the available upgrades.
     */
    connect() {
        const upgrade = this.upgradeValue;
        const slice = this.shuffle(upgrade.choices).slice(0, Math.min(upgrade.choices.length, 3));
        for (let i = 0; i < slice.length; i++) {
            this.createChoice(upgrade, i);
        }
        this.confirmTarget.disabled = true;
    }

    /**
     * Handles when the user selects an upgrade. Shows the
     * upgrade in a preview before the user confirms their choice.
     */
    select(button, upgrade, choice) {
        this.selectedElement?.classList.remove(this.selectedClass);
        this.selectedElement = button;
        this.selectedElement.classList.add(this.selectedClass);
        this.confirmTarget.disabled = false;

        const avatarController = this.application.getControllerForElementAndIdentifier(document.querySelector('#avatar-preview'), 'avatar');

        switch (upgrade.type) {
            case "sprite":
                avatarController.hideDefault();
                lottie.destroy("preview");
                this.loadPart(this.getPartName(upgrade.name, choice), this.avatarOutlet.container, upgrade.priority, "preview", avatarController.animation).then((_) => {
                    avatarController.reset();
                });
                break;
            case "animation":
                avatarController.playAnimation(choice);
                break;
        }
    }

    /**
     * Creates a choice element and renders the upgrade inside.
     *
     * Template:
     * <button data-action="avatar-upgrade-dialog#select">
     *   <span class="student-upgrade-dialog-choice-preview"></span>
     *   Bras 2
     * </button>
     */
    createChoice(upgrade, index) {
        const choice = upgrade.choices[index];
        const button = document.createElement("button");
        const preview = document.createElement("span");
        const name = upgrade.name;

        preview.classList.add("student-upgrade-dialog-choice-preview");
        button.append(preview);
        button.dataset["choiceIndex"] = choice;
        button.append(this.upgradesi18nValue[`${upgrade.type}.${upgrade.name}`] + ` ${index + 1}`);
        button.classList.add(upgrade.type.toLowerCase());
        button.addEventListener('click', () => this.select(button, upgrade, choice));

        if (upgrade.type === "sprite") {
            void this.loadPart(this.getPartName(name, choice), preview);
        }
        this.choicesTarget.append(button);
    }

    /**
     * Validates the choice of the user.
     *
     * This method sends an ajax request to the API, telling
     * it which upgrade was chosen by the user.
     */
    validate() {
        this.element.classList.add(this.loadingClass);

        const form = new FormData();
        form.set("upgrade-name", this.upgradeValue.name);
        form.set("upgrade-choice", this.selectedElement.dataset.choiceIndex);
        form.set("token", this.tokenTarget.value);

        fetch(this.endpointValue, {
            method: "POST", body: form,
        })
            .then((res) => {
                this.element.classList.remove(this.loadingClass);
                const status = res.status;
                if (status !== 200) {
                    this.element.classList.add(this.errorClass);
                } else {
                    res.text().then((data) => {
                        this.showUpgrade(data);
                    });
                }
            })
            .catch(() => {
                this.element.classList.add(this.errorClass);
            });
    }

    /**
     * This method is called after the user confirmed their choice.
     * It hides the choice part behind the render view and put the avatar
     * in the center of the screen.
     *
     * At the end of the animation, we force an entire page reload. This will
     * refresh the avatar in the student workspace and check if there is
     * another upgrade available.
     */
    showUpgrade(data) {
        this.element.classList.add("folded");
        setTimeout(() => {
            const avatarCtrl = this.avatarOutlet;
            avatarCtrl.render(avatarCtrl.getParts((JSON.parse(data))));
        }, 800);

        setTimeout(() => {
            this.close();
        }, 3000);

        setTimeout(() => {
            location.reload();
        }, 3250);
    }

    /**
     * Closes the dialog.
     */
    close() {
        this.element.classList.remove(this.visibleClass);
    }

    /**
     * Shuffles the given choices array.
     * https://stackoverflow.com/questions/2450954/how-to-randomize-shuffle-a-javascript-array
     */
    shuffle(choices) {
        let currentIndex = choices.length, randomIndex;

        while (currentIndex > 0) {
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex--;
            [choices[currentIndex], choices[randomIndex]] = [choices[randomIndex], choices[currentIndex]];
        }
        return choices;
    }
}
