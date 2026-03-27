import {getReloadAjaxUrl} from "../store/window";
import {useSelect} from '@wordpress/data';
import {useMemo, useState} from "@wordpress/element";
import {store as editorStore} from '@wordpress/editor';

export type State = "idle" | "loading" | "success" | "error"


type Post = {
    id: number
    link: string
    status: string
}

export const usePost = (): Post|undefined => {
    return useSelect(
		(select) =>
			select(editorStore).getCurrentPost() as Post | undefined,
        []
    );
}

export const useCanRevalidate = ()=> {
    return usePost()?.status == "publish";
}


export const useReload = (frontend:number) => {

    const [state, setState] = useState<State>("idle");
    const post = usePost();

    const path = useMemo(()=>{
        if(post?.link){
            const url = new URL(post?.link);
            return url.pathname;
        } else {
            return "";
        }
    }, [post?.link]);

    return {
        state,
        reload: () => {
            setState("loading");
            (async ()=>{
                try {
                    const response = await fetch(getReloadAjaxUrl(frontend, path));
                    const json = await response.json()
                    if(json.success){
                        setState("success");
                    } else {
                        console.error(json);
                        setState("error");
                    }
                } catch(e) {
                    setState("error");
                }

            })();
        }
    }
}
