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

class GroupBlock extends Block {
    constructor($blocks) {
        super("()");
        this.block.classList.add("group-block");
        this.setBlocks($blocks);
        this.updateBlockElement();
    }

    setBlocks($blocks) {
        if (!Array.isArray($blocks)) {
            throw new TypeError("Blocks must be an array");
        }

        if ($blocks.some((block) => !(block instanceof Block))) {
            throw new TypeError("Blocks must be an array of Block instances");
        }

        this.blocks = $blocks;
    }

    getValue() {
        return `(${this.blocks.map((block) => block.getValue()).join("")})`;
    }

    updateBlockElement() {
        this.block.innerHTML = `(${this.blocks
            .map((block) => block.block.outerHTML)
            .join("")})`;
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
    constructor() {
        const sqrtIcon = `${window.location.pathname}public/sqrt_image.svg`;
        super(sqrtIcon);
        this.block.classList.add("sqrt-block");

        const indexValue = this.getIndexWithPrompt();
        const groupIndex = indexValue.split("/");
        let index;
        if (groupIndex.length > 1) {
            index = new GroupBlock([
                new NumberBlock(groupIndex[0]),
                new OperatorBlock("/"),
                new NumberBlock(groupIndex[1]),
            ]);
        } else {
            index = new NumberBlock(indexValue);
        }

        const argumentValue = this.getArgumentWithPrompt();
        const argument = new NumberBlock(argumentValue);

        this.setIndex(index);
        this.setArgument(argument);
        this.addSubBlock(index);
        this.addSubBlock(argument);
        this.updateBlockElement();
    }

    getIndexWithPrompt() {
        const regex = /^\d+(\.\d+)?(\/\d+(\.\d+)?)?$/;
        let index = prompt("Inserisci l'indice della radice quadrata");
        while (!regex.test(index)) {
            index = prompt(
                "Inserisci un numero valido per l'indice della radice quadrata"
            );
        }

        return index;
    }

    getArgumentWithPrompt() {
        let argument = prompt("Inserisci l'argomento della radice quadrata");
        while (isNaN(argument) || argument === null || argument === "") {
            argument = prompt(
                "Inserisci un numero valido per l'argomento della radice quadrata"
            );
        }

        return argument;
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
        return `\\sqrt[${this.__index.getValue()}]{${this.__argument.getValue()}}`;
    }

    updateBlockElement() {
        const sqrtImage = document.createElement("img");
        sqrtImage.src = this.character;
        sqrtImage.classList.add("sqrt-image");

        this.__index.block.classList.add("index");
        this.__argument.block.classList.add("argument");

        this.block.innerHTML = `${this.__index.block.outerHTML}<div class="argument-custom-group">${sqrtImage.outerHTML}${this.__argument.block.outerHTML}</div>`;
        this.__index.block.classList.add("index");
        this.__argument.block.classList.add("argument");
    }
}

class Sqrt2Block extends FunctionBlock {
    constructor() {
        const sqrtIcon = `${window.location.pathname}public/sqrt_image.svg`;
        super(sqrtIcon);
        this.block.classList.add("sqrt-block");

        let index = new NumberBlock("2");

        const argumentValue = this.getArgumentWithPrompt();
        const argument = new NumberBlock(argumentValue);

        this.setIndex(index);
        this.setArgument(argument);
        this.addSubBlock(index);
        this.addSubBlock(argument);
        this.updateBlockElement();
    }

    getArgumentWithPrompt() {
        let argument = prompt("Inserisci l'argomento della radice quadrata");
        while (isNaN(argument) || argument === null || argument === "") {
            argument = prompt(
                "Inserisci un numero valido per l'argomento della radice quadrata"
            );
        }

        return argument;
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
        return `\\sqrt[${this.__index.getValue()}]{${this.__argument.getValue()}}`;
    }

    updateBlockElement() {
        const sqrtImage = document.createElement("img");
        sqrtImage.src = this.character;
        sqrtImage.classList.add("sqrt-image");

        this.__index.block.classList.add("index");
        this.__argument.block.classList.add("argument");

        this.block.innerHTML = `${this.__index.block.outerHTML}<div class="argument-custom-group">${sqrtImage.outerHTML}${this.__argument.block.outerHTML}</div>`;
        this.__index.block.classList.add("index");
        this.__argument.block.classList.add("argument");
    }
}

class SinBlock extends FunctionBlock {
    constructor() {
        super("sin");
        this.block.classList.add("sin-block");

        const argumentValue = this.getArgumentWithPrompt();
        const argument = new NumberBlock(argumentValue);

        this.block.innerText = `${this.character}(`;
        this.setArgument(argument);
        this.addSubBlock(argument);
        this.updateBlockElement();
        this.block.innerHTML += ")";
    }

    getArgumentWithPrompt() {
        let argument = prompt(
            "Inserisci l'argomento della funzione seno (in gradi)"
        );
        while (isNaN(argument) || argument === null || argument === "") {
            argument = prompt(
                "Inserisci un numero valido per l'argomento della funzione seno (in gradi)"
            );
        }

        return argument;
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

class CosBlock extends FunctionBlock {
    constructor() {
        super("cos");
        this.block.classList.add("cos-block");

        const argumentValue = this.getArgumentWithPrompt();
        const argument = new NumberBlock(argumentValue);

        this.block.innerText = `${this.character}(`;
        this.setArgument(argument);
        this.addSubBlock(argument);
        this.updateBlockElement();
        this.block.innerHTML += ")";
    }

    getArgumentWithPrompt() {
        let argument = prompt(
            "Inserisci l'argomento della funzione coseno (in gradi)"
        );
        while (isNaN(argument) || argument === null || argument === "") {
            argument = prompt(
                "Inserisci un numero valido per l'argomento della funzione coseno (in gradi)"
            );
        }

        return argument;
    }

    setArgument(argument) {
        if (!(argument instanceof Block)) {
            throw new TypeError("Argument must be an instance of Block");
        }

        this.argument = argument;
    }

    getValue() {
        return `\\cos{${this.argument.getValue()}}`;
    }

    updateBlockElement() {
        this.block.innerHTML += this.argument.block.outerHTML;
        this.argument.block.classList.add("argument");
    }
}

class TanBlock extends FunctionBlock {
    constructor() {
        super("tan");
        this.block.classList.add("tan-block");

        const argumentValue = this.getArgumentWithPrompt();
        const argument = new NumberBlock(argumentValue);

        this.block.innerText = `${this.character}(`;
        this.setArgument(argument);
        this.addSubBlock(argument);
        this.updateBlockElement();
        this.block.innerHTML += ")";
    }

    getArgumentWithPrompt() {
        let argument = prompt(
            "Inserisci l'argomento della funzione tangente (in gradi)"
        );
        while (isNaN(argument) || argument === null || argument === "") {
            argument = prompt(
                "Inserisci un numero valido per l'argomento della funzione tangente (in gradi)"
            );
        }

        return argument;
    }

    setArgument(argument) {
        if (!(argument instanceof Block)) {
            throw new TypeError("Argument must be an instance of Block");
        }

        this.argument = argument;
    }

    getValue() {
        return `\\tan{${this.argument.getValue()}}`;
    }

    updateBlockElement() {
        this.block.innerHTML += this.argument.block.outerHTML;
        this.argument.block.classList.add("argument");
    }
}

class PowerOfNBlock extends Block {
    constructor() {
        super("pow");
        this.block.classList.add("pow-block");

        const baseValue = this.getBaseWithPrompt();
        const base = new NumberBlock(baseValue);

        const exponentValue = this.getExponentWithPrompt();
        const exponent = new NumberBlock(exponentValue);

        this.setBase(base);
        this.setExponent(exponent);
        this.updateBlockElement();
    }

    getBaseWithPrompt() {
        let base = prompt("Inserisci la base della potenza");
        while (isNaN(base) || base === null || base === "") {
            base = prompt(
                "Inserisci un numero valido per la base della potenza"
            );
        }

        return base;
    }

    getExponentWithPrompt() {
        let exponent = prompt("Inserisci l'esponente della potenza");
        while (isNaN(exponent) || exponent === null || exponent === "") {
            exponent = prompt(
                "Inserisci un numero valido per l'esponente della potenza"
            );
        }

        return exponent;
    }

    setBase(base) {
        if (!(base instanceof Block)) {
            throw new TypeError("Base must be an instance of Block");
        }

        this.base = base;
    }

    updateBlockElement() {
        this.block.innerHTML = `${this.base.block.outerHTML}<sup class="power-exponent">${this.exponent.block.outerHTML}</sup>`;
        this.base.block.classList.add("base");
        this.exponent.block.classList.add("exponent");
    }

    setExponent(exponent) {
        if (!(exponent instanceof Block)) {
            throw new TypeError("Exponent must be an instance of Block");
        }

        this.exponent = exponent;
    }

    getValue() {
        return `${this.base.getValue()}^{${this.exponent.getValue()}}`;
    }
}

class PowerOf2 extends Block {
    constructor() {
        super("pow2");
        this.block.classList.add("pow2-block");

        const baseValue = this.getBaseWithPrompt();
        const base = new NumberBlock(baseValue);

        this.setBase(base);
        this.exponent = new NumberBlock("2");
        this.updateBlockElement();
    }

    getBaseWithPrompt() {
        let base = prompt("Inserisci la base della potenza");
        while (isNaN(base) || base === null || base === "") {
            base = prompt(
                "Inserisci un numero valido per la base della potenza"
            );
        }

        return base;
    }

    setBase(base) {
        if (!(base instanceof Block)) {
            throw new TypeError("Base must be an instance of Block");
        }

        this.base = base;
    }

    updateBlockElement() {
        this.block.innerHTML = `${this.base.block.outerHTML}<sup class="power-exponent">${this.exponent.block.outerHTML}</sup>`;
        this.base.block.classList.add("base");
        this.exponent.block.classList.add("exponent");
    }

    getValue() {
        return `${this.base.getValue()}^${this.exponent.getValue()}`;
    }
}

export {
    Block,
    NumberBlock,
    OperatorBlock,
    SqrtBlock,
    FunctionBlock,
    Sqrt2Block,
    SinBlock,
    CosBlock,
    TanBlock,
    PowerOfNBlock,
    PowerOf2,
};

// README: Continua con la crezione del blocco SQRT inserendo l'indece e l'argomento, renderizzando l'immagine e mettodo il bordo al altezza giusta
