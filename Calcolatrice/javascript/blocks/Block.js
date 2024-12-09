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
    }
}

class OperatorBlock extends Block {
    constructor(character) {
        super(character);
        this.block.classList.add("operator-block");
    }
}

class SqrtBlock extends Block {
    /**
     *
     * @param {Block} index
     * @param {Block} argument
     */
    constructor(index, argument) {
        super("../../public/sqrt_image.svg");
        this.block.classList.add("sqrt-block");
    }

    /**
     * Return a string that rappresent an n_root that follow the procol of the calculator
     * @returns {string}
     */
    getValue() {
        return `\\sqrt[${this.index.getValue()}]{${this.argument.getValue()}}`;
    }
}
