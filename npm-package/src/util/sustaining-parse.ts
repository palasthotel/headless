import {z} from "zod";

export const sustainingParse = <Type extends z.ZodTypeAny>(data:unknown, schema: Type) => {
    const parsed = schema.safeParse(data);
    if(parsed.success){
        return data as z.infer<typeof schema>
    }
    return parsed.error;
}

export const isParseError = (parsed: any): parsed is z.ZodError => parsed instanceof z.ZodError;