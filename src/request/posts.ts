import {GetPostByIdRequestArgs, getPostRequest, getPostsRequest} from "@palasthotel/wp-rest";
import {mapQueryToParam} from "../mapping/compare.ts";
import {asHeadlessRequest} from "./headless.ts";
import {GetHeadlessPostsRequestArgs, HeadlessParam} from "../@types";

export const getPostsWithBlocksRequest = (args: GetHeadlessPostsRequestArgs, param: HeadlessParam = {}) => {
    if (Array.isArray(args.hl_meta_query)) {
        args.hl_meta_keys = [
            ...(args.hl_meta_query.map(q => q.key)),
            ...(args.hl_meta_keys ?? [])
        ];
        args.hl_meta_values = [
            ...(args.hl_meta_query.map(q => q.value)),
            ...(args.hl_meta_values ?? [])
        ];
        args.hl_meta_compares = [
            ...(args.hl_meta_query.map(
                    q => mapQueryToParam(q.compare))
            ),
            ...(args.hl_meta_compares ?? [])
        ];
        delete args.hl_meta_query;
    }
    if(Array.isArray(args.hl_meta_keys)){
        if(
            args.hl_meta_keys.length != args.hl_meta_values?.length ||
            args.hl_meta_keys.length != args.hl_meta_compares?.length
        ) {
            console.warn("hl_meta_ fields need have the same length");
        }
    }

    return asHeadlessRequest(
        getPostsRequest(args),
        param
    );
}

export const getPostWithBlocksRequest = (args: GetPostByIdRequestArgs, param: HeadlessParam = {}) => {
    return asHeadlessRequest(getPostRequest(args), param);
}