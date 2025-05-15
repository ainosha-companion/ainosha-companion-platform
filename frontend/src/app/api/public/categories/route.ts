import { getAllCategories } from "@/server/article/category/api"
import { type NextRequest } from "next/server"

export async function GET(request: NextRequest) {
    const search = request.nextUrl.searchParams.get("search") ?? undefined
    const categories = await getAllCategories(search)
    return Response.json({ result: { categories } })
}
