import * as v from "valibot"
import { ArticleDraftReqSchema } from "./schema"

export type ArticleListItem = {
    id: number
    title: string
    slug: string
    thumbnail: string | null
    status?: string
    created_at: string | null
    updated_at: string | null
    category: {
        id: number
        name: string
    } | null
    author: {
        id: number
        name: string
    }
    tags: string[]
}

export type ArticleDraftReq = v.InferOutput<typeof ArticleDraftReqSchema>
