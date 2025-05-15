import { getPublishedArticles } from "@/server/article/api"
import { type NextRequest } from "next/server"

export async function GET(request: NextRequest) {
    const searchParams = request.nextUrl.searchParams

    const response = await getPublishedArticles(searchParams.toString())
    return Response.json(response)
}
