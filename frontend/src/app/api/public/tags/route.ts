import { getAllTags } from "@/server/article/tag/api"
import { type NextRequest } from "next/server"

export async function GET(request: NextRequest) {
    const search = request.nextUrl.searchParams.get("search") ?? undefined
    const tags = await getAllTags(search)

    return Response.json(tags)
}
