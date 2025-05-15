import * as v from "valibot"
import { ArticleRequestSchema } from "./schema"
export type ArticleRequest = v.InferOutput<typeof ArticleRequestSchema>
