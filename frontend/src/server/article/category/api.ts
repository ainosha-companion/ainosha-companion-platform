import * as v from "valibot"
import http from "../../integration/http"
import { GetCategoriesSchema } from "./_internal/schema"
import { Category } from "./_internal/type"

export async function getAllCategories(search?: string): Promise<Category[]> {
    const url = search
        ? `public/categories?search=${encodeURIComponent(search)}`
        : `public/categories`

    const response = await http.get(url).json()
    const validated = v.parse(GetCategoriesSchema, response)
    return validated.result.categories
}
