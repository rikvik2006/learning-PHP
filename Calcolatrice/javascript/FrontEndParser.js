import {
    CosBlock,
    FactorialBlock,
    NumberBlock,
    OperatorBlock,
    PowerOfNBlock,
    SinBlock,
    SqrtBlock,
    TanBlock,
} from "./blocks/Block.js";

export class FrontEndParser {
    static parseInput(input) {
        const blocks = input.split(";");

        const frontEndObjectBlocks = [];
        for (const block of blocks) {
            const frontEndObjectBlock = FrontEndParser.parseBlock(block);
            frontEndObjectBlocks.push(frontEndObjectBlock);
        }

        return frontEndObjectBlocks;
    }

    static parseBlock(block) {
        let frontEndObjectBlock;

        if (!isNaN(block)) {
            // NUMERO
            frontEndObjectBlock = new NumberBlock(block);
        } else if (["+", "-", "*", "/"].includes(block)) {
            // OPERATORE
            if (block === "+") {
                frontEndObjectBlock = new OperatorBlock("+");
            } else if (block === "-") {
                frontEndObjectBlock = new OperatorBlock("-");
            } else if (block === "*") {
                frontEndObjectBlock = new OperatorBlock("*");
            } else if (block === "/") {
                frontEndObjectBlock = new OperatorBlock("/");
            }
        } else if (block === ".") {
            frontEndObjectBlock = new NumberBlock(".");
        } else if (/^\\sqrt\[[0-9]+\]{[0-9]+}$/.test(block)) {
            // RADICE ENNESIMA
            const n = block.match(/\\sqrt\[([0-9]+)\]/)[1];
            const value = block.match(/{([0-9]+)}/)[1];

            frontEndObjectBlock = new SqrtBlock(
                new NumberBlock(n),
                new NumberBlock(value)
            );
        } else if (/^[0-9]+\^{[0-9]+}$/.test(block)) {
            // POTENZA ENNESIMA
            const base = block.match(/([0-9]+)\^{/)[1];
            const exponent = block.match(/\^{([0-9]+)}/)[1];

            frontEndObjectBlock = new PowerOfNBlock(
                new NumberBlock(base),
                new NumberBlock(exponent)
            );
        } else if (/^\\sin{-?[0-9]+}$/.test(block)) {
            // SENO
            const value = block.match(/{(-?[0-9]+)}/)[1];

            frontEndObjectBlock = new SinBlock(new NumberBlock(value));
        } else if (/^\\cos{-?[0-9]+}$/.test(block)) {
            // COSENO
            const value = block.match(/{(-?[0-9]+)}/)[1];

            frontEndObjectBlock = new CosBlock(new NumberBlock(value));
        } else if (/^\\tan{-?[0-9]+}$/.test(block)) {
            // TANGENTE
            const value = block.match(/{(-?[0-9]+)}/)[1];

            frontEndObjectBlock = new TanBlock(new NumberBlock(value));
        } else if (/^\\fact{[0-9]+}$/.test(block)) {
            // FATTORIALE
            const value = block.match(/{([0-9]+)}/)[1];

            frontEndObjectBlock = new FactorialBlock(new NumberBlock(value));
        } else {
            throw new Error(`Il blocco ${block} non è riconosciuto`);
        }

        return frontEndObjectBlock;
    }
}
