import {Controller} from '@hotwired/stimulus';

/**
 * This component lets the user add or remove items of a
 * collection inside a Symfony's form. This requires to have
 * a sub-form prototype rendered from the view in order
 * to have this component generic.
 */
export default class extends Controller {

    static targets = ["collectionContainer"]

    static values = {
        index: Number,
        prototype: String,
    }

    /**
     * Initialization.
     */
    connect() {
        this.collectionContainerTarget
            .querySelectorAll(':scope > div')
            .forEach((item) => {
                this.addTagFormDeleteLink(item)
            })
    }

    /**
     * Adds a new sub-form to the collection.
     */
    addCollectionElement(_) {
        const item = document.createElement('div');
        item.innerHTML = this.prototypeValue.replace(/__name__/g, this.indexValue);
        this.collectionContainerTarget.appendChild(item);
        quilljs_textarea(`#session_activities_${this.indexValue} textarea`);
        this.indexValue++;
        this.addTagFormDeleteLink(item);
    }

    /**
     * Adds a "remove" link inside a sub-form so that
     * we can remove a collection entry.
     */
    addTagFormDeleteLink(item) {
        const removeButton = document.createElement('button');
        removeButton.classList = 'button button-negative';
        removeButton.innerHTML = '<i class="fa fa-times"></i>';
        removeButton.addEventListener('click', () => {
            item.remove();
        });
        item.append(removeButton);
    }
}
