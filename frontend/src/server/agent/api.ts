import * as v from "valibot"
import http from "../integration/http"
import { MarketSentimentResponseSchema, TokenResponseSchema } from "./_internal"

export async function requestMarketSentiment() {
    const response = await http.get(`analytics/market-sentiment`).json()
    const validated = v.parse(MarketSentimentResponseSchema, response)

    return validated
}

export async function requestToken(body: { symbol: string; period: string }) {
    const response = await http.post(`analytics/token`, { json: body }).json()
    const validated = v.parse(TokenResponseSchema, response)
    return validated
}
