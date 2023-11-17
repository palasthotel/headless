import {
    blockSchema,
    coreImageBlockSchema,
    coreQuoteBlockSchema,
    coreTextBlockSchema,
    groupBlockSchema, isParseError, sustainingParse,
    coreListBlockSchema
} from "../src";

describe('Blocks', () => {
    test('Should parse core paragraph block', ()=>{
        const parsed = coreTextBlockSchema.safeParse({
            blockName: "core/paragraph",
            innerHTML: "<p>test</p>",
        });
        expect(parsed.success).toBeTruthy();
        if(!parsed.success){
            return;
        }
        expect(parsed.data.innerHTML).toBe("<p>test</p>")
    });

    test("Should parse core image block", ()=> {
        const parsed = coreImageBlockSchema.safeParse({
            "blockName": "core/image",
            "attrs": {
                "id": 2,
                "sizeSlug": "large",
                "linkDestination": "none",
                "image_id": 2,
                "focalPointEnabled": "",
                "focalPoint": {
                    "top": "50.00",
                    "left": "50.00"
                },
                "src": [
                    "https://www.palasthotel.de/wp-content/uploads/test.jpg",
                    2560,
                    1707,
                    false
                ],
                "sizes": [
                    [
                        "https://www.palasthotel.de/wp-content/uploads/test-150x150.jpg",
                        150,
                        150,
                        true
                    ],
                    [
                        "https://www.palasthotel.de/wp-content/uploads/test-300x200.jpg",
                        300,
                        200,
                        true
                    ],
                ],
                "alt": "some alt",
                "caption": "my caption.",
                "media_license": {
                    "media_license_info": "nil",
                    "media_license_author": "ida",
                    "media_license_url": ""
                }
            },
            "innerBlocks": []
        });
        expect(parsed.success).toBeTruthy();
        if(!parsed.success){
            return;
        }
        expect(parsed.data.attrs.id).toBe(2)
    });

    test("Should parse core quote block", ()=>{
        const parsed = coreQuoteBlockSchema.safeParse({
            "blockName": "core/quote",
            "attrs": [],
            "innerBlocks": [
                {
                    "blockName": "core/paragraph",
                    "attrs": [],
                    "innerHTML": "\n<p>«Cite!»</p>\n"
                }
            ],
            "innerHTML": "\n<blockquote class=\"wp-block-quote\"><cite>Olivia Allemann, Betriebsleiterin DILU – Drogeninformation Luzern</cite></blockquote>\n",
            "innerContent": [
                "\n<blockquote class=\"wp-block-quote\">",
                null,
                "<cite>Olivia Allemann, Betriebsleiterin DILU – Drogeninformation Luzern</cite></blockquote>\n"
            ]
        });

        expect(parsed.success).toBeTruthy();
        if(!parsed.success){
            return;
        }

        expect(parsed.data.blockName).toBe("core/quote");
        expect(parsed.data.innerBlocks.length).toBeGreaterThan(0);
    });

    test("Should parse core group block", ()=>{
        const parsed = groupBlockSchema.safeParse({
            "blockName": "core/group",
            "attrs": {
                "className": "is-style-group-inline-block",
                "layout": {
                    "type": "constrained"
                }
            },
            "innerBlocks": [
                {
                    "blockName": "core/heading",
                    "attrs": [],
                    "innerBlocks": [],
                    "innerHTML": "\n<h2 class=\"wp-block-heading\">Some heading</h2>\n",
                    "innerContent": [
                        "\n<h2 class=\"wp-block-heading\">Some heading</h2>\n"
                    ]
                },
                {
                    "blockName": "core/paragraph",
                    "attrs": [],
                    "innerHTML": "\n<p>Some paragraph.</p>\n"
                },
            ],
            "innerHTML": "\n<div class=\"wp-block-group is-style-group-inline-block\">\n\n\n\n</div>\n",
            "innerContent": [
                "\n<div class=\"wp-block-group is-style-group-inline-block\">",
                null,
                "\n\n",
                null,
                "\n\n",
                null,
                "</div>\n"
            ]
        });
        expect(parsed.success).toBeTruthy()
        if(!parsed.success){
            return;
        }
        expect(parsed.data.innerBlocks?.length).toBe(2);
    });

    test("Should parse core list block", ()=>{
        const parsed = coreListBlockSchema.parse({
            "blockName": "core/list",
            "attrs": {
                "className": "is-style-core-list-sources"
            },
            "innerBlocks": [
                {
                    "blockName": "core/list-item",
                    "attrs": [],
                    "innerBlocks": [],
                    "innerHTML": "\n<li>Item 1</li>\n"
                },
                {
                    "blockName": "core/list-item",
                    "attrs": [],
                    "innerBlocks": [],
                    "innerHTML": "\n<li>Item 2</li>\n"
                },
                {
                    "blockName": "core/list-item",
                    "attrs": [],
                    "innerBlocks": [],
                    "innerHTML": "\n<li>Item 3</li>\n"
                },
            ],
            "innerHTML": "\n<ul class=\"is-style-core-list-sources\">\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n</ul>\n",
            "innerContent": [
                "\n<ul class=\"is-style-core-list-sources\">",
                null,
                "\n\n",
                null,
                "\n\n",
                null,
                "\n\n",
                null,
                "\n\n",
                null,
                "\n\n",
                null,
                "\n\n",
                null,
                "\n\n",
                null,
                "\n\n",
                null,
                "</ul>\n"
            ]
        });

        expect(parsed.attrs?.className).toBe("is-style-core-list-sources");
        expect(parsed.innerBlocks.length).toBe(3);
        expect(parsed.innerBlocks[0].innerHTML).toBe("\n<li>Item 1</li>\n");
    })
});

describe('Custom Props', () => {
    test("Should keep custom props after parsing", ()=> {
       const parsedMinimal = sustainingParse({
           blockName: "core/paragraph",
           innerHTML: "<p>test</p>",
       },blockSchema);
       const isError = isParseError(parsedMinimal);
       expect(isError).toBeFalsy();
       if(isError) return;
       expect(parsedMinimal?.blockName).toBe("core/paragraph");
       const parsedP = coreTextBlockSchema.parse(parsedMinimal);
       expect(parsedP.innerHTML).toBe("<p>test</p>");
    });
});