import {zBlock} from "../src";

test('Should parse', ()=>{

    const simple = zBlock.parse({
        blockName: "test",
    });

    expect(simple.blockName).toBe("test");
})