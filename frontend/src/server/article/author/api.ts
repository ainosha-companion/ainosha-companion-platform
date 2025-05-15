// src/server/article/author/api.ts

import * as v from "valibot"
import http from "../../integration/http"
import { GetAuthorsSchema } from "./_internal/schema"
import { Author } from "./_internal/type"

export async function getAllAuthors(search?: string): Promise<Author[]> {
    const url = search
        ? `public/authors?search=${encodeURIComponent(search)}`
        : "public/authors"

    const response = await http.get(url).json()
    const validated = v.parse(GetAuthorsSchema, response)
    return validated.authors
}
