import { z } from "zod";

/** WP reading settings where "Your latest posts" is set as the front page. */
const settingsForPostsSchema = z.object({
  front_page: z.literal("posts"),
  // page_on_front is 0 when no static page is selected
  page_on_front: z.literal(0),
  home_url: z.string(),
});

/** WP reading settings where a static page is set as the front page. */
const settingsForPageSchema = z.object({
  front_page: z.literal("page"),
  // ID of the page assigned as front page
  page_on_front: z.coerce.number().gt(0),
  home_url: z.string(),
});

/** Response schema for the headless /settings endpoint. Discriminated by front_page. */
export const settingsResponseSchema = z.discriminatedUnion("front_page", [
  settingsForPageSchema,
  settingsForPostsSchema,
]);
