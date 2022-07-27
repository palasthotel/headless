import {getReloadAjaxUrl} from "../store/window";
import {useSelect} from '@wordpress/data';
import {useState} from "@wordpress/element";

type State = "idle" | "loading" | "success" | "error"

type UseReload = {
    state: State
    reload: () => void
}

type Post = {
    id: number
}
export const useReload = (): UseReload => {

    const [state, setState] = useState<State>("idle");
    const post = useSelect<Post>(
        select => select("core/editor").getCurrentPost()
    );

    return {
        state,
        reload: () => {
            setState("loading");
            fetch(getReloadAjaxUrl(post.id))
                .then(res => res.json())
                .then(json => {
                    if(json.success){
                        setState("success");
                    } else {
                        console.error(json);
                        setState("error");
                    }
                })
                .catch(() => {
                    setState("error");
                }).finally(() => {
                setTimeout(() => {
                    setState("idle");
                }, 4000);
            });
        }
    }
}