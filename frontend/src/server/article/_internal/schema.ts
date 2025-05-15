import { MetadataResponseSchema } from "@/server/_internal"
import * as v from "valibot"

export const ArticleItemSchema = v.object({
    id: v.number(),
    title: v.string(),
    slug: v.string(),
    thumbnail: v.nullable(v.string()),
    created_at: v.nullable(v.string()),
    updated_at: v.nullable(v.string()),
    category: v.nullable(
        v.object({
            id: v.number(),
            name: v.string(),
        }),
    ),
    author: v.object({
        id: v.number(),
        name: v.string(),
    }),
    tags: v.array(v.string()),
})

export const ArticleListItemSchema = v.object({
    id: v.number(),
    title: v.string(),
    slug: v.string(),
    category: v.nullable(
        v.object({
            id: v.number(),
            name: v.string(),
        }),
    ),
    author: v.object({
        id: v.number(),
        name: v.string(),
    }),
    status: v.nullable(v.any()),
    tags: v.array(v.string()),
    thumbnail: v.nullable(v.string()),
    created_at: v.string(),
    updated_at: v.string(),
})

export const ArticleListSchema = v.object({
    _metadata: MetadataResponseSchema,
    result: v.object({
        articles: v.array(ArticleListItemSchema),
    }),
})

export const GetPublishedArticlesSchema = v.object({
    _metadata: MetadataResponseSchema,
    result: v.object({
        articles: v.array(ArticleItemSchema),
    }),
})

export const GetPublishedArticleSchema = v.object({
    _metadata: MetadataResponseSchema,
    result: v.object({
        article: v.intersect([
            ArticleItemSchema,
            v.object({
                tags: v.array(v.string()),
                html: v.string(),
                markdown: v.string(),
                category: v.nullable(v.any()),
            }),
        ]),
    }),
})

export const GetOwnerArticleBySlugSchema = v.object({
    _metadata: MetadataResponseSchema,
    result: v.object({
        message: v.string(),
        article: v.intersect([
            ArticleItemSchema,
            v.object({
                html: v.string(),
                markdown: v.string(),
                status: v.string(),
                tags: v.array(v.string()),
            }),
        ]),
    }),
})
