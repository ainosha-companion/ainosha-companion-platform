import * as v from "valibot"

export const ArticleDraftReqSchema = v.object({
    title: v.string(),
    sortDesc: v.string(),
    tokenName: v.string(),
    symbol: v.string(),
    category: v.string(),
    targetAudience: v.string(),
    reference: v.string(),
})
