import { Block, NumberBlock, SqrtBlock } from "./blocks/Block.js";
import { ScreenManager } from "./ScreenManager.js";

class Calculator {
    constructor() {
        this.blocks = [
            // new SqrtBlock(new NumberBlock("4"), new NumberBlock("16")),
        ];
    }

    getBlocks() {
        return this.blocks;
    }

    /**
     * @param {number} index
     * @returns {Block}
     */
    getBlock(index) {
        return this.blocks[index];
    }

    /**
     * @param {Block} block
     */
    addBlock(block) {
        this.blocks.push(block);
    }

    /**
     * @param {Block} block
     */
    removeBlock(block) {
        const index = this.blocks.indexOf(block);
        if (index !== -1) {
            this.blocks.splice(index, 1);
        }
    }

    /**
     * @returns {Block}
     */
    getLastBlock() {
        return this.blocks[this.blocks.length - 1];
    }

    /**
     * @returns {Block}
     */
    getFirstBlock() {
        return this.blocks[0];
    }

    /**
     * @returns {string}
     */
    getValue() {
        return this.blocks.map((block) => block.getValue()).join("");
    }

    // Cursor
    getCursorIndex() {
        return this.cursorIndex;
    }

    setCursorIndex(index) {
        if (index >= 0 && index <= this.blocks.length) {
            this.cursorIndex = index;
        }
    }

    moveCursorLeft() {
        this.setCursorIndex(this.cursorIndex - 1);
    }

    moveCursorRight() {
        this.setCursorIndex(this.cursorIndex + 1);
    }

    // moveCursorLeft() {
    //     if (this.cursorIndex > 0) {
    //         this.cursorIndex--;
    //     }
    // }

    // moveCursorRight() {
    //     if (this.cursorIndex < this.blocks.length - 1) {
    //         this.cursorIndex++;
    //     }
    // }
}

export { Calculator };
