import {PluginDocumentSettingPanel} from '@wordpress/edit-post';
import {Button} from "@wordpress/components";
import {useReload} from "../hooks/use-reload";

export default function ReloadPanel(

) {

    const {
        state,
        reload,
    } = useReload();


    return (
        <PluginDocumentSettingPanel
            title="Headless"
        >
            <Button
                variant="secondary"
                disabled={state != "idle"}
                onClick={reload}
            >
                Reload cache
            </Button>
            {state == "idle" && <p className="description">
                Reload contents in headless frontend.
            </p>}
            {state == "loading" && <p>Loading...</p>}
            {state == "success" && <p>🎉 Success</p>}
            {state == "error" && <p>🚨 Something went wrong</p>}

        </PluginDocumentSettingPanel>
    )
}