import { TokenResponseSchema } from "@/server/agent/_internal"
import * as v from "valibot"
import { AgentResponseSchema, MarketSentimentResponseSchema } from "./schema"

export type Agent = v.InferOutput<typeof AgentResponseSchema>

export type MarketSentiment = v.InferOutput<
    typeof MarketSentimentResponseSchema
>
export type TokenReport = v.InferOutput<typeof TokenResponseSchema>
