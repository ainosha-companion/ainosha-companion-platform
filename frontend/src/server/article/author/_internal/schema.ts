// src/server/article/author/_internal/schema.ts

import * as v from "valibot"

export const AuthorSchema = v.object({
    id: v.number(),
    name: v.string(),
    email: v.string(),
    avatar: v.nullable(v.string()),
    created_at: v.string(),
    updated_at: v.string(),
})

export const GetAuthorsSchema = v.object({
    authors: v.array(AuthorSchema),
})
