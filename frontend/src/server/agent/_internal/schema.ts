import * as v from "valibot"
import { MetadataResponseSchema } from "../../_internal"

export const MarketSentimentResponseSchema = v.object({
    _metadata: MetadataResponseSchema,
    result: v.object({
        market: v.object({
            type: v.string(),
            html: v.string(),
            pdf: v.string(),
            expired_at: v.string(),
        }),
    }),
})

export const TokenResponseSchema = v.object({
    _metadata: v.object({
        success: v.boolean(),
    }),
    result: v.object({
        report: v.object({
            id: v.number(),
            type: v.string(),
            html: v.string(),
            pdf: v.string(),
            context: v.any(),
            created_at: v.string(),
            expired_at: v.string(),
            token: v.object({
                symbol: v.string(),
                name: v.string(),
                address: v.string(),
            }),
        }),
    }),
})
