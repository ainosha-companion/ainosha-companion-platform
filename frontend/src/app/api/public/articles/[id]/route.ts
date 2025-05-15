import { getPublishedArticleBySlug } from "@/server/article/api"

async function GET(
    request: Request,
    { params }: { params: Promise<{ id: string }> },
) {
    const { id } = await params
    const response = await getPublishedArticleBySlug(id)
    return Response.json(response)
}

export { GET }
