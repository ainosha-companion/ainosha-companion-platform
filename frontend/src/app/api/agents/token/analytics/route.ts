import { requestToken } from "@/server/agent/api"

export async function POST(request: Request) {
    const body = await request.json()
    console.log("body", body)

    const response = await requestToken(body)

    return Response.json(response)
}
