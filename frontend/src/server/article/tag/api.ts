import * as v from "valibot"
import http from "../../integration/http"
import { GetTagsSchema } from "./_internal/schema"
import type { Tag } from "./_internal/type"

export async function getAllTags(search?: string): Promise<Tag[]> {
    const url = search
        ? `public/tags?search=${encodeURIComponent(search)}`
        : `public/tags`

    const response = await http.get(url).json()

    const validated = v.parse(GetTagsSchema, response)
    return validated.result.tags
}
