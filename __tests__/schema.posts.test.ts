import {post} from '../__test_data__/post.ts';
import {postWithBlocksResponseSchema} from "../src/schema/posts";
import {z} from "zod";
import {blockSchema} from "../src";


test("Should parse post", ()=>{

    const parsed = postWithBlocksResponseSchema.parse(post);
    expect(parsed.content.headless_blocks.length).toBeGreaterThan(0);

    const customBlock = parsed.content.headless_blocks.find(b => b.blockName == "custom/block");
    expect(customBlock?.blockName).toBe("custom/block");

    // check if parsing was none destructive
    const customBlockSchema = blockSchema.extend({
        attrs: z.object({
            content: z.object({
                title: z.string(),
            })
        })
    });

    const parsedCustomBlock = customBlockSchema.parse(customBlock);
    expect(parsedCustomBlock?.attrs.content.title).toBe("title");

});