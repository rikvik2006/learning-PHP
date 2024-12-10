import { Block } from "./Block.js";

class EmptyBlock extends Block {
    constructor() {
        super("0");
        this.block.classList.add("empty-block");
        this.updateBlockElement();
    }

    getValue() {
        return "{EMPTY}";
    }

    updateBlockElement() {
        this.block.textContent = "0";
    }
}

export { EmptyBlock };
