import {z} from "zod";
import {Block} from "../../@types";
import {isParseError, sustainingParse} from "../../util";
import {entityMetaSchema} from "@palasthotel/wp-rest";

export const blockNameSchema = z.string().nullable();

export const blockAttrSchema = entityMetaSchema

export const blockSchema = z.object({
    blockName: blockNameSchema,
    attrs: blockAttrSchema.optional(),
    innerHTML: z.string().optional(),
    innerContent: z.array(z.string().nullish()).optional(),
    innerBlocks: z.array(z.unknown()).optional(),
});

export const groupBlockSchema = blockSchema.extend({
    innerBlocks: blockSchema.array(),
})

export const zBlock = z.custom<Block>((value) => {
    return sustainingParseGenericBlock(value) ?? false;
});

const sustainingParseGenericBlock = (data:unknown) => {

    const parsed = sustainingParse(data, blockSchema);

    if(isParseError(parsed)) return parsed;

    if(parsed?.innerBlocks?.length){
        for (const innerBlock of parsed.innerBlocks) {
            const innerParsed = sustainingParse(innerBlock, blockSchema);
            if(isParseError(innerParsed)) return innerParsed;
        }
    }

    return parsed;
};