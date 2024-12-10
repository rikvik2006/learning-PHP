/**
 * @abstract Abstract class that rappresent a block of the calculator
 */
class Block {
    /**
     *
     * @param {string} character
     */
    constructor(character) {
        this.generateBlockElement();
        this.setCharacter(character);

        // Create a mimic of abstract class
        if (this.constructor === Block) {
            throw new TypeError(
                "Class is of abstract type and can't be instantiated"
            );
        }

        // Create a mimic of abstract method
        // Get value method need to return a string that follow the procol of the calculator, this string will be read by the parser (in the server) and this string will be converted into classes, after that the every classes will make a string that will be evaluated using eval to get the result)
        if (this.getValue === undefined) {
            throw new TypeError("getValue method must be implemented");
        }

        if (this.updateBlockElement === undefined) {
            throw new TypeError(
                "updateBlockElement method must be implemented"
            );
        }
    }

    generateBlockElement() {
        this.block = document.createElement("div");
        this.block.classList.add("block");
    }

    getBlock() {
        return this.block;
    }

    setCharacter(character) {
        this.character = character;
    }

    getCharacter() {
        return this.character;
    }
}

class NumberBlock extends Block {
    constructor(character) {
        super(character);
        this.block.classList.add("number-block");
        this.updateBlockElement();
    }

    getValue() {
        return this.character;
    }

    updateBlockElement() {
        this.block.textContent = this.character;
    }
}

class OperatorBlock extends Block {
    constructor(character) {
        super(character);
        this.block.classList.add("operator-block");
        this.updateBlockElement();
    }

    getValue() {
        return this.character;
    }

    updateBlockElement() {
        this.block.textContent = this.character;
    }
}

/**
 * @abstract Abstract class that rappresent a function block of the calculator
 */
class FunctionBlock extends Block {
    constructor(character) {
        super(character);

        if (this.constructor === FunctionBlock) {
            throw new TypeError(
                "Class is of abstract type and can't be instantiated"
            );
        }

        if (this.getValue === undefined) {
            throw new TypeError("getValue method must be implemented");
        }

        if (this.updateBlockElement === undefined) {
            throw new TypeError(
                "updateBlockElement method must be implemented"
            );
        }

        this.subBlocks = [];
        this.subBlocksCursorIndex = 0;
    }

    addSubBlock(block) {
        if (!(block instanceof Block)) {
            throw new TypeError("Block must be an instance of Block");
        }

        this.subBlocks.push(block);
    }

    /**
     * Sposta il cursore tra i sotto-blocchi o esce dalla funzione.
     * @param {string} direction - 'left' o 'right'
     * @returns {boolean} - `true` se il cursore è ancora all'interno della funzione, `false` se è uscito.
     */
    // moveCursorInside(direction) {
    //     console.log("🚀", this.subBlocksCursorIndex);
    //     if (this.subBlocksCursorIndex == 0) {
    //         this.subBlocks[this.subBlocksCursorIndex].block.classList.add(
    //             "cursor"
    //         );
    //     } else {
    //         if (direction === "left") {
    //             if (this.subBlocksCursorIndex > 0) {
    //                 this.subBlocksCursorIndex--;
    //                 return true; // Rimaniamo all'interno
    //             }
    //         } else if (direction === "right") {
    //             if (this.subBlocksCursorIndex < this.subBlocks.length - 1) {
    //                 this.subBlocksCursorIndex++;
    //                 return true; // Rimaniamo all'interno
    //             }
    //         }
    //     }

    //     // Se siamo qui, dobbiamo uscire dalla funzione
    //     return false;
    // }

    /**
     * Restituisce il sotto-blocco attualmente selezionato.
     * @returns {Block}
     */
    getCurrentSubBlock() {
        return this.subBlocks[this.subBlocksCursorIndex];
    }
}

/**
 * @extends Block
 * @property {Block} __index
 * @property {Block} __argument
 */
class SqrtBlock extends FunctionBlock {
    /**
     *
     * @param {Block} index
     * @param {Block} argument
     */
    constructor(index, argument) {
        super("../../public/sqrt_image.svg");
        this.block.classList.add("sqrt-block");

        this.setIndex(index);
        this.setArgument(argument);
        this.addSubBlock(index);
        this.addSubBlock(argument);
        this.updateBlockElement();
    }

    setIndex(index) {
        if (!(index instanceof Block)) {
            throw new TypeError("Index must be an instance of Block");
        }

        this.__index = index;
    }

    getIndex() {
        return this.__index;
    }

    setArgument(argument) {
        if (!(argument instanceof Block)) {
            throw new TypeError("Argument must be an instance of Block");
        }

        this.__argument = argument;
    }

    getArgument() {
        return this.__argument;
    }

    /**
     * Return a string that rappresent an n_root that follow the procol of the calculator
     * @returns {string}
     */
    getValue() {
        return `\\sqrt[${this.index.getValue()}]{${this.argument.getValue()}}`;
    }

    updateBlockElement() {
        const sqrtImage = document.createElement("img");
        sqrtImage.src = this.character;
        sqrtImage.classList.add("sqrt-image");
        this.block.innerHTML = `${this.__index.block}${sqrtImage.outerHTML}${this.__argument.block}`;
        this.__index.block.classList.add("index");
        this.__argument.block.classList.add("argument");
    }
}

class SinBlock extends FunctionBlock {
    /**
     *
     * @param {Block} argument
     */
    constructor(argument) {
        super("sin");
        this.block.classList.add("sin-block");

        this.block.innerText = `${this.character}(`;
        this.setArgument(argument);
        this.addSubBlock(argument);
        this.updateBlockElement();
        this.block.innerHTML += ")";
    }

    setArgument(argument) {
        if (!(argument instanceof Block)) {
            throw new TypeError("Argument must be an instance of Block");
        }

        this.argument = argument;
    }

    getValue() {
        return `\\sin{${this.argument.getValue()}}`;
    }

    updateBlockElement() {
        this.block.innerHTML += this.argument.block.outerHTML;
        this.argument.block.classList.add("argument");
    }
}

export {
    Block,
    NumberBlock,
    OperatorBlock,
    SqrtBlock,
    SinBlock,
    FunctionBlock,
};

// README: Continua con la crezione del blocco SQRT inserendo l'indece e l'argomento, renderizzando l'immagine e mettodo il bordo al altezza giusta
