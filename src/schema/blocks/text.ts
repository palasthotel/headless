import {blockSchema, groupBlockSchema} from "./base.ts";

// TODO: attrs for alignment and so on

/**
 * @description text blocks are paragraph, heading, quote and html blocks that only privide html content via innerHTML attribute
 */
export const coreTextBlockSchema = blockSchema.pick( {
    blockName: true,
    attrs: true,
    innerHTML: true,
});

export const coreQuoteBlockSchema = groupBlockSchema.required({
    blockName: true,
    innerBlocks: true,
});

export const coreHTMLBlockSchema = blockSchema.pick({
    blockName: true,
    attrs: true,
    innerHTML: true,
})