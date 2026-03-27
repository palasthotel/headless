import {CompareArg, CompareParam} from "../@types";

type CompareMap = {
    [key in CompareArg]: CompareParam
}

const map: CompareMap = {
    "=": "eq",
    "!=": "neq",
    like: "like",
}

export const mapQueryToParam = (arg: CompareArg): CompareParam => {
    return map[arg];
}