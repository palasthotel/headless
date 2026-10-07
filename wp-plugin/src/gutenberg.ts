import { registerPlugin } from "@wordpress/plugins";
import ReloadPanel from "./components/ReloadPanel";
import { getPreviewUrl } from "./store/window";
import { dispatch, select, subscribe } from "@wordpress/data";
import { writeInterstitialMessage } from "./preview";

import "./styles.css";
import { isPreviewActive, isRevalidateActive } from "./store/admin-window";

if (isRevalidateActive()) {
  registerPlugin("headless-plugin", {
    icon: () => null,
    render: ReloadPanel,
  });
}

document.addEventListener("DOMContentLoaded", function () {
  if (!isPreviewActive()) {
    return;
  }

  const coreEditorSelect = select("core/editor");
  const getCurrentPostId = coreEditorSelect.getCurrentPostId;
  const isSavingPost = coreEditorSelect.isSavingPost;
  const isDraft = () => {
    const status = coreEditorSelect.getCurrentPost().status;
    return status == "draft" || status == "auto-draft";
  };

  const coreEditorDispatch = dispatch("core/editor") as {
    autosave: () => Promise<void>;
    savePost: () => Promise<void>;
  };
  const autosave = coreEditorDispatch.autosave;
  const savePost = coreEditorDispatch.savePost;
  const coreNoticesDispatch = dispatch("core/notices") as {
    createErrorNotice: (content: string) => void;
  };

  // --------------------------------------------------------
  // create replacement for preview link
  // --------------------------------------------------------
  const onPreviewClick = (e: MouseEvent) => {
    e.preventDefault();
    if (isSavingPost()) {
      return;
    }
    const link = e.currentTarget as HTMLAnchorElement;
    const ref = window.open("about:blank", link.target);
    if (ref) {
      writeInterstitialMessage(ref.document);

      const saveFn = isDraft() ? savePost : autosave;
      saveFn()
        .then(() => {
          // savePost() and autosave() resolve after a failed save too; the
          // editor shows its own error notice then
          if (coreEditorSelect.didPostSaveRequestFail()) {
            ref.close();
            return;
          }
          ref.location = link.href;
        })
        .catch(() => {
          ref.close();
          coreNoticesDispatch.createErrorNotice(
            "Preview could not be generated. Please save your changes and try again.",
          );
        });
    }
  };

  subscribe(() => {
    const headlessPreviewLinks =
      document.querySelectorAll<HTMLAnchorElement>("#headless-preview-link");
    if (isSavingPost()) {
      headlessPreviewLinks.forEach((link) => {
        link.classList.add("is-disabled");
      });
    } else {
      headlessPreviewLinks.forEach((link) => {
        link.classList.remove("is-disabled");
      });
    }
  });

  // --------------------------------------------------------
  // hack it with interval updates
  // --------------------------------------------------------
  // workaround script until there's an official solution for https://github.com/WordPress/gutenberg/issues/13998
  setInterval(checkPreview, 300);

  function checkPreview() {
    const postId = getCurrentPostId();
    const previewUrl = getPreviewUrl(postId);

    // replace all preview links
    const editorPreviewLink = document.querySelectorAll(
      "[target^=wp-preview-]",
    );
    if (editorPreviewLink && editorPreviewLink.length) {
      editorPreviewLink.forEach((link) => {
        link.setAttribute("href", previewUrl);
      });
    }

    // replace notice link
    const notices = document.querySelectorAll<HTMLAnchorElement>(
      ".components-snackbar-list .components-snackbar__content a.components-button",
    );
    notices.forEach((notice) => {
      if (
        notice.href.includes("?post=" + postId) || // custom post types
        notice.href.includes("?page_id=" + postId) || // pages
        notice.href.includes("?p=" + postId) // posts
      ) {
        notice.href = previewUrl;
        notice.target = "wp-preview-" + postId;
      }
    });

    // replace this special preview link
    const externalPreviewGroup = Array.from(
      document.querySelectorAll<HTMLElement>(".components-menu-group"),
    ).find((group) =>
      group.querySelector(
        ".editor-preview-dropdown__button-external, a[role='menuitem'][target^='wp-preview-']",
      ),
    );

    if (!externalPreviewGroup) {
      return;
    }

    const id = "headless-preview-link";
    const existingHeadlessLink =
      externalPreviewGroup.querySelector<HTMLAnchorElement>("#" + id);
    if (existingHeadlessLink) {
      existingHeadlessLink.href = previewUrl;
      return;
    }

    // is hidden via styles.css
    const gutenbergLink = externalPreviewGroup.querySelector<HTMLAnchorElement>(
      ".editor-preview-dropdown__button-external, a[role='menuitem'][target^='wp-preview-']",
    );
    if (!gutenbergLink) {
      return;
    }

    const a = gutenbergLink.cloneNode(true) as HTMLAnchorElement;
    a.href = previewUrl;
    a.id = id;
    a.addEventListener("click", onPreviewClick);
    gutenbergLink.style.display = "none";
    gutenbergLink.parentElement?.append(a);
  }
});
