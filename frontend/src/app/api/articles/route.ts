import { getAllArticles } from "@/server/article/api"
import { articleRequest } from "@/server/article/request/api"
import { type NextRequest, NextResponse } from "next/server"
export async function GET(request: NextRequest) {
    try {
        const searchParams = request.nextUrl.searchParams
        const articles = await getAllArticles(searchParams.toString())
        return NextResponse.json(articles.result.articles)
    } catch (error: any) {
        throw new Error(error)
    }
}

export async function POST(request: NextRequest): Promise<Response> {
    try {
        const body = await request.json()
        await articleRequest(body)
        return NextResponse.json({})
    } catch (error: any) {
        throw new Error(error)
    }
}
