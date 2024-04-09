import {Controller} from '@hotwired/stimulus';

/**
 * This component is the student's login form. It lets student
 * log on MPW by providing:
 * 1. the classroom's code (4 characters)
 * 2. their password (a combination of 4 coloured shapes)
 */
export default class extends Controller {

    static targets = ["inputClassroom", "composer", "inputView", "inputValue", "classroomCode", "classroomId", "form"];

    static values = {
        maxlength: Number,
        url: String,
        code: String
    };

    dragOverClass = "drag-over";
    colorClass = ".student-password-color";
    shapeClass = ".student-password-shape";
    loggedClass = "logged";
    filledState = "filled";
    hasInputState = "has-input";
    currentItem = null;
    urlCodePlaceholder = "code";
    errorClass = "error";
    loadingClass = "loading";

    /**
     * Returns the amount of characters in the input.
     */
    inputLength = () => {
        return this.inputValueTarget.value.length / 2;
    }

    /**
     * Initialization. Listens for drag/drop events.
     */
    connect() {
        this.element.querySelectorAll(this.shapeClass).forEach(item => {
            item.addEventListener('dragstart', () => this.dragStart(item));
            item.addEventListener('dragend', () => this.dragEnd());
        });
        this.element.querySelectorAll(this.colorClass).forEach(color => {
            color.addEventListener('dragover', (evt) => this.dragOver(evt));
            color.addEventListener('dragenter', (evt) => this.dragEnter(evt, color));
            color.addEventListener('dragleave', (evt) => this.dragLeave(evt, color));
            color.addEventListener('drop', (evt) => this.dragDrop(evt, color));
        });

        if (this.codeValue) {
            this.checkClassroomExists(this.codeValue);
        }
    }

    /**
     * Handles when the user validates the classroom code.
     */
    selectClassroom() {
        this.checkClassroomExists(this.inputClassroomTarget.value);
    }

    /**
     * Checks if the given classroom exists by providing its
     * code to the API.
     */
    checkClassroomExists(code) {
        const url = this.urlValue.replace(this.urlCodePlaceholder, code);
        this.element.classList.add(this.loadingClass);
        fetch(url)
            .then(res => {
                const status = res.status;
                if (status === 404) {
                    this.addErrorClass('classroom');
                } else {
                    this.element.classList.add('step-2');
                    res.text().then(r => {
                        this.removeErrorClass();
                        const json = JSON.parse(r);
                        this.classroomCodeTarget.innerHTML = json.code;
                        this.classroomIdTarget.value = json.id;
                    });
                }
            }).catch(_ => {
            this.addErrorClass('classroom');
        }).finally(() => {
            this.element.classList.remove(this.loadingClass);
        });
    }


    /**
     * Adds an error class to the element.
     */
    addErrorClass(type) {
        this.element.classList.add(this.errorClass, `${this.errorClass}-${type}`);
    }

    /**
     * Removes the error class from the element.
     */
    removeErrorClass() {
        this.element.classList.remove(this.errorClass);
    }

    /**
     * Clears the classroom
     */
    clearClassroom() {
        this.element.classList.remove('step-2');
        this.removeErrorClass();
        this.clear();
    }

    /**
     * Handles when an item gets dragged.
     */
    dragStart(item) {
        this.currentItem = item;
    }

    /**
     * Handles when an item gets released.
     */
    dragEnd() {
        this.currentItem = null;
    }

    /**
     * Handles when an element is being dragged over an eligible drop target.
     *
     * Called multiple times.
     */
    dragOver(event) {
        event.preventDefault();
    }

    /**
     * Handles when a dragged element enters an eligible drop target.
     *
     * Called once.
     */
    dragEnter(event, colorElement) {
        colorElement.classList.add(this.dragOverClass);
    }

    /**
     * Handles when a dragged element leaves a valid drop target.
     */
    dragLeave(event, colorElement) {
        colorElement.classList.remove(this.dragOverClass);
    }

    /**
     * Handles when an element gets dropped in a valid target.
     *
     * This will create a new character in the "password field".
     */
    dragDrop(event, colorElement) {
        if (this.currentItem) {
            colorElement.classList.remove(this.dragOverClass);
            this.createCharacter(this.currentItem, colorElement);
        }
    }

    /**
     * Creates a new character given a shape and a color. This method
     * will also clamp the password's length.
     */
    createCharacter(shapeElement, colorElement) {
        if (this.inputLength() >= this.maxlengthValue) {
            return;
        }
        const elem = document.createElement("div");
        elem.classList.add('student-password-character', `student-password-${shapeElement.dataset.shape}-${colorElement.dataset.color}`);
        this.inputViewTarget.append(elem);
        setTimeout(() => {
            elem.classList.add('active');
        }, 0);
        this.inputValueTarget.value = `${this.inputValueTarget.value}${shapeElement.dataset.shape[0]}${colorElement.dataset.color[0]}`;
        this.element.classList.add(this.hasInputState);

        if (this.inputLength() === this.maxlengthValue) {
            this.element.classList.add(this.filledState);
        }
    }

    /**
     * Clears the password field.
     */
    clear() {
        this.inputViewTarget.innerHTML = "";
        this.inputValueTarget.value = "";
        this.element.classList.remove(this.filledState);
    }

    /**
     * Handles the login action. Performs a request against the API and
     * redirect the user if they successfully log in.
     */
    login(evt) {
        const form = this.formTarget;
        evt.preventDefault();
        fetch(form.getAttribute('action'), {
            method: form.getAttribute('method'),
            body: new FormData(form)
        }).then(res => {
            res.text().then(r => {
                const data = JSON.parse(r);
                if (res.status !== 200) {
                    this.addErrorClass('code');
                    this.clear();
                    evt.target.classList.remove('loading');
                } else {
                    this.element.classList.add(this.loggedClass);
                    setTimeout(() => {
                        window.location.href = data.url;
                    }, 2500);
                }
            });

        });
    }
}
