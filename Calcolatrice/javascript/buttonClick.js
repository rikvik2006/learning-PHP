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
    FactorialBlock,
} from "./blocks/Block.js";
import { EmptyBlock } from "./blocks/EmptyBlock.js";
import { Calculator } from "./Calculator.js";
import { ErrorScreenManager } from "./ErrorScreenManager.js";
import { FrontEndParser } from "./FrontEndParser.js";
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

function validateAdiacentOperators($expressionString) {
    console.log("⭐ validando");
    const baseUrl =
        window.location.origin +
        window.location.pathname.replace(/\/index\.php$/, "");
    const url = new URL(
        baseUrl + "/php/controller/validateOperatorController.php"
    );

    console.log("🔢", url);

    return fetch(url, {
        method: "POST",
        //send expresssion string as urlencoded form data
        body: new URLSearchParams({ expression: $expressionString }),
    });
}

export const calculator = new Calculator();

document.addEventListener("DOMContentLoaded", () => {
    // const calculator = new Calculator();
    const screenManager = new ScreenManager();

    const buttons = document.querySelectorAll(".btn");
    const form = document.querySelector("form.calculator_container");
    const storeInput = document.getElementById("invisible_store_screen");
    const invisibileScreen = document.getElementById(
        "invisible_calculator_screen"
    );

    console.log("🔢", invisibileScreen.value);

    FrontEndParser.parseInput(invisibileScreen.value).forEach((block) => {
        console.log("⭐", block);

        calculator.addBlock(block);

        // Aggiorna lo schermo
        screenManager.updateScreen(calculator.getBlocks());
    });

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
            } else if (blockType === "decimal") {
                block = new NumberBlock(character);
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
            } else if (blockType === "fact") {
                block = new FactorialBlock();
            } else if (button.hasAttribute("data-clear")) {
                calculator.blocks = []; // Svuota tutti i blocchi
                block = null;
            } else if (button.hasAttribute("data-backspace")) {
                calculator.removeBlock(calculator.getLastBlock());
                block = null;
            } else if (button.hasAttribute("data-equals")) {
                // Invia il form
                form.submit();
            } else if (button.hasAttribute("data-sto")) {
                storeInput.value = "store";
                form.submit();
            } else if (button.hasAttribute("data-memplus")) {
                storeInput.value = "memplus";
                form.submit();
            } else if (button.hasAttribute("data-mem")) {
                storeInput.value = "recall";
                form.submit();
            }

            if (block) {
                calculator.addBlock(block);
            }

            // Aggiorna lo schermo
            console.log("💀", calculator.getBlocks());
            screenManager.updateScreen(calculator.getBlocks());
            validateAdiacentOperators(
                calculator
                    .getBlocks()
                    .map((block) => block.getValue())
                    .join(";")
            )
                .then((response) => response.json())
                .then((data) => {
                    if (data.error) {
                        console.error(data.error);
                        // calculator.removeBlock(calculator.getLastBlock());
                        ErrorScreenManager.updateError(data.error);
                    } else if (data.status == "ok") {
                        ErrorScreenManager.updateError("Schermo Errori");
                    }
                });
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
