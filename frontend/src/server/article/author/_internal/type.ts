// src/server/article/author/_internal/type.ts

export type Author = {
    id: number
    name: string
    email: string
    avatar: string | null
    created_at: string
    updated_at: string
}

export type GetAuthorsResponse = {
    authors: Author[]
}
