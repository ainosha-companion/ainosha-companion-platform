import { getAllAuthors } from "@/server/article/author/api"
import { type NextRequest } from "next/server"

export async function GET(request: NextRequest) {
    const search = request.nextUrl.searchParams.get("search") ?? undefined
    const authors = await getAllAuthors(search)
    return Response.json({ authors })
}
