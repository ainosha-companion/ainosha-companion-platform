import { requestMarketSentiment } from "@/server/agent/api"

export async function GET() {
    const response = await requestMarketSentiment()
    return Response.json(response)
}
