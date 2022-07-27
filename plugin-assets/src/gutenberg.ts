import {registerPlugin} from "@wordpress/plugins";
import ReloadPanel from "./components/ReloadPanel";

registerPlugin('headless-plugin', {
    icon: () => null,
    render: ReloadPanel,
});