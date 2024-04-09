import {Controller} from '@hotwired/stimulus';

/**
 * This component provides a base implementation for an avatar.
 */
export class BaseAvatarController extends Controller {

    /**
     * Lottie player
     */
    animationControllers = [];

    /**
     * Returns a string identifying the part file.
     */
    getPartName(id, choice) {
        return `${id}-${choice}`;
    }

    /**
     * Loads a part given its id into the given container. Starts an
     * animation if required and gives the possibility to name it in order
     * to control it through Lottie's API.
     */
    loadPart(part, container, priority = 0, controlName = null, animationName = null) {
        return new Promise((accept, reject) => {
            // - First we need a container to host all parts
            // - Then we'll create an element to render the animation
            // - We'll place this element into our container
            // - We then kindly ask lottie to load the animation
            const element = document.createElement("div");
            element.style.zIndex = `${priority}`;
            container.append(element);
            const url = `/build/avatar/${part}.json`;

            fetch(url)
                .then(res => res.text())
                .then((data) => {
                    const animationData = JSON.parse(data);
                    const params = {
                        name: controlName,
                        container: element,
                        renderer: 'svg',
                        loop: true,
                        autoplay: !!animationName,
                        animationData: animationData
                    };
                    const anim = lottie.loadAnimation(params);
                    if (animationName) {
                        anim.goToAndPlay(animationName, true);
                    }
                    this.animationControllers.push({
                        part: part, controller: anim
                    });
                    accept(lottie);
                }).catch((reason) => {
                reject(reason);
            });
        });
    }

    /**
     * Plays the given animation.
     */
    playAnimation(name) {
        for (let anim of this.animationControllers) {
            anim.controller.goToAndPlay(name, true);
        }
    }
}
