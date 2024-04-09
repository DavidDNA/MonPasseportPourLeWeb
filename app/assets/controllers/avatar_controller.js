import {BaseAvatarController} from "./avatar-base_controller";

const AvatarDefaultUpgradeName = "default";

/**
 * This component provides a base implementation for an avatar.
 */
export default class extends BaseAvatarController {

    /**
     * The current animation.
     */
    animation = null;

    /**
     * Returns the avatar parts container.
     */
    get container() {
        return this.element;
    }

    get animation() {
        return this.animation;
    }

    static values = {
        avatar: Object
    }

    /**
     * Initializes the component - renders all the avatar parts.
     */
    connect() {
        this.render(this.getParts(this.avatarValue));
    }

    /**
     * Returns all the avatar's parts given the raw data.
     * - upgrades
     * - choice
     * -- upgrade
     * -- name
     */
    getParts(avatarData) {
        const parts = [];
        const sorted = avatarData.upgrades.sort((a, b) => a.upgrade.priority > b.upgrade.priority ? 1 : -1);
        for (let upgrade of sorted) {
            parts.push({
                priority: upgrade.upgrade.priority,
                name: upgrade.upgrade.name,
                type: upgrade.upgrade.type,
                choice: upgrade.choice
            });
        }
        return parts;
    }

    /**
     * Renders the avatar by loading and bootstrapping the animation.
     */
    render(parts) {
        while (this.container.firstChild) {
            this.container.firstChild.remove();
        }

        // don't render default part
        if (parts.length > 1) {
            parts = parts.filter(p => !p.name.startsWith(AvatarDefaultUpgradeName));
        }

        // find animation
        const animation = parts.find(p => p.type === "animation")?.choice;
        this.animation = animation;

        // take only sprites
        parts = parts.filter(p => p.type === "sprite");

        for (let part of parts) {
            const name = this.getPartName(part.name, part.choice);
            void this.loadPart(name, this.container, part.priority, null, animation);
        }
    }

    /**
     * Hides the default avatar.
     */
    hideDefault() {
        this.animationControllers.find(anim => anim.part.startsWith(AvatarDefaultUpgradeName))?.controller.destroy();
    }

    /**
     * Resets the animation controllers so that the restart the current animation.
     */
    reset() {
        for (let anim of this.animationControllers) {
            const controller = anim.controller;
            if (controller.isPaused) {
                return;
            }
            controller.stop();
            controller.play();
        }
    }
}
