import * as v from "valibot"
import {
    ArticleListSchema,
    GetOwnerArticleBySlugSchema,
    GetPublishedArticleSchema,
    GetPublishedArticlesSchema,
} from "./schema"

export type GetPublishedArticlesResponse = v.InferOutput<
    typeof GetPublishedArticlesSchema
>

export type GetPublishedArticleResponse = v.InferOutput<
    typeof GetPublishedArticleSchema
>

export type GetOwnerArticleResponse = v.InferOutput<
    typeof GetOwnerArticleBySlugSchema
>

export type Article = GetPublishedArticlesResponse["result"]["articles"][number]

export type GetArticlesResponse = v.InferOutput<typeof ArticleListSchema>
