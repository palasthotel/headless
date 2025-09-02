import {PluginDocumentSettingPanel} from '@wordpress/editor';
import {Button} from "@wordpress/components";
import {useReload, State, useCanRevalidate, usePost} from "../hooks/use-reload";
import {getFrontends} from "../store/window";
import {useEffect, useMemo, useState} from "@wordpress/element";

type Controller = {
    add: (frontend: number, fn: () => void) => void
    run: () => void
}

const buildController = (): Controller => {
    const fns = new Map<number, ()=>void>();
    return {
        add: (frontend, fn) => {
            fns.set(frontend, fn);
        },
        run: () => {
            fns.forEach((fn) => {
                fn();
            });
        }
    }
}

type FrontendProps = {
    index: number
    baseUrl: string
    controller: Controller,
    onStateChanged: (frontend: number, state: State) => void
}
const FrontendItem = (
    {
        index,
        baseUrl,
        controller,
        onStateChanged,
    }: FrontendProps
) => {
    const {
        state,
        reload,
    } = useReload(index);

    const post = usePost();

    const url = useMemo(()=>{
        const link = post.link;
        const url = new URL(link);;
        return baseUrl.replace(/^\/|\/$/g, '')+url.pathname;
    }, [post.link])

    useEffect(() => {
        controller.add(index, reload);
    }, [index]);

    useEffect(() => {
        onStateChanged(index, state);
    }, [state])

    return <div title={url}>
        <a href={url} target="_blank">Frontend {index}</a>
        {state == "loading" && <> 🧹</>}
        {state == "success" && <> ✅</>}
        {state == "error" && <> 🚨</>}
    </div>
}

export default function ReloadPanel() {

    const frontends = getFrontends();

    const controller = useMemo(() => buildController(), []);
    const [loadingState, setLoadingState] = useState<{ [key: number]: boolean}>({});
    const canRevalidate = useCanRevalidate();

    const handleClick = () => {
        controller.run();
    }

    const isLoading = Object.values(loadingState).find(value => value == true);

    return (
        <PluginDocumentSettingPanel
            title="Headless"
        >
            {canRevalidate ?
                <>
                    <ol>

                        {frontends.map((frontend, index) => {
                            return <li key={index}>
                                <FrontendItem
                                    baseUrl={frontend}
                                    index={index}
                                    controller={controller}
                                    onStateChanged={(index, state) => {
                                        setLoadingState(prev => {
                                            const copy = {...prev};
                                            copy[index] = state  == "loading";
                                            return copy;
                                        });
                                    }}
                                />
                            </li>
                        })}
                    </ol>

                    <Button
                        variant="secondary"
                        disabled={isLoading || !canRevalidate}
                        onClick={handleClick}
                    >
                        Revalidate cache
                    </Button>
                </>
                :
                <p className="description">Only published contents can be revalidated.</p>
            }


        </PluginDocumentSettingPanel>
    )
}
