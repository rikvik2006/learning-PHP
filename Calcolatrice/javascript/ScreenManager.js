import { Block } from "./blocks/Block.js";
import { calculator } from "./buttonClick.js";

class ScreenManager {
    constructor() {
        this.screenContainer = document.querySelector(
            ".calculator_screen .expression"
        );
        this.invisibleScreen = document.getElementById(
            "invisible_calculator_screen"
        );
    }

    /**
     * Aggiorna lo schermo visivo e l'input invisibile.
     * @param {Block[]} blocks
     */
    updateScreen(blocks) {
        // Aggiorna il contenuto dello schermo visivo
        this.screenContainer.innerHTML = this.getBlocksHTML(blocks);

        // Aggiorna il valore dell'input invisibile
        const screenValue = blocks.map((block) => block.getValue()).join("");
        this.invisibleScreen.value = screenValue || "0";
    }

    /**
     * Ottiene l'HTML dei blocchi.
     * @param {Block[]} blocks
     * @returns {string}
     */
    // getBlocksHTML(blocks) {
    //     return blocks
    //         .map((block, index) => {
    //             const isSelected = index === calculator.getCursorIndex();
    //             block.block.classList.toggle("cursor", isSelected);
    //             return block.block.outerHTML;
    //         })
    //         .join("");
    // }

    getBlocksHTML(blocks) {
        return blocks.map((block) => block.block.outerHTML).join("");
    }
}

export { ScreenManager };
