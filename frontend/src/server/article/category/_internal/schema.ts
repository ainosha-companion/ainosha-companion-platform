import * as v from "valibot"

export const CategorySchema = v.object({
    id: v.number(),
    name: v.string(),
    slug: v.string(),
    created_at: v.string(),
    updated_at: v.string(),
})

export const GetCategoriesSchema = v.object({
    result: v.object({
        categories: v.array(CategorySchema),
    }),
})
