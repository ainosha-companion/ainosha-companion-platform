import * as v from "valibot"

export const TagSchema = v.object({
    id: v.number(),
    name: v.string(),
    created_at: v.string(),
    updated_at: v.string(),
})

export const GetTagsSchema = v.object({
    _metadata: v.object({
        success: v.boolean(),
    }),
    result: v.object({
        tags: v.array(TagSchema),
    }),
})
