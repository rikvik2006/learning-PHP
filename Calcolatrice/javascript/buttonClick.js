import {
    Block,
    NumberBlock,
    OperatorBlock,
    SqrtBlock,
    SinBlock,
    Sqrt2Block,
    CosBlock,
    TanBlock,
    PowerOf2,
    PowerOfNBlock,
} from "./blocks/Block.js";
import { EmptyBlock } from "./blocks/EmptyBlock.js";
import { Calculator } from "./Calculator.js";
import { ScreenManager } from "./ScreenManager.js";

/**
 * Add the value of the block to the invisible input where is content will be sent to the server
 * @param {Block} block
 */
function addBlockToInput(block) {
    const screen = document.getElementById("invisible_calculator_screen");
    const currentValue = screen.value === "0" ? "" : screen.value;
    screen.value = currentValue + block.getValue();
}

export const calculator = new Calculator();

document.addEventListener("DOMContentLoaded", () => {
    // const calculator = new Calculator();
    const screenManager = new ScreenManager();

    const buttons = document.querySelectorAll(".btn");
    const form = document.querySelector("form.calculator_container");

    buttons.forEach((button) => {
        button.addEventListener("click", () => {
            const blockType = button.getAttribute("data-type");
            const character = button.getAttribute("data-character");
            let block;

            // Crea il blocco corretto basandosi sul tipo
            if (blockType === "number") {
                block = new NumberBlock(character);
            } else if (blockType === "operator") {
                block = new OperatorBlock(character);
            } else if (blockType === "sin") {
                block = new SinBlock();
            } else if (blockType === "cos") {
                block = new CosBlock();
            } else if (blockType === "tan") {
                block = new TanBlock();
            } else if (blockType === "nroot") {
                block = new SqrtBlock();
            } else if (blockType === "sqrt") {
                block = new Sqrt2Block();
            } else if (blockType === "pow2") {
                block = new PowerOf2();
            } else if (blockType === "pow_n") {
                block = new PowerOfNBlock();
            } else if (button.hasAttribute("data-clear")) {
                calculator.blocks = []; // Svuota tutti i blocchi
                block = null;
            } else if (button.hasAttribute("data-backspace")) {
                calculator.removeBlock(calculator.getLastBlock());
                block = null;
            } else if (button.hasAttribute("data-equals")) {
                // Invia il form
                form.submit();
            }

            if (block) {
                calculator.addBlock(block);
            }

            // Aggiorna lo schermo

            console.log("💀", calculator.getBlocks());
            screenManager.updateScreen(calculator.getBlocks());
        });
    });

    // document.addEventListener("keydown", (event) => {
    //     if (event.key === "ArrowLeft") {
    //         calculator.moveCursorLeft();
    //     } else if (event.key === "ArrowRight") {
    //         calculator.moveCursorRight();
    //     }

    //     if (event.key === "ArrowLeft") {
    //         const currentBlock = calculator.getBlock(
    //             calculator.getCursorIndex()
    //         );
    //         // TODO: Utilizzare la classe FunctionBlock che contiene un array di sotto blocchi della funzione
    //         if (currentBlock instanceof FunctionBlock) {
    //             currentBlock.moveCursorInside("left");
    //         } else {
    //             calculator.moveCursorLeft();
    //         }
    //     } else if (event.key === "ArrowRight") {
    //         const currentBlock = calculator.getBlock(
    //             calculator.getCursorIndex()
    //         );
    //         if (currentBlock instanceof FunctionBlock) {
    //             currentBlock.moveCursorInside("right");
    //         } else {
    //             calculator.moveCursorRight();
    //         }
    //     }

    //     screenManager.updateScreen(calculator.getBlocks());
    // });

    // document.addEventListener("keydown", (event) => {
    //     // const currentBlock = calculator.getBlock(calculator.getCursorIndex());

    //     if (event.key === "ArrowLeft") {
    //         const currentBlock = calculator.getBlock(
    //             calculator.getCursorIndex() - 1
    //         );

    //         console.log(
    //             "🔢 index",
    //             calculator.getCursorIndex() - 1,
    //             "📒 currentBlock",
    //             currentBlock
    //         );

    //         if (currentBlock instanceof FunctionBlock) {
    //             const isInside = currentBlock.moveCursorInside("left");
    //             if (!isInside) {
    //                 calculator.moveCursorLeft(); // Esci dalla funzione
    //             }
    //         } else {
    //             calculator.moveCursorLeft();
    //         }
    //     } else if (event.key === "ArrowRight") {
    //         const currentBlock = calculator.getBlock(
    //             calculator.getCursorIndex() + 1
    //         );

    //         console.log(
    //             "🔢 index",
    //             calculator.getCursorIndex() + 1,
    //             "📒 currentBlock",
    //             currentBlock
    //         );

    //         if (currentBlock instanceof FunctionBlock) {
    //             const isInside = currentBlock.moveCursorInside("right");
    //             if (!isInside) {
    //                 calculator.moveCursorRight(); // Esci dalla funzione
    //             }
    //         } else {
    //             calculator.moveCursorRight();
    //         }
    //     }

    //     // Aggiorna lo schermo
    //     screenManager.updateScreen(calculator.getBlocks());
    // });
});
