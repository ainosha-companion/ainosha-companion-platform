import { MetadataResponseSchema } from "@/server/_internal"
import * as v from "valibot"

export const AgentResponseSchema = v.object({
    id: v.string(),
    slug: v.string(),
    name: v.string(),
    description: v.string(),
    avatarURL: v.string(),
})

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
