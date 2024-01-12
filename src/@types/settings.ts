import {z} from "zod";
import {settingsResponseSchema} from "../schema";

export type SettingsResponse = z.infer<typeof settingsResponseSchema>